from database import *
from tool import *
from model import *
import json

class PlaylistToFile:

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

        # ----

        playlist_list       = playlist.getList(sort_by = "date", sort_direction = "desc", limit = 2, select = " idx,company_idx,date ")
        skip_company_idx    = []

        for playlist in playlist_list:
            skip_company_idx.append(playlist.company_idx)
            playlist.svm_processed = PLAYLIST_PROCESS.PROCESS
            playlist.update_svm_process()

        # ---

        size = 100

        # ---

        company_list = self.get_company_list(skip_company_idx)

        # ---

        for playlist in playlist_list:

            json_to_save = {
                "playlist"  : {
                    "idx"           : playlist.idx,
                    "company_idx"   : playlist.company_idx,
                    "date"          : str(playlist.date)
                },
                "size"      : size,
                "home"      : {},
                "away_list" : []
            }

            # set update date time for svm weight
            playlist.svm_processed_wait = getDateTime()
            playlist.update_svm_processed_wait()

            stock_list = CompanyStock.new({
                "company_idx"       : playlist.company_idx,
                "search_start_date" : dsformat(str(playlist.date), size),
                "search_end_date"   : dsformat(str(playlist.date))
            }).getList(sort_by = 'date', sort_direction = 'desc', nolimit = True, noclass = True)
            json_to_save["home"]["stock_list"] = list(map(lambda x : { "price" : x["price"], "prev_diff" : x["prev_diff"], "percentage" : x["percentage"], "open" : x["open"], "high" : x["high"], "low" : x["low"] ,"volume" : x["volume"] }, stock_list))

            for company in company_list:

                if company.idx == playlist.company_idx:
                    continue

                stock_list = CompanyStock.new({
                    "company_idx"       : company.idx,
                    "search_start_date" : dsformat(str(playlist.date), size),
                    "search_end_date"   : dsformat(str(playlist.date))
                }).getList(sort_by = 'date', sort_direction = 'desc', nolimit = True, noclass = True)

                json_to_save["away_list"].append({
                    "company_idx"   : company.idx,
                    "stock_list"    : list(map(lambda x : { "price" : x["price"], "prev_diff" : x["prev_diff"], "percentage" : x["percentage"], "open" : x["open"], "high" : x["high"], "low" : x["low"] ,"volume" : x["volume"] }, stock_list))
                })

            filename = "need_to_solve_playlist_" + str(playlist.idx) + ".json"
            j = json.dumps(json_to_save)
            f = open("./json/" + filename, "w")
            f.write(j)
            f.close()

            playlist.svm_processed = PLAYLIST_PROCESS.DONE
            playlist.update_svm_process()

            # set update date time for svm weight
            playlist.svm_processed_complete = getDateTime()
            playlist.update_svm_processed_complete()
