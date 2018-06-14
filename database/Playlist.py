from system.database.DataModel import DataModel
from system.database.BusinessModel import BusinessModel
from datetime import datetime

class PLAYLIST_TYPE:
    TOP  = 1

class PLAYLIST_PROCESS:
    WAIT    = 0
    PROCESS = 1
    DONE    = 2

class Playlist(DataModel, BusinessModel):

    idx                 = None
    type                = None
    rank                = None
    company_idx         = None
    company_stock_idx   = None
    date                = None
    svm_processed       = None
    hmm_processed       = None
    created_date_time   = None

    @staticmethod
    def new(data = {}):
        new = Playlist()
        new.extend(data)
        return new

    def create(self):

        # default values
        self.svm_processed = "0"
        self.hmm_processed = "0"

        query  = "INSERT INTO `playlist` "
        query +=    "( `type`, `rank`, `company_idx`, `company_stock_idx`, `date`, `svm_processed`, `hmm_processed`, `created_date_time`, `status` ) "
        query += "VALUES "
        query +=    "( %s, %s, %s, %s, %s, %s, %s, %s, %s ) "

        return self.postman.create(query, [
            self.type, self.rank, self.company_idx, self.company_stock_idx, self.date, self.svm_processed, self.hmm_processed, str(datetime.now().strftime("%Y-%m-%d %H:%I:%S")), '1'
        ])

    def get(self, select = ' idx,type,rank,company_idx,date '):

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
        if self.svm_processed:  query += "`svm_processed`=%s AND "
        query +=    "`status`=%s "
        query += "ORDER BY {0} {1} ".format(sort_by, sdirection)
        if not nolimit:         query += "LIMIT %s offset %s "

        params = []
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
