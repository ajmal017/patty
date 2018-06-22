import sys
from datetime import datetime, date, timedelta

def progressbar(count, total, status=''):
    bar_len = 60
    filled_len = int(round(bar_len * count / float(total)))

    percents = round(100.0 * count / float(total), 1)
    bar = '=' * filled_len + '-' * (bar_len - filled_len)

    sys.stdout.write('[%s] %s%s ...%s\r' % (bar, percents, '%', status))
    sys.stdout.flush()  # As suggested by Rom Ruben (see: http://stackoverflow.com/questions/3173320/text-progress-bar-in-the-console/27871113#comment50529068_27871113)

def dformat(format = "%Y-%m-%d", subtract_date = 0):
    if subtract_date == 0:
        return str(date.today().strftime(format))
    find_date = date.today() - timedelta(subtract_date)
    return str(find_date.strftime("%Y-%m-%d"))

def dsformat(date_str, subtract_date = 0, format = "%Y-%m-%d"):
    a = datetime.strptime(date_str, format)
    dd = datetime.date(a)
    if subtract_date == 0:
       return str(dd.strftime(format))
    find_date = dd - timedelta(subtract_date)
    return str(find_date.strftime(format))

def getDateTime():
    return str(datetime.now().strftime("%Y-%m-%d %H:%M:%S"))
