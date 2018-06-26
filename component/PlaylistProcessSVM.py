from database import *
from tool import *
from model import *
from multiprocessing import Pool

class PlaylistProcessSVM:

    company_list = []

    def get_company_list(self, skip_company_idx = []):

        # main company list
        company_list = []

        # company list object
        c = Company.new({"need_history" : COMPANY_NEED_HISTORY.NO})

        # check if skip idx is long enough
        if  len(skip_company_idx) <= 0:
            skip_company_idx = False

        # loop through 10 times limit 250
        for i in range(20):

            # temp list with companies
            t = c.getList( limit = 250, offset = (i*250), select = " idx " )

            # check if there are results
            if len(t) >= 1:

                # append main list with temp list
                company_list = company_list + t

        # return main company list
        self.company_list = company_list


    def initMultiCore(self):
        playlist_list = self.preprocess();
        with Pool as p:
            list(p.map(self.mainprocess, sleep_time_list))

    def initSingleCore(self):
        playlist_list = self.preprocess();
        list(map(self.mainprocess, playlist_list))

    def preprocess(self):

        self.get_company_list()

        playlist_list = Playlist.new({
            "svm_processed":PLAYLIST_PROCESS.WAIT
        }).getList(sort_by = "date", sort_direction = "desc", limit = 3, select = " idx,company_idx,date ")

        for playlist in playlist_list:
            playlist.svm_processed = PLAYLIST_PROCESS.PROCESS
            playlist.update_svm_process()

        return playlist_list

    def mainprocess(self, playlist):

        # set update date time for svm weight
        playlist.svm_processed_wait = getDateTime()
        playlist.update_svm_processed_wait()

        train_stock_list = CompanyStock.new({
            "company_idx"       : playlist.company_idx,
            "search_start_date" : dsformat(str(playlist.date), size),
            "search_end_date"   : dsformat(str(playlist.date))
        }).getList(sort_by = 'date', sort_direction = 'desc', nolimit = True)

        svm_model = SVMWrapper()
        svm_model.train_data_x = CompanyStock.getOCHLV(train_stock_list, size)
        svm_model.train_data_y = CompanyStock.getP(train_stock_list, size)
        svm_model.train()

        for company in company_list:

            if company.idx == playlist.company_idx:
                continue

            predict_stock_list = CompanyStock.new({
                "company_idx"       : company.idx,
                "search_start_date" : dsformat(str(playlist.date), size),
                "search_end_date"   : dsformat(str(playlist.date))
            }).getList(sort_by = 'date', sort_direction = 'desc', nolimit = True)

            svm_model.test_data_x = CompanyStock.getOCHLV(predict_stock_list, size)
            svm_model.test_data_y = CompanyStock.getP(predict_stock_list, size)
            score = svm_model.test()

            ModelResult.new({
                "playlist_idx"      : playlist.idx,
                "train_company_idx" : playlist.company_idx,
                "test_company_idx"  : company.idx,
                "f1"                : "0",
                "accuracy"          : "0",
                "recall"            : "0",
                "precise"           : "0",
                "score"             : float(score),
                "type"              : MODEL_TYPE.SVM,
                "duration"          : size
            }).checkCreate()

        playlist.svm_processed = PLAYLIST_PROCESS.DONE
        playlist.update_svm_process()

        # set update date time for svm weight
        playlist.svm_processed_complete = getDateTime()
        playlist.update_svm_processed_complete()
