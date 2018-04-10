from system.database.DataModel import DataModel
from system.database.BusinessModel import BusinessModel
from datetime import datetime

class CompanyStock(DataModel, BusinessModel):

    idx                 = None
    company_idx         = None
    price               = None
    prev_diff           = None
    open                = None
    high                = None
    low                 = None
    volume              = None
    date                = None
    created_date_time   = None

    @staticmethod
    def new(data = {}):
        new = CompanyStock()
        new.extend(data)
        return new

    def create(self):

        query  = "INSERT INTO `company_stock` "
        query +=    "( `company_idx`, `price`, `prev_diff`, `open`, `high`, `low`, `volume`, `date`, `created_date_time`, `status` ) "
        query += "VALUES "
        query +=    "( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s ) "

        return self.postman.create(query, [
            self.company_idx, self.price, self.prev_diff, self.open, self.high, self.low, self.volume, self.date, str(datetime.now().strftime("%Y-%m-%d %H:%I:%S")), '1'
        ])

    def get(self, select = ' idx,price '):

        query  = "SELECT "
        query +=    select
        query += " FROM "
        query +=    "`company_stock` "
        query += "WHERE "
        if self.idx:            query += "`idx`=%s AND "
        if self.company_idx:    query += "`company_idx`=%s AND "
        if self.date:           query += "`date`=%s AND "
        query +=    "`status`=%s "

        params = []
        if self.idx:            params.append(self.idx)
        if self.company_idx:    params.append(self.company_idx)
        if self.date:           params.append(self.date)
        params.append('1')

        return CompanyStock.new(self.postman.get(query, params))
