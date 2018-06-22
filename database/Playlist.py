from system.database.DataModel import DataModel
from system.database.BusinessModel import BusinessModel
from datetime import datetime

class PLAYLIST_TYPE:
    TOP     = 1
    CUSTOM  = 99

class PLAYLIST_PROCESS:
    WAIT    = 1
    PROCESS = 2
    DONE    = 3

class Playlist(DataModel, BusinessModel):

    idx                     = None
    group_idx               = None
    type                    = None
    rank                    = None
    company_idx             = None
    company_stock_idx       = None
    date                    = None
    svm_processed           = None
    svm_processed_wait      = None
    svm_processed_complete  = None
    hmm_processed           = None
    created_date_time       = None

    @staticmethod
    def new(data = {}):
        new = Playlist()
        new.extend(data)
        return new

    def create(self):

        # default values
        self.group_idx              = 0
        self.svm_processed          = PLAYLIST_PROCESS.WAIT
        self.hmm_processed          = PLAYLIST_PROCESS.WAIT
        self.svm_processed_wait     = "0000-00-00 00:00:00"
        self.svm_processed_complete = "0000-00-00 00:00:00"

        query  = "INSERT INTO `playlist` "
        query +=    "( `group_idx`, `type`, `rank`, `company_idx`, `company_stock_idx`, `date`, `svm_processed`, `svm_processed_wait`, `svm_processed_complete`, `hmm_processed`, `created_date_time`, `status` ) "
        query += "VALUES "
        query +=    "( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s ) "

        return self.postman.create(query, [
            self.group_idx, self.type, self.rank, self.company_idx, self.company_stock_idx, self.date, self.svm_processed, self.svm_processed_wait, self.svm_processed_complete,  self.hmm_processed, str(datetime.now().strftime("%Y-%m-%d %H:%I:%S")), '1'
        ])

    def get(self, select = ' idx,group_idx,type,rank,company_idx,date '):

        query  = "SELECT "
        query +=    select
        query += " FROM "
        query +=    "`playlist` "
        query += "WHERE "
        if self.idx:                query += "`idx`=%s AND "
        if self.type:               query += "`type`=%s AND "
        if self.company_idx:        query += "`company_idx`=%s AND "
        if self.company_stock_idx:  query += "`company_stock_idx`=%s AND "
        if self.date:               query += "`date`=%s AND "
        query +=    "`status`=%s "

        params = []
        if self.idx:                params.append(self.idx)
        if self.type:               params.append(self.type)
        if self.company_idx:        params.append(self.company_idx)
        if self.company_stock_idx:  params.append(self.company_stock_idx)
        if self.date:               params.append(self.date)
        params.append('1')

        return Playlist.new(self.postman.get(query, params))

    def getList(self, **kwargs):

        sort_by     = kwargs['sort_by']         if 'sort_by'        in kwargs else 'idx'
        sdirection  = kwargs['sort_direction']  if 'sort_direction' in kwargs else 'desc'
        limit       = kwargs['limit']           if 'limit'          in kwargs else 20
        nolimit     = kwargs['nolimit']         if 'nolimit'        in kwargs else False
        offset      = kwargs['offset']          if 'offset'         in kwargs else 0
        select      = kwargs['select']          if 'select'         in kwargs else ' idx,start_date,end_date,processed '

        query  = "SELECT "
        query +=    select
        query += " FROM "
        query +=    "`playlist` "
        query += "WHERE "
        if self.type:           query += "`type`=%s AND "
        if self.svm_processed:  query += "`svm_processed`=%s AND "
        query +=    "`status`=%s "
        query += "ORDER BY {0} {1} ".format(sort_by, sdirection)
        if not nolimit:         query += "LIMIT %s offset %s "

        params = []
        if self.type:           params.append(self.type)
        if self.svm_processed:  params.append(self.svm_processed)
        params.append('1')
        if not nolimit:         params.extend((limit, offset))
        sqllist     = self.postman.getList(query, params)
        return_list = list(map(lambda x: Playlist.new(x), sqllist))

        return return_list

    def update_svm_process(self):
        self.postman.execute(
            " UPDATE `playlist` SET `svm_processed`=%s WHERE `idx`=%s ",
            [self.svm_processed, self.idx]
        )

    def update_svm_processed_wait(self):
        self.postman.execute(
            " UPDATE `playlist` SET `svm_processed_wait`=%s WHERE `idx`=%s ",
            [self.svm_processed_wait, self.idx]
        )

    def update_svm_processed_complete(self):
        self.postman.execute(
            " UPDATE `playlist` SET `svm_processed_complete`=%s WHERE `idx`=%s ",
            [self.svm_processed_complete, self.idx]
        )
