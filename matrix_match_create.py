from urllib.parse import quote_plus, unquote_plus, urlparse, parse_qs
from bs4 import BeautifulSoup
import numpy as np
import requests
import sys
from system.Helper import progressbar
from system.analytics.Stopwatch import Stopwatch
from application.model.Company import COMPANY_NEED_HISTORY
from application.model.Company import Company
from application.model.CompanyStock import CompanyStock
from application.model.Matrix import MATRIX_PROCESSED
from application.model.Matrix import Matrix
from application.model.MatrixMatch import MatrixMatch
from application.model.MatrixMatch import MATRIX_MATCH_PROCESSED
from application.model.MatrixItem import MatrixItem
from application.model.MatrixMatchItem import MatrixMatchItem
from datetime import date
from datetime import datetime

class MatrixMatchCreate:

    progress_sofar      = 0
    progress_total      = 0
    create_list         = []

    def init(self):

        # start stopwatch
        stopwatch = Stopwatch.init()

        # start time
        stopwatch.start("MatrixMatch")

        # get match list
        match_list = MatrixMatch.new({ "processed" : MATRIX_MATCH_PROCESSED.NO }).getList(sort_by = 'idx', sort_direction = 'asc', limit = 3, select = ' idx,matrix_idx,company_idx ')

        # set total progress
        self.progress_total = (len(match_list) * 4000)

        # change their status to processed
        for match in match_list:
            match.processed = MATRIX_MATCH_PROCESSED.YES
            match.updateProcessed()

        # loop through match list
        for match in match_list:

            check_matrix_time = Matrix.new({ "idx" : match.matrix_idx }).get( " start_date,end_date " )

            start_date  = datetime.strptime(str(check_matrix_time.start_date), "%Y-%m-%d")
            end_date    = datetime.strptime(str(check_matrix_time.end_date), "%Y-%m-%d")
            days_count  = abs((end_date - start_date).days)

            # current company
            current_matrix = MatrixItem.new({
                "matrix_idx"    : match.matrix_idx,
                "company_idx"   : match.company_idx
            })
            main_matrix_item_list = current_matrix.getList(sort_by = 'col', sort_direction = 'asc', nolimit = True, select = ' idx,max,min,high,low ')
            matrix_main = MatrixItem.converMatrix(main_matrix_item_list, days_count)

            for idx in range(1, 4000):

                if idx == match.company_idx:
                    continue

                # other company
                sub_matrix_item_list = MatrixItem.new({
                    "matrix_idx"    : match.matrix_idx,
                    "company_idx"   : idx
                }).getList(sort_by = 'col', sort_direction = 'asc', nolimit = True, select = ' idx,max,min,high,low ')

                # only if there is enough data, match them
                # or else lets just through it away
                if len(sub_matrix_item_list) == len(main_matrix_item_list):

                    matrix_sub = MatrixItem.converMatrix(sub_matrix_item_list, days_count)

                    abs_matrix = np.absolute(matrix_main - matrix_sub)

                    sum_val = np.sum(abs_matrix)

                    matrix_match_item = MatrixMatchItem.new({
                        "matrix_match_idx"      : match.idx,
                        "company_idx"           : idx,
                        "point"                 : str(sum_val)
                    })
                    self.addcreate(matrix_match_item)

                    # increase progress amount
                    self.progress_sofar = self.progress_sofar + 1

                    # show progress bar
                    #progressbar(self.progress_sofar, self.progress_total, "{0} / {1} - {2} ".format(self.progress_sofar, self.progress_total, Stopwatch.init().check("MatrixMatch")))

        self.loop_createlist()

    def addcreate(self, item):

        self.create_list.append(item)

        # loop through list
        if len(self.create_list) > 1000:

            # create item
            self.loop_createlist()

    def loop_createlist(self):

        # show progress bar
        #progressbar(self.progress_sofar, self.progress_total, "{0} / {1} - {2} looping through create ".format(self.progress_sofar, self.progress_total, Stopwatch.init().check("MatrixMatch")))

        # matrix stock item create
        for matrix_item in self.create_list:
            matrix_item.create()

        # remove all item
        self.create_list.clear()


cs = MatrixMatchCreate()
cs.init()
