from system.database.DataModel import DataModel
from system.database.BusinessModel import BusinessModel
from datetime import datetime

class COMPANY_NEED_HISTORY:
    NO  = 1
    YES = 2


class COMPANY_MARKET:
    UNKNOWN     = 0
    KOSPI       = 1
    KODAK       = 2
    KONEK       = 3


class Company(DataModel, BusinessModel):

    idx                 = None
    name                = None
    code                = None
    market              = None
    need_history        = None
    last_updated        = None
    created_date_time   = None

    @staticmethod
    def new(data = {}):
        new = Company()
        new.extend(data)
        return new

    def dailyStockUpdate(self):

        query  = "UPDATE "
        query +=    "`company` "
        query += "SET "
        query +=    "last_updated=%s "
        query += "WHERE "
        query +=    "`status`=%s "

        self.postman.execute(query, [
            str(datetime.now().strftime("%Y-%m-%d")), '1'
        ])

    def create(self):

        query  = "INSERT INTO `company` "
        query +=    "( `name`, `code`, `market`, `need_history`, `last_updated`, `created_date_time`, `status` ) "
        query += "VALUES "
        query +=    "( %s, %s, %s, %s, %s, %s, %s ) "

        return self.postman.create(query, [
            self.name, self.code, self.market, COMPANY_NEED_HISTORY.YES, str(datetime.now().strftime("%Y-%m-%d")), str(datetime.now().strftime("%Y-%m-%d %H:%I:%S")), '1'
        ])

    def get(self, select = ' idx,name,code '):

        query  = "SELECT "
        query +=    select
        query += " FROM "
        query +=    "`company` "
        query += "WHERE "
        if self.idx:     query += "`idx`=%s AND "
        if self.name:    query += "`name`=%s AND "
        query +=    "`status`=%s "

        params = []
        if self.idx:     params.append(self.idx)
        if self.name:    params.append(self.name)
        params.append('1')

        return Company.new(self.postman.get(query, params))

    def getList(self, **kwargs):

        sort_by     = kwargs['sort_by']         if 'sort_by'        in kwargs else 'idx'
        sdirection  = kwargs['sort_direction']  if 'sort_direction' in kwargs else 'desc'
        limit       = kwargs['limit']           if 'limit'          in kwargs else 20
        nolimit     = kwargs['nolimit']         if 'nolimit'        in kwargs else False
        offset      = kwargs['offset']          if 'offset'         in kwargs else 0
        select      = kwargs['select']          if 'select'         in kwargs else ' idx,name,code,market,need_history '

        skip_idx    = kwargs['skip_idx']        if 'skip_idx'       in kwargs else False

        query  = "SELECT "
        query +=    select
        query += " FROM "
        query +=    "`company` "
        query += "WHERE "
        if self.need_history:   query += "`need_history`=%s AND "
        if self.market:         query += "`market`=%s AND "
        if self.last_updated:   query += "`last_updated`<%s AND "
        if skip_idx:            query += "`idx` NOT IN (%s) AND "
        query +=    "`status`=%s "
        query += "ORDER BY {0} {1} ".format(sort_by, sdirection)
        if not nolimit:         query += "LIMIT %s offset %s "

        params = []
        if self.need_history:   params.append(self.need_history)
        if self.market:         params.append(self.market)
        if self.last_updated:   params.append(self.last_updated)
        if skip_idx:            params.append(','.join(map(str, skip_idx)) )
        params.append('1')
        if not nolimit:         params.extend((limit, offset))

        sqllist     = self.postman.getList(query, params)
        return_list = list(map(lambda x: Company.new(x), sqllist))

        return return_list

    def updateNeedHistory(self):
        self.postman.execute(
            " UPDATE `company` SET `need_history`=%s WHERE `idx`=%s ",
            [self.need_history, self.idx]
        )
