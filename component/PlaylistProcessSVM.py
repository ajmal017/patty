from system.database.Postman import Postman
from database import *
from tool import *
from model import *
from multiprocessing import Pool
from datetime import datetime

class PlaylistProcessSVM:

    company_list = []

    def get_company_list(self, skip_company_idx = []):

        # main company list
        company_list = []

        # company list object
        c = Company.new({"need_history" : COMPANY_NEED_HISTORY.NO, "exclude_learn" : COMPANY_EXCLUDE_LEARN.NO})

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
            else:
                break

        # return main company list
        self.company_list = company_list


    def initMultiCore(self):

        mth = ModelTrainingHistory.new({"startt":str(datetime.now().strftime("%Y-%m-%d %H:%I:%S")), "endt" : "0000-00-00 00:00:00"})
        mth.idx = mth.create()

        # get playlist
        playlist_list = []

        for i in range(200):
            additional_list = self.preprocess(100);
            if len(additional_list) > 0:
                playlist_list = playlist_list + additional_list
            else:
                break

        # release db connection
        # Postman.init().close()

        with Pool(29) as p:
            list(p.map(self.mainprocess_precall, playlist_list))

        mth.endt = str(datetime.now().strftime("%Y-%m-%d %H:%I:%S"))
        mth.update_endt()

    def initSingleCore(self):

        mth = ModelTrainingHistory.new({"startt":str(datetime.now().strftime("%Y-%m-%d %H:%I:%S")), "endt" : "0000-00-00 00:00:00"})
        mth.idx = mth.create()

        playlist_list = self.preprocess();
        list(map(self.mainprocess, playlist_list))

        mth.endt = str(datetime.now().strftime("%Y-%m-%d %H:%I:%S"))
        mth.update_endt()

    def preprocess(self, limit = 3):

        self.get_company_list()

        playlist_list = Playlist.new({
            "svm_processed":PLAYLIST_PROCESS.WAIT
        }).getList(sort_by = "date", sort_direction = "desc", limit = limit, select = " idx,company_idx,date ")

        for playlist in playlist_list:
            playlist.svm_processed = PLAYLIST_PROCESS.PROCESS
            playlist.update_svm_process()

        return playlist_list

    def mainprocess_precall(self, playlist):
        self.mainprocess(playlist, True)

    def mainprocess(self, playlist, multicore = False):

        # number of days to use
        duration = 300

        # minimum number of days to use
        minimum_duration = 120

        # new postman instance variable
        instance_postman = None

        # check if multicore is enabled
        if multicore:

            # create new object
            instance_postman = Postman()

            # create new connection
            instance_postman.connect()

        # ------------------------------------------

        # set update date time for svm weight
        Playlist.new({
            "idx"                   : playlist.idx,
            "svm_processed_wait"    : getDateTime()
        }).multicore(instance_postman, multicore).update_svm_processed_wait()

        train_stock_list = CompanyStock.new({
            "company_idx"       : playlist.company_idx,
            "search_start_date" : dsformat(str(playlist.date), duration),
            "search_end_date"   : dsformat(str(playlist.date))
        }).multicore(instance_postman, multicore).getList(sort_by = 'date', sort_direction = 'desc', nolimit = True)

        # below minimum, skip
        below_minimun_skip = False

        # check if the number of days equals the minimum amount
        if len(train_stock_list) < minimum_duration:
            below_minimun_skip = True

        if  not below_minimun_skip:

            svm_model = SVMWrapper()
            svm_model.train_data_x = CompanyStock.getOCHLV(train_stock_list, duration)
            svm_model.train_data_y = CompanyStock.getP(train_stock_list, duration)
            svm_model.train()

            for company in self.company_list:

                if company.idx == playlist.company_idx:
                    continue

                predict_stock_list = CompanyStock.new({
                    "company_idx"       : company.idx,
                    "search_start_date" : dsformat(str(playlist.date), duration),
                    "search_end_date"   : dsformat(str(playlist.date))
                }).multicore(instance_postman, multicore).getList(sort_by = 'date', sort_direction = 'desc', nolimit = True)

                # check if the number of days equals the minimum amount
                if len(predict_stock_list) < minimum_duration:
                    continue

                svm_model.test_data_x = CompanyStock.getOCHLV(predict_stock_list, duration)
                svm_model.test_data_y = CompanyStock.getP(predict_stock_list, duration)
                score, accuracy = svm_model.test()

                ModelResult.new({
                    "playlist_idx"      : playlist.idx,
                    "train_company_idx" : playlist.company_idx,
                    "test_company_idx"  : company.idx,
                    "f1"                : "0",
                    "accuracy"          : float(accuracy),
                    "recall"            : "0",
                    "precise"           : "0",
                    "score"             : float(score),
                    "type"              : MODEL_TYPE.SVM,
                    "duration"          : duration
                }).multicore(instance_postman, multicore).create()

        Playlist.new({
            "svm_processed" : PLAYLIST_PROCESS.DONE,
            "idx"           : playlist.idx
        }).multicore(instance_postman, multicore).update_svm_process()

        # set update date time for svm weight
        Playlist.new({
            "svm_processed_complete"    : getDateTime(),
            "idx"                       : playlist.idx
        }).multicore(instance_postman, multicore).update_svm_processed_complete()
