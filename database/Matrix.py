from system.database.DataModel import DataModel
from system.database.BusinessModel import BusinessModel
from datetime import datetime

class MATRIX_PROCESSED:
    NO  = 1
    YES = 2

class Matrix(DataModel, BusinessModel):

    idx                 = None
    start_date          = None
    end_date            = None
    processed           = None
    created_date_time   = None

    @staticmethod
    def new(data = {}):
        new = Matrix()
        new.extend(data)
        return new

    def create(self):

        query  = "INSERT INTO `matrix` "
        query +=    "( `start_date`, `end_date`, `processed`, `created_date_time`, `status` ) "
        query += "VALUES "
        query +=    "( %s, %s, %s, %s, %s ) "

        return self.postman.create(query, [
            self.start_date, self.end_date, self.processed, str(datetime.now().strftime("%Y-%m-%d %H:%I:%S")), '1'
        ])

    def get(self, select = ' idx,start_date,end_date,processed '):

        query  = "SELECT "
        query +=    select
        query += " FROM "
        query +=    "`matrix` "
        query += "WHERE "
        if self.idx:            query += "`idx`=%s AND "
        if self.start_date:     query += "`start_date`=%s AND "
        if self.end_date:       query += "`end_date`=%s AND "
        query +=    "`status`=%s "

        params = []
        if self.idx:            params.append(self.idx)
        if self.start_date:     params.append(self.start_date)
        if self.end_date:       params.append(self.end_date)
        params.append('1')

        return Matrix.new(self.postman.get(query, params))

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
        query +=    "`matrix` "
        query += "WHERE "
        if self.processed:      query += "`processed`=%s AND "
        query +=    "`status`=%s "
        query += "ORDER BY {0} {1} ".format(sort_by, sdirection)
        if not nolimit:         query += "LIMIT %s offset %s "

        params = []
        if self.processed:      params.append(self.processed)
        params.append('1')
        if not nolimit:         params.extend((limit, offset))

        sqllist     = self.postman.getList(query, params)
        return_list = list(map(lambda x: Matrix.new(x), sqllist))

        return return_list

    def updateProcessed(self):

        query  = "UPDATE "
        query +=    "`matrix` "
        query += "SET "
        query +=    "processed=%s "
        query += "WHERE "
        query +=    "`idx`=%s "

        self.postman.execute(query, [
            self.processed, self.idx
        ])
