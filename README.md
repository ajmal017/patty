# patty
Stock Market Comparison



## CRON Schedule
1. look for new companies on list
```
10 4 * * * /usr/bin/python3 /~/patty/company_search.py
```
2. get daily updates for each company
```
4 22 * * * /usr/bin/python3 /~/patty/company_daily.py
```
3. get history for new companies
```
50 * * * * /usr/bin/python3 /~/patty/company_history.py
```
