from system.database.DataModel import DataModel
from system.database.BusinessModel import BusinessModel

class COMPANY_NEED_HISTORY:
    NO  = 1
    YES = 2

class COMPANY_MARKET:
    UNKNOWN     = 0
    KOSPI       = 1
    KODAK       = 2
    KONEK       = 3

class Company(DataModel, BusinessModel):

    name                = None
    code                = None
    market              = None
    need_history        = None
    last_updated        = None
    created_date_time   = None

    def create(self):

        query  = "INSERT INTO `company` "
        query +=    "( `name`, `code`, `market`, `need_history`, `last_updated`, `created_date_time`, `status` ) "
        query += "VALUES "
        query +=    "( %s, %s, %s, %s, %s, %s, %s ) "

        return self.postman.create(query, [
            name, code, market, COMPANY_NEED_HISTORY.YES, str(datetime.now().strftime("%Y-%m-%d %H:%I:%S")), str(datetime.now().strftime("%Y-%m-%d %H:%I:%S")), '1'
        ])

    def get(self, select = ' idx,name,code '):

        query  = "SELECT "
        query +=    select
        query += " FROM "
        query +=    "`company` "
        query += "WHERE "
        if idx:     query += "`idx`=%s AND "
        if name:    query += "`name`=%s AND "
        query +=    "`status`=%s "

        params = []
        if idx:     params.append(idx)
        if name:    params.append(name)
        params.append('1')

        return self.extend(self.postman.get(query, params))
