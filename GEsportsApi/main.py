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

if __name__ == "__main__":
    import uvicorn
    uvicorn.run("main:app", host="0.0.0.0", port=8001, reload=True)
