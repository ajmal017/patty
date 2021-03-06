from database import *
from tool import *
from datetime import date, timedelta

class PlaylistFindTopVolume:

    def init(self):

        # get yesterday's date
        yesterday = date.today() - timedelta(1)

        # convert date object to string date
        yesterday = str(yesterday.strftime("%Y-%m-%d"))

        # run top list
        self.runtext(yesterday)


    def runtext(self, yesterday):

        # search limit
        limit = 30

        # get the top 100
        stock_list = CompanyStock.new({ "date" : yesterday }).getList(sort_by = 'percentage', sort_direction = 'desc', limit = limit)

        # loop through top list
        for i,stock in enumerate(stock_list):

            # create playlist item
            playlist = Playlist.new({
                "type"              : PLAYLIST_TYPE.TOP,
                "rank"              : i,
                "company_idx"       : stock.company_idx,
                "company_stock_idx" : stock.idx,
                "date"              : yesterday
            })

            # check if playlist item is in database
            # just fo safety of duplicate
            check = playlist.get()

            # if not in database add it!
            if check.idx == None:
                playlist.create()

    def mimic_date(self):

        for i in range(140):

            # get yesterday's date
            yesterday = date.today() - timedelta(i)

            # convert date object to string date
            yesterday = str(yesterday.strftime("%Y-%m-%d"))

            # find the top list!
            self.runtext(yesterday)
