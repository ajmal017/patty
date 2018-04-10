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
