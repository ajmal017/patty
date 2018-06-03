# patty
Stock Market Comparison



## CRON Schedule
1. Call for daily processing features
```
10 1 * * * /usr/bin/python3 /~/patty/company.py daily
10 3 * * * /usr/bin/python3 /~/patty/playlist.py daily
10 5 * * * /usr/bin/python3 /~/patty/model.py daily
```
2. Call hourly for updates requiring hourly check
```
10 * * * * /usr/bin/python3 /~/patty/company.py hourly
30 * * * * /usr/bin/python3 /~/patty/playlist.py hourly
50 * * * * /usr/bin/python3 /~/patty/model.py hourly
```
