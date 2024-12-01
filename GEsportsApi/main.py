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

class PlayerStats(BaseModel):
    name: str
    champion: str
    kills: Optional[int]
    deaths: Optional[int]
    assists: Optional[int]
    gold: Optional[int]
    cs: Optional[int]
    role: Optional[str]
    damage: Optional[int]
    vision: Optional[int]
    items: List[str] = []
    trinket: Optional[str]
    keystone: Optional[str]
    primaryTree: Optional[str]
    secondaryTree: Optional[str]
    summonerSpells: List[str] = []

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
    Team1Players: List[PlayerStats] = []
    Team2Players: List[PlayerStats] = []

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
        parsed_date = datetime.strptime(date, '%Y-%m-%d')
        date_pattern = parsed_date.strftime('%Y-%m-%d')
        
        # Step 1: Get MatchId
        try:
            match_query = site.cargo_client.query(
                tables="MatchSchedule",
                where=f"""
                    DateTime_UTC LIKE '{date_pattern}%' AND
                    Team1 = '{team1}' AND Team2 = '{team2}'
                """,
                fields="MatchId, DateTime_UTC",
                limit=1
            )
            print("Step 1 successful")
        except Exception as e:
            print(f"Step 1 failed: {str(e)}")
            raise HTTPException(status_code=500, detail=f"Error in Step 1: {str(e)}")
        
        if not match_query:
            return []
            
        match_id = match_query[0]['MatchId']
        print(f"\nStep 1 - Found MatchId: {match_id}")
        
        # Step 2: Get games - Use simpler query
        try:
            games = site.cargo_client.query(
                tables="ScoreboardGames",
                where=f"DateTime_UTC LIKE '{date_pattern}%' AND Team1 = '{team1}' AND Team2 = '{team2}'",
                fields="""
                    Team1, Team2, WinTeam, LossTeam,
                    Team1Score, Team2Score, Winner, Gamelength,
                    Team1Bans, Team2Bans, Team1Picks, Team2Picks,
                    Team1Dragons, Team2Dragons, Team1Barons, Team2Barons,
                    Team1Towers, Team2Towers, Team1Gold, Team2Gold,
                    Team1Kills, Team2Kills, Team1RiftHeralds, Team2RiftHeralds,
                    Team1Inhibitors, Team2Inhibitors, DateTime_UTC, GameId, VOD
                """,
                order_by="DateTime_UTC"
            )
            print("Step 2 successful")
        except Exception as e:
            print(f"Step 2 failed: {str(e)}")
            raise HTTPException(status_code=500, detail=f"Error in Step 2: {str(e)}")
        
        print(f"\nStep 2 - Found {len(games)} games")
        if games:
            print("First game:", games[0])
        
        transformed_games = []
        for game in games:
            try:
                game_id = game['GameId']
                print(f"\nProcessing game with GameId: {game_id}")
                
                transformed_game = {
                    'Team1': game['Team1'],
                    'Team2': game['Team2'],
                    'WinTeam': game['WinTeam'],
                    'LossTeam': game['LossTeam'],
                    'Team1Score': game['Team1Score'],
                    'Team2Score': game['Team2Score'],
                    'Winner': game['Winner'],
                    'Gamelength': game['Gamelength'],
                    'Team1Bans': game['Team1Bans'],
                    'Team2Bans': game['Team2Bans'],
                    'Team1Picks': game['Team1Picks'],
                    'Team2Picks': game['Team2Picks'],
                    'Team1Dragons': int(game['Team1Dragons']) if game['Team1Dragons'] else 0,
                    'Team2Dragons': int(game['Team2Dragons']) if game['Team2Dragons'] else 0,
                    'Team1Barons': int(game['Team1Barons']) if game['Team1Barons'] else 0,
                    'Team2Barons': int(game['Team2Barons']) if game['Team2Barons'] else 0,
                    'Team1Towers': int(game['Team1Towers']) if game['Team1Towers'] else 0,
                    'Team2Towers': int(game['Team2Towers']) if game['Team2Towers'] else 0,
                    'Team1Gold': float(game['Team1Gold']) if game['Team1Gold'] else 0.0,
                    'Team2Gold': float(game['Team2Gold']) if game['Team2Gold'] else 0.0,
                    'Team1Kills': int(game['Team1Kills']) if game['Team1Kills'] else 0,
                    'Team2Kills': int(game['Team2Kills']) if game['Team2Kills'] else 0,
                    'Team1RiftHeralds': int(game['Team1RiftHeralds']) if game['Team1RiftHeralds'] else 0,
                    'Team2RiftHeralds': int(game['Team2RiftHeralds']) if game['Team2RiftHeralds'] else 0,
                    'Team1Inhibitors': int(game['Team1Inhibitors']) if game['Team1Inhibitors'] else 0,
                    'Team2Inhibitors': int(game['Team2Inhibitors']) if game['Team2Inhibitors'] else 0,
                    'DateTime_UTC': game.get('DateTime UTC', ''),
                    'VOD': game.get('VOD', None),
                    'Team1Players': [],
                    'Team2Players': []
                }

                # Step 3: Get players
                try:
                    players_query = site.cargo_client.query(
                        tables="ScoreboardPlayers",
                        where=f"DateTime_UTC = '{game.get('DateTime UTC', '')}'",  # Use datetime instead of GameId
                        fields="""
                            Name, Champion, Team, Role,
                            Kills, Deaths, Assists,
                            Gold, CS, DamageToChampions,
                            VisionScore, Items, Trinket,
                            KeystoneRune, PrimaryTree,
                            SecondaryTree, SummonerSpells
                        """,
                        order_by="Role_Number"
                    )
                    print("Step 3 successful")
                except Exception as e:
                    print(f"Step 3 failed: {str(e)}")
                    raise HTTPException(status_code=500, detail=f"Error in Step 3: {str(e)}")
                
                print(f"Found {len(players_query)} players")
                
                for player in players_query:
                    player_data = {
                        'name': player['Name'],
                        'champion': player['Champion'],
                        'kills': int(player['Kills']) if player['Kills'] else 0,
                        'deaths': int(player['Deaths']) if player['Deaths'] else 0,
                        'assists': int(player['Assists']) if player['Assists'] else 0,
                        'gold': int(player['Gold']) if player['Gold'] else 0,
                        'cs': int(player['CS']) if player['CS'] else 0,
                        'role': player['Role'],
                        'damage': int(player['DamageToChampions']) if player['DamageToChampions'] else 0,
                        'vision': int(player['VisionScore']) if player['VisionScore'] else 0,
                        'items': player['Items'].split(';') if player['Items'] else [],
                        'trinket': player['Trinket'],
                        'keystone': player['KeystoneRune'],
                        'primaryTree': player['PrimaryTree'],
                        'secondaryTree': player['SecondaryTree'],
                        'summonerSpells': player['SummonerSpells'].split(',') if player['SummonerSpells'] else []
                    }
                    
                    if player['Team'] == game['Team1']:
                        transformed_game['Team1Players'].append(player_data)
                    else:
                        transformed_game['Team2Players'].append(player_data)

                transformed_games.append(transformed_game)
            except Exception as e:
                print(f"Error processing game {game_id}: {str(e)}")
                continue

        return transformed_games
            
    except Exception as e:
        print(f"Error in get_match_games: {str(e)}")
        raise HTTPException(status_code=500, detail=f"Error fetching game details: {str(e)}")

@app.get("/test")
def test():
    return {"message": "API is working"}

@app.get("/TestMatchSchedule")
def test_match_schedule():
    site = EsportsClient("lol")
    try:
        # Try to get a single match from today
        response = site.cargo_client.query(
            tables="MatchSchedule",
            fields="DateTime_UTC, Team1, Team2, MatchId",
            where="DateTime_UTC LIKE '2024-11-30%'",
            limit=1
        )
        
        print("\nQuery response:", response)
        return response
        
    except Exception as e:
        print(f"Error in test: {str(e)}")
        return {
            "error": str(e),
            "type": type(e).__name__,
            "location": "test_match_schedule",
            "details": getattr(e, 'details', None)
        }

@app.get("/TestScoreboardGames")
def test_scoreboard_games():
    site = EsportsClient("lol")
    try:
        # Try to get a single game from today
        response = site.cargo_client.query(
            tables="ScoreboardGames",
            fields="DateTime_UTC, Team1, Team2, GameId",
            where="DateTime_UTC LIKE '2024-11-30%'",
            limit=1
        )
        
        print("\nQuery response:", response)
        return response
        
    except Exception as e:
        print(f"Error in test: {str(e)}")
        return {
            "error": str(e),
            "type": type(e).__name__,
            "location": "test_scoreboard_games",
            "details": getattr(e, 'details', None)
        }

@app.get("/TestScoreboardPlayers")
def test_scoreboard_players():
    site = EsportsClient("lol")
    try:
        # Try to get a single player record from today
        response = site.cargo_client.query(
            tables="ScoreboardPlayers",
            fields="DateTime_UTC, Team, Name, Champion, GameId",
            where="DateTime_UTC LIKE '2024-11-30%'",
            limit=1
        )
        
        print("\nQuery response:", response)
        return response
        
    except Exception as e:
        print(f"Error in test: {str(e)}")
        return {
            "error": str(e),
            "type": type(e).__name__,
            "location": "test_scoreboard_players",
            "details": getattr(e, 'details', None)
        }

@app.get("/TestTables")
def test_tables():
    site = EsportsClient("lol")
    try:
        # Get list of all tables
        tables = site.cargo_client.get_tables()
        
        print("\nAvailable tables:")
        for table in tables:
            print(f"- {table}")
            
        return {"tables": tables}
        
    except Exception as e:
        print(f"Error getting tables: {str(e)}")
        raise HTTPException(status_code=500, detail=f"Error getting tables: {str(e)}")

@app.get("/TestSpecificGame")
def test_specific_game():
    site = EsportsClient("lol")
    try:
        # 1. Get MatchId from MatchSchedule
        match_query = site.cargo_client.query(
            tables="MatchSchedule=MS",
            where="""
                MS.DateTime_UTC LIKE '2024-11-30%' AND
                MS.Team1 = 'DRX' AND MS.Team2 = 'BNK FearX'
            """,
            fields="MS.MatchId",
            limit=1
        )
        
        if not match_query:
            return {"error": "Match not found"}
            
        match_id = match_query[0]['MatchId']
        print(f"Found MatchId: {match_id}")
        
        # 2. Get GameId from ScoreboardGames using MatchId
        game_query = site.cargo_client.query(
            tables="ScoreboardGames=SG",
            where=f"SG.MatchId = '{match_id}'",
            fields="SG.*",
            limit=1
        )
        
        if not game_query:
            return {"error": "Game not found"}
            
        game = game_query[0]
        game_id = game['GameId']
        print(f"Found GameId: {game_id}")
        
        # 3. Get player details using GameId
        players_query = site.cargo_client.query(
            tables="ScoreboardPlayers=SP",
            where=f"SP.GameId = '{game_id}'",
            fields="""
                SP.Name, SP.Champion, SP.Team,
                SP.Kills, SP.Deaths, SP.Assists,
                SP.Gold, SP.CS, SP.Role,
                SP.DamageToChampions, SP.VisionScore,
                SP.Items, SP.Trinket,
                SP.KeystoneRune, SP.PrimaryTree,
                SP.SecondaryTree, SP.SummonerSpells
            """,
            order_by="SP.Role_Number"
        )
        
        print(f"Found {len(players_query)} players")
        if players_query:
            print("First player:", players_query[0])
            
        return {
            "match_id": match_id,
            "game_id": game_id,
            "game": game,
            "players": players_query
        }
        
    except Exception as e:
        print(f"Error in test: {str(e)}")
        return {
            "error": str(e),
            "type": type(e).__name__,
            "location": "test_specific_game",
            "details": getattr(e, 'details', None)
        }

@app.get("/TestFields")
def test_fields():
    site = EsportsClient("lol")
    try:
        # Get a single record from each table with all fields
        match_fields = site.cargo_client.query(
            tables="MatchSchedule",
            fields="_pageName, *",  # Include all fields
            limit=1
        )
        
        game_fields = site.cargo_client.query(
            tables="ScoreboardGames",
            fields="_pageName, *",  # Include all fields
            limit=1
        )
        
        player_fields = site.cargo_client.query(
            tables="ScoreboardPlayers",
            fields="_pageName, *",  # Include all fields
            limit=1
        )
        
        # Print raw results for debugging
        print("\nMatchSchedule fields:", match_fields[0] if match_fields else "No data")
        print("\nScoreboardGames fields:", game_fields[0] if game_fields else "No data")
        print("\nScoreboardPlayers fields:", player_fields[0] if player_fields else "No data")
        
        return {
            "match_fields": list(match_fields[0].keys()) if match_fields else [],
            "game_fields": list(game_fields[0].keys()) if game_fields else [],
            "player_fields": list(player_fields[0].keys()) if player_fields else [],
            "match_sample": match_fields[0] if match_fields else None,
            "game_sample": game_fields[0] if game_fields else None,
            "player_sample": player_fields[0] if player_fields else None
        }
        
    except Exception as e:
        print(f"Error in test: {str(e)}")
        return {
            "error": str(e),
            "type": type(e).__name__,
            "location": "test_fields",
            "details": getattr(e, 'details', None)
        }

@app.get("/TestGameQuery")
def test_game_query():
    site = EsportsClient("lol")
    try:
        # Try a simple query to ScoreboardGames
        game_query = site.cargo_client.query(
            tables="ScoreboardGames",  # No alias
            fields="DateTime_UTC, Team1, Team2, MatchId, GameId",  # Just basic fields
            where="DateTime_UTC LIKE '2024-11-30%'",  # Just date filter
            limit=5  # Get a few records
        )
        
        print("\nFound games:", len(game_query))
        if game_query:
            for game in game_query:
                print("\nGame:", game)
            
        return {
            "games": game_query
        }
        
    except Exception as e:
        print(f"Error in test: {str(e)}")
        return {
            "error": str(e),
            "type": type(e).__name__,
            "location": "test_game_query",
            "details": getattr(e, 'details', None)
        }

@app.get("/TestMatchSteps")
def test_match_steps():
    site = EsportsClient("lol")
    try:
        # Step 1: Get MatchId
        match_query = site.cargo_client.query(
            tables="MatchSchedule",
            where="""
                DateTime_UTC LIKE '2024-11-30%' AND
                Team1 = 'DRX' AND Team2 = 'BNK FearX'
            """,
            fields="MatchId, DateTime_UTC",
            limit=1
        )
        
        if not match_query:
            return {"error": "Match not found"}
            
        match_id = match_query[0]['MatchId']
        print("\nStep 1 - Found MatchId:", match_id)
        
        # Step 2: Get game details
        game_query = site.cargo_client.query(
            tables="ScoreboardGames",
            where=f"DateTime_UTC LIKE '2024-11-30%' AND Team1 = 'DRX' AND Team2 = 'BNK FearX'",
            fields="GameId, MatchId, DateTime_UTC",
            limit=1
        )
        
        if game_query:
            game_id = game_query[0]['GameId']
            print("\nStep 2 - Found GameId:", game_id)
        
        # Step 3: Get player details
        if game_query:
            players_query = site.cargo_client.query(
                tables="ScoreboardPlayers",
                where=f"GameId = '{game_id}'",
                fields="Name, Champion, Team, Role",
                limit=10
            )
            
            print(f"\nStep 3 - Found {len(players_query)} players")
            if players_query:
                print("First player:", players_query[0])
        
        return {
            "step1_match": match_query[0] if match_query else None,
            "step2_game": game_query[0] if game_query else None,
            "step3_players": players_query if game_query and players_query else None
        }
        
    except Exception as e:
        print(f"Error in test: {str(e)}")
        return {
            "error": str(e),
            "type": type(e).__name__,
            "location": "test_match_steps",
            "step": "unknown",
            "details": getattr(e, 'details', None)
        }

if __name__ == "__main__":
    import uvicorn
    uvicorn.run("main:app", host="0.0.0.0", port=8001, reload=True)
