from database import *
from tool import *
from model import *

class WatchToPlaylist:

    def process_daily(self):
        return Watch.new({"type":WATCH_TYPE.DAILY,"processed_date_not":dformat()}).getList(nolimit = True)

    def process_once(self):
        return Watch.new({"type":WATCH_TYPE.ONCE,"processed_date":"0000-00-00"}).getList(nolimit = True)

    def create_playlist(self, watch):

        watch.processed_date = dformat()
        watch.updateProcessedDate()

        Playlist.new({
            "group_idx"         : watch.group_idx,
            "rank"              : "0",
            "type"              : PLAYLIST_TYPE.CUSTOM,
            "company_idx"       : watch.company_idx,
            "company_stock_idx" : watch.company_stock_idx,
            "date"              : dformat()
        }).create()

    def init(self):
        list(map(self.create_playlist, self.process_daily()))
        list(map(self.create_playlist, self.process_once()))
