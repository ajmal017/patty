from system.database.DataModel import DataModel
from system.database.BusinessModel import BusinessModel
from datetime import datetime

class WATCH_TYPE:
    DAILY     = 1
    ONCE      = 2

class Watch(DataModel, BusinessModel):

    idx                 = None
    group_idx           = None
    company_idx         = None
    company_stock_idx   = None
    type                = None
    processed_date      = None
    created_date_time   = None
    status              = None

    processed_date_not  = None

    @staticmethod
    def new(data = {}):
        new = Watch()
        new.extend(data)
        return new


    def getList(self, **kwargs):

        sort_by     = kwargs['sort_by']         if 'sort_by'        in kwargs else 'idx'
        sdirection  = kwargs['sort_direction']  if 'sort_direction' in kwargs else 'desc'
        limit       = kwargs['limit']           if 'limit'          in kwargs else 20
        nolimit     = kwargs['nolimit']         if 'nolimit'        in kwargs else False
        noclass     = kwargs['noclass']         if 'noclass'        in kwargs else False
        offset      = kwargs['offset']          if 'offset'         in kwargs else 0
        select      = kwargs['select']          if 'select'         in kwargs else ' idx,group_idx,company_idx,company_stock_idx,type,processed_date '

        query  = "SELECT "
        query +=    select
        query += " FROM "
        query +=    "`watch` "
        query += "WHERE "
        if self.type:               query += "`type`=%s AND "
        if self.processed_date_not: query += "`processed_date`!=%s AND "
        query +=    "`status`=%s "
        query += "ORDER BY {0} {1} ".format(sort_by, sdirection)
        if not nolimit:             query += "LIMIT %s offset %s "

        params = []
        if self.type:               params.append(self.type)
        if self.processed_date_not: params.append(self.processed_date_not)
        params.append('1')
        if not nolimit:             params.extend((limit, offset))

        sqllist     = self.postman.getList(query, params)
        if not noclass:
            return list(map(lambda x: Watch.new(x), sqllist))
        return sqllist

    def updateProcessedDate(self):

        query  = "UPDATE "
        query +=    "`watch` "
        query += "SET "
        query +=    "`processed_date`=%s "
        query += "WHERE "
        query +=    "`idx`=%s "

        params = []
        params.append(self.processed_date)
        params.append(self.idx)

        self.postman.execute(query, params)
