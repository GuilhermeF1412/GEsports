from mwrogue.esports_client import EsportsClient
from datetime import datetime

site = EsportsClient("lol")

response = site.cargo_client.query(
    tables="Teams=T",
    fields="T.OverviewPage, T.Image"
)

print (response)