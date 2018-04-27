from system.database.DataModel import DataModel
from system.database.BusinessModel import BusinessModel
from datetime import datetime
import numpy as np

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

    @staticmethod
    def converMatrix(match_list, days_count):

        matrix_col = []

        for item in match_list:

            max     = item.max
            min     = item.min
            high    = item.high
            low     = item.low

            # the number of steps,
            # increase this for more accurency, i think??????
            scale   = 1000

            # create steps in matrix
            step   = max / scale;

            # list that will hold the values
            col = [];

            # loop variables
            cnt_i = 0

            # loop through range to generate matrix
            while(True):

                if cnt_i >= max:
                    break
                else:
                    cnt_i = cnt_i + step

                lvl = (cnt_i * step);

                if lvl >= low and lvl <= high:
                    col.append(1)
                else:
                    col.append(0)

            matrix_col.append(col)

        if len(matrix_col) < days_count:
            for i in range(days_count):
                matrix_col.append([ 0 for i in range(1000) ])

        ######################

        if len(matrix_col) <= 0:
            return np.array([])

        new_matrix = []

        for rowIdn in range(0, 1000):

            row = []

            for colIdn in range(len(matrix_col)):

                row.append(matrix_col[colIdn][rowIdn])

            new_matrix.append(row)

        return np.array(new_matrix)

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

    def getList(self, **kwargs):

        sort_by     = kwargs['sort_by']         if 'sort_by'        in kwargs else 'idx'
        sdirection  = kwargs['sort_direction']  if 'sort_direction' in kwargs else 'desc'
        limit       = kwargs['limit']           if 'limit'          in kwargs else 20
        nolimit     = kwargs['nolimit']         if 'nolimit'        in kwargs else False
        offset      = kwargs['offset']          if 'offset'         in kwargs else 0
        select      = kwargs['select']          if 'select'         in kwargs else ' idx,matrix_idx,company_idx,company_stock_idx,col,max,min,high,low '

        query  = "SELECT "
        query +=    select
        query += " FROM "
        query +=    "`matrix_item` "
        query += "WHERE "
        if self.matrix_idx:     query += "`matrix_idx`=%s AND "
        if self.company_idx:    query += "`company_idx`=%s AND "
        query +=    "`status`=%s "
        query += "ORDER BY {0} {1} ".format(sort_by, sdirection)
        if not nolimit:         query += "LIMIT %s offset %s "

        params = []
        if self.matrix_idx:     params.append(self.matrix_idx)
        if self.company_idx:    params.append(self.company_idx)
        params.append('1')
        if not nolimit:         params.extend((limit, offset))

        sqllist     = self.postman.getList(query, params)
        return_list = list(map(lambda x: MatrixItem.new(x), sqllist))

        return return_list
