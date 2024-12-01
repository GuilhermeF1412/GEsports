from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from mwrogue.esports_client import EsportsClient
from datetime import datetime
from typing import List, Optional
from pydantic import BaseModel

app = FastAPI()

# Add CORS middleware
app.add_middleware(
    CORSMiddleware,
    allow_origins=["http://localhost:8000"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

class TeamImage(BaseModel):
    OverviewPage: Optional[str]
    Image: Optional[str]

class Match(BaseModel):
    Name: str
    DateTime_UTC: str
    Team1: str
    Team1Score: Optional[int]
    Team1Final: Optional[str]
    Team2: str
    Team2Score: Optional[int]
    Team2Final: Optional[str]
    Winner: Optional[str]
    Stream: Optional[str]
    BestOf: Optional[int]
    Team1Image: Optional[str]
    Team2Image: Optional[str]
    Team1OverviewPage: Optional[str]
    Team2OverviewPage: Optional[str]
    Phase: Optional[str]
    ShownName: Optional[str]
    MatchDay: Optional[int]
    HasTime: Optional[bool]
    Venue: Optional[str]
    GroupName: Optional[str]

class MatchGame(BaseModel):
    Team1: str
    Team2: str
    WinTeam: Optional[str]
    LossTeam: Optional[str]
    Team1Score: Optional[int]
    Team2Score: Optional[int]
    Winner: Optional[str]
    Gamelength: Optional[str]
    Team1Bans: Optional[str]
    Team2Bans: Optional[str]
    Team1Picks: Optional[str]
    Team2Picks: Optional[str]
    Team1Players: Optional[str]
    Team2Players: Optional[str]
    Team1Dragons: Optional[int]
    Team2Dragons: Optional[int]
    Team1Barons: Optional[int]
    Team2Barons: Optional[int]
    Team1Towers: Optional[int]
    Team2Towers: Optional[int]
    Team1Gold: Optional[float]
    Team2Gold: Optional[float]
    Team1Kills: Optional[int]
    Team2Kills: Optional[int]
    VOD: Optional[str]
    Team1RiftHeralds: Optional[int]
    Team2RiftHeralds: Optional[int]
    Team1Inhibitors: Optional[int]
    Team2Inhibitors: Optional[int]
    DateTime_UTC: str

@app.get("/TodayMatches", response_model=List[Match])
def get_today_matches(date: Optional[str] = None):
    site = EsportsClient("lol")

    try:
        # Use provided date or default to today
        if not date:
            date = datetime.now().strftime('%Y-%m-%d')
        
        print(f"Received date parameter: {date}")
        
        # Parse and validate the date
        parsed_date = datetime.strptime(date, '%Y-%m-%d')
        
        # Format date for SQL pattern matching
        date_pattern = parsed_date.strftime('%Y-%m-%d')
        print(f"Using date pattern: {date_pattern}")
        
        # Match using exact date match
        where_clause = f"""
            MS.DateTime_UTC LIKE '{date_pattern}%' AND
            MS.IsNullified = false
        """
        
        print(f"Using where clause: {where_clause}")

        response = site.cargo_client.query(
            tables="MatchSchedule=MS, Tournaments=T, Teams=Team1, Teams=Team2",
            where=where_clause,
            join_on="MS.OverviewPage=T.OverviewPage, MS.Team1=Team1.OverviewPage, MS.Team2=Team2.OverviewPage",
            fields="""
                T.Name, MS.DateTime_UTC=DateTime_UTC,
                MS.Team1, MS.Team1Score, MS.Team1Final,
                MS.Team2, MS.Team2Score, MS.Team2Final,
                MS.Winner, MS.Stream, MS.BestOf,
                Team1.Image=Team1Image, Team2.Image=Team2Image,
                Team1.OverviewPage=Team1OverviewPage, Team2.OverviewPage=Team2OverviewPage,
                MS.Phase, MS.ShownName, MS.MatchDay, MS.HasTime,
                MS.Venue, MS.GroupName
            """,
            order_by="MS.DateTime_UTC, T.Name"
        )
        
        print(f"Raw API response: {response}")
        
        if response:
            print(f"First match: {response[0]}")
            
        return response
    except ValueError as e:
        print(f"Date parsing error: {e}")
        raise HTTPException(status_code=400, detail=f"Invalid date format: {str(e)}")
    except Exception as e:
        print(f"Query error: {e}")
        raise HTTPException(status_code=500, detail=f"Error fetching matches: {str(e)}")

@app.get("/AllTeamImages", response_model=List[TeamImage])
def get_all_team_images():
    site = EsportsClient("lol")
    try:
        return site.cargo_client.query(
            tables="Teams=T",
            fields="T.OverviewPage, T.Image"
        )
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error fetching team images: {str(e)}")

@app.get("/MatchGames", response_model=List[MatchGame])
def get_match_games(team1: str, team2: str, date: str):
    site = EsportsClient("lol")
    
    try:
        # Parse and validate the date
        parsed_date = datetime.strptime(date, '%Y-%m-%d')
        date_pattern = parsed_date.strftime('%Y-%m-%d')
        
        print(f"Searching for games between {team1} and {team2} on {date_pattern}")
        
        where_clause = f"""
            SG.DateTime_UTC LIKE '{date_pattern}%' AND
            (
                (SG.Team1 LIKE '%{team1}%' OR SG.Team1 LIKE '%{team2}%') AND
                (SG.Team2 LIKE '%{team1}%' OR SG.Team2 LIKE '%{team2}%')
            )
        """
        
        print(f"Using where clause: {where_clause}")
        
        response = site.cargo_client.query(
            tables="ScoreboardGames=SG",
            where=where_clause,
            fields="""
                SG.Team1, SG.Team2, SG.WinTeam, SG.LossTeam,
                SG.Team1Score, SG.Team2Score, SG.Winner,
                SG.Gamelength, SG.Team1Bans, SG.Team2Bans,
                SG.Team1Picks, SG.Team2Picks, SG.Team1Players,
                SG.Team2Players, SG.Team1Dragons, SG.Team2Dragons,
                SG.Team1Barons, SG.Team2Barons, SG.Team1Towers,
                SG.Team2Towers, SG.Team1Gold, SG.Team2Gold,
                SG.Team1Kills, SG.Team2Kills, SG.VOD,
                SG.Team1RiftHeralds, SG.Team2RiftHeralds,
                SG.Team1Inhibitors, SG.Team2Inhibitors,
                SG.DateTime_UTC=DateTime_UTC
            """,
            order_by="SG.DateTime_UTC"
        )
        
        if response:
            # Transform the response to match our model
            transformed_response = []
            for game in response:
                # Create a new dict with the correct field name
                game_dict = dict(game)
                if 'DateTime UTC' in game_dict:
                    game_dict['DateTime_UTC'] = game_dict.pop('DateTime UTC')
                transformed_response.append(game_dict)
            
            print(f"Found {len(transformed_response)} games")
            if transformed_response:
                print(f"First game: {transformed_response[0]}")
            
            return transformed_response
            
    except Exception as e:
        print(f"Error in get_match_games: {str(e)}")
        raise HTTPException(status_code=500, detail=f"Error fetching game details: {str(e)}")

@app.get("/test")
def test():
    return {"message": "API is working"}

if __name__ == "__main__":
    import uvicorn
    uvicorn.run("main:app", host="0.0.0.0", port=8001, reload=True)
