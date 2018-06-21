from database import *
from tool import *
from model import *

class PlaylistCustomSVM:

    def get_company_list(self, skip_company_idx):

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
        return company_list


    def init(self):

        playlist = Playlist.new()
        playlist.svm_processed  = PLAYLIST_PROCESS.WAIT
        #playlist.type           = PLAYLIST_TYPE.CUSTOM

        # ----

        playlist_list       = playlist.getList(sort_by = "rank", sort_direction = "asc", select = " idx,company_idx ")
        skip_company_idx    = []

        for playlist in playlist_list:
            skip_company_idx.append(playlist.company_idx)
            playlist.svm_processed = PLAYLIST_PROCESS.PROCESS
            playlist.update_svm_process()

        # ---

        size = 1424

        # ---

        company_list = self.get_company_list(skip_company_idx)

        # ---

        for playlist in playlist_list:

            train_stock_list = CompanyStock.new({
                "company_idx"       : playlist.company_idx,
                "search_start_date" : dformat("%Y-%m-%d", size),
                "search_end_date"   : dformat("%Y-%m-%d")
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
                    "search_start_date" : dformat("%Y-%m-%d", size),
                    "search_end_date"   : dformat("%Y-%m-%d")
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
                    "type"              : MODEL_TYPE.SVM
                }).checkCreate()

            playlist.svm_processed = PLAYLIST_PROCESS.DONE
            playlist.update_svm_process()
