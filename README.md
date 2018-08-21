# patty
Stock Market Comparison



## CRON Schedule
1. Call for daily processing features
```
10 1 * * * /usr/bin/python3 /~/patty/company.py daily
10 2 * * * /usr/bin/python3 /~/patty/company.py baily
10 3 * * * /usr/bin/python3 /~/patty/playlist.py daily
10 5 * * * /usr/bin/python3 /~/patty/model.py daily
10 7 * * * /usr/bin/python3 /~/patty/watch.py daily
```

2. Call hourly for updates requiring hourly check
```
10 * * * * /usr/bin/python3 /~/patty/company.py hourly
20 * * * * /usr/bin/python3 /~/patty/playlist.py hourly
30 * * * * /usr/bin/python3 /~/patty/model.py hourly
40 * * * * /usr/bin/python3 /~/patty/watch.py hourly
```

3. Call minute for updates requiring minutely check
```
* * * * *  /usr/bin/python3 /~/patty/playlist.py minute
```
