from fastapi import FastAPI
from mwrogue.esports_client import EsportsClient
from datetime import datetime

app = FastAPI()

@app.get("/data")
def get_data():
    site = EsportsClient("lol")
    overview_page = "LEC/2024 Season/Summer Season"

    response = site.cargo_client.query(
        tables="ScoreboardGames=SG, Tournaments=T",
        where=f"T.OverviewPage='{overview_page}'",
        join_on="SG.OverviewPage=T.OverviewPage",
        fields="T.Name, SG.DateTime_UTC, SG.Team1, SG.Team2"
    )

    return response

@app.get("/TodayMatches")
def get_today_matches():
    site = EsportsClient("lol")

    # Get today's date
    today_date_str = datetime.now().strftime('%Y-%m-%d')

    # Construct the where clause to fetch matches for today
    where_clause = f"MS.DateTime_UTC >= '{today_date_str} 00:00:00' AND MS.DateTime_UTC <= '{today_date_str} 23:59:59'"

    order_by_clause = "T.Name"

    response = site.cargo_client.query(
        tables="MatchSchedule=MS, Tournaments=T, Teams=Team1, Teams=Team2",
        where=where_clause,
        join_on="MS.OverviewPage=T.OverviewPage, MS.Team1=Team1.OverviewPage, MS.Team2=Team2.OverviewPage",
        fields="T.Name, MS.DateTime_UTC, MS.Team1, MS.Team1Score, MS.Team2, MS.Team2Score, MS.Winner, MS.Stream, Team1.Image=Team1Image, Team2.Image=Team2Image, MS.BestOf, Team1.OverviewPage=Team1OverviewPage, Team2.OverviewPage=Team2OverviewPage",
        order_by=order_by_clause
    )

    return response

@app.get("/AllTournaments")
def get_all_tournaments():
    site = EsportsClient("lol")

    response = site.cargo_client.query(
        tables="Tournaments=T",
        fields="T.OverviewPage, T.Region, T.Name"
    )

    return response

@app.get("/TodayTournaments")
def get_today_tournaments():
    site = EsportsClient("lol")

    today_date_str = datetime.now().strftime('%Y-%m-%d')

    where_clause = f"MS.DateTime_UTC >= '{today_date_str} 00:00:00' AND MS.DateTime_UTC <= '{today_date_str} 23:59:59'"

    response = site.cargo_client.query(
        tables="MatchSchedule=MS, Tournaments=T",
        join_on="MS.OverviewPage=T.OverviewPage",
        where=where_clause,
        fields="T.OverviewPage, T.Country, T.Name",
        group_by="T.OverviewPage"
    )

    return response

@app.get("/AllTeamImages")
def get_all_team_images():
    site = EsportsClient("lol")

    response = site.cargo_client.query(
        tables="Teams=T",
        fields="T.OverviewPage, T.Image"
    )

    return response

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)
