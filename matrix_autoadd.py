from urllib.parse import quote_plus, unquote_plus, urlparse, parse_qs
from bs4 import BeautifulSoup
import requests
import sys
from datetime import datetime
from datetime import timedelta
from system.Helper import progressbar
from system.analytics.Stopwatch import Stopwatch
from application.model.Matrix import MATRIX_PROCESSED
from application.model.Matrix import Matrix

class MatrixPreload:

    def init(self):

        # start stopwatch
        stopwatch = Stopwatch.init()

        # start time
        stopwatch.start("company_history")

        # loop through 60 days
        for i in range(1, 90):

            finddate = datetime.now()
            finddate -= timedelta(days = i)

            # get matrix list
            matrix = Matrix.new({
                "start_date"    : finddate.strftime("%Y-%m-%d"),
                "end_date"      : str(datetime.now().strftime("%Y-%m-%d")),
                "processed"     : MATRIX_PROCESSED.NO
            })

            check = matrix.get()

            if not check.idx:
                matrix.create()

if __name__ == '__main__':
    mp = MatrixPreload()
    mp.init()
