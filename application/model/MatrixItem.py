from system.database.DataModel import DataModel
from system.database.BusinessModel import BusinessModel
from datetime import datetime

class MatrixItem(DataModel, BusinessModel):

    idx                 = None
    matrix_idx          = None
    company_idx         = None
    company_stock_idx   = None
    col                 = None
    max                 = None
    min                 = None
    high                = None
    low                 = None
    created_date_time   = None

    @staticmethod
    def new(data = {}):
        new = MatrixItem()
        new.extend(data)
        return new


    def create(self):

        query  = "INSERT INTO `matrix_item` "
        query +=    "( `matrix_idx`, `company_idx`, `company_stock_idx`, `col`, `max`, `min`, `high`, `low`, `created_date_time`, `status` ) "
        query += "VALUES "
        query +=    "( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s ) "

        return self.postman.create(query, [
            self.matrix_idx, self.company_idx, self.company_stock_idx, self.col, self.max, self.min, self.high, self.low, str(datetime.now().strftime("%Y-%m-%d %H:%I:%S")), '1'
        ])


    def get(self, select = ' idx,matrix_idx '):

        query  = "SELECT "
        query +=    select
        query += " FROM "
        query +=    "`matrix_item` "
        query += "WHERE "
        if self.idx:                query += "`idx`=%s AND "
        if self.matrix_idx:         query += "`matrix_idx`=%s AND "
        if self.company_idx:        query += "`company_idx`=%s AND "
        if self.company_stock_idx:  query += "`company_stock_idx`=%s AND "
        query +=    "`status`=%s "

        params = []
        if self.idx:                params.append(self.idx)
        if self.matrix_idx:         params.append(self.matrix_idx)
        if self.company_idx:        params.append(self.company_idx)
        if self.company_stock_idx:  params.append(self.company_stock_idx)
        params.append('1')

        return MatrixItem.new(self.postman.get(query, params))
