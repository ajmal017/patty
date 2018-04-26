from urllib.parse import quote_plus, unquote_plus, urlparse, parse_qs
from bs4 import BeautifulSoup
import requests
import sys
from system.Helper import progressbar
from system.analytics.Stopwatch import Stopwatch
from application.model.Company import COMPANY_NEED_HISTORY
from application.model.Company import Company
from application.model.CompanyStock import CompanyStock
from application.model.Matrix import MATRIX_PROCESSED
from application.model.Matrix import Matrix
from application.model.MatrixMatch import MATRIX_MATCH_PROCESSED
from application.model.MatrixMatch import MatrixMatch
from application.model.MatrixItem import MatrixItem

class MatrixCron:

    progress_sofar      = 0
    progress_total      = 0
    create_list         = []
    create_match_list   = []

    def init(self):

        # start stopwatch
        stopwatch = Stopwatch.init()

        # start time
        stopwatch.start("company_history")

        # get matrix list
        matrix = Matrix.new({ "processed" : MATRIX_PROCESSED.NO })
        matrix_list = matrix.getList(sort_by = 'idx', sort_direction = 'asc', limit = 2, offset = 0, select = ' idx,start_date,end_date ')

        # set matrix processed
        for matrix in matrix_list:
            matrix.processed = MATRIX_PROCESSED.YES
            matrix.updateProcessed()

        # loop through matrix and get
        for matrix in matrix_list:

            company_stock = CompanyStock.new()
            company_stock.search_start_date = matrix.start_date
            company_stock.search_end_date   = matrix.end_date

            # loop through company
            for i in range(1, 4000):

                # set search company stock
                company_stock.company_idx = i

                # stock list
                stock_list = company_stock.getList(sort_by = 'date', sort_direction = 'desc', nolimit = True, select = ' idx,company_idx,price,open,high,low ')

                # check if such company exists
                if len(stock_list) > 0:

                    # create matrix match item
                    matrix_match = MatrixMatch.new({
                       'matrix_idx'    : matrix.idx,
                       'company_idx'   : i,
                       'processed'     : MATRIX_MATCH_PROCESSED.NO
                    })

                    # get match from database
                    check = matrix_match.get(' idx ')

                    # check if item exists
                    if not check.idx:

                        # add to match list stack
                        self.create_match_list.append(matrix_match)

                max = 0
                min = 0

                # price,open,high,low
                for id,stock in enumerate(stock_list):

                    # type 1
                    # use that day with highest value and lowest
                    # max     = stock.high    if max < stock.high else max
                    # min     = stock.low     if min > stock.low  else min

                    # type 2
                    # use stock price & open as guides

                    # find largest
                    high    = stock.open   if stock.price < stock.open else stock.price
                    max     = high         if max < high               else max

                    # find lowest
                    low     = stock.open   if stock.price > stock.open else stock.price
                    min     = low          if min > low or min == 0    else min

                # price,open,high,low
                for id,stock in enumerate(stock_list):

                    high    = stock.open   if stock.price < stock.open else stock.price
                    low     = stock.open   if stock.price > stock.open else stock.price

                    matrix_item                     = MatrixItem()
                    matrix_item.matrix_idx          = matrix.idx                # matrix idx
                    matrix_item.company_idx         = stock.company_idx         # company idx
                    matrix_item.company_stock_idx   = stock.idx                 # company stock idx 기준으로
                    matrix_item.col                 = id
                    matrix_item.max                 = max
                    matrix_item.min                 = min
                    matrix_item.high                = high
                    matrix_item.low                 = low

                    check = matrix_item.get()

                    if not check.idx:
                        self.addcreate(matrix_item)

        self.loop_create()


    def addcreate(self, matrix_item):

        self.create_list.append(matrix_item)

        if len(self.create_list) > 1000:
            self.loop_create()


    def loop_create(self):

        # matrix stock item create
        for matrix_item in self.create_list:
            matrix_item.create()

        # create matrix match
        for match in self.create_match_list:
            match.create()

        # create list
        self.create_match_list.clear()
        self.create_list.clear()

cs = MatrixCron()
cs.init()
