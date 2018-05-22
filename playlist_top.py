from urllib.parse import quote_plus, unquote_plus, urlparse, parse_qs
from bs4 import BeautifulSoup
import requests
import sys
from system.Helper import progressbar
from system.analytics.Stopwatch import Stopwatch
from application.model.Playlist import Playlist
from application.model.CompanyStock import CompanyStock
from datetime import date, timedelta

yesterday = date.today() - timedelta(1)

cs = CompanyStock.new()
cs.date = str(yesterday.strftime("%Y-%m-%d"))
stock_list = cs.getList(sort_by = 'percentage', sort_direction = 'desc', limit = 100)

print(yesterday.strftime("%Y-%m-%d"))
for i,stock in enumerate(stock_list):
    playlist = Playlist.new({
        "type"          : 1,
        "rank"          : i,
        "company_idx"   : stock.company_idx,
        "date"          : cs.date
    })
    check = playlist.get()
    if check.idx == None:
        playlist.create()
