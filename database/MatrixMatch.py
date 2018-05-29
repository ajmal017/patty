from system.database.DataModel import DataModel
from system.database.BusinessModel import BusinessModel
from datetime import datetime

class MATRIX_MATCH_PROCESSED:
    NO  = 1
    YES = 2

class MatrixMatch(DataModel, BusinessModel):

    idx                 = None
    matrix_idx          = None
    company_idx         = None
    processed           = None
    created_date_time   = None

    @staticmethod
    def new(data = {}):
        new = MatrixMatch()
        new.extend(data)
        return new

    def create(self):

        query  = "INSERT INTO `matrix_match` "
        query +=    "( `matrix_idx`, `company_idx`, `processed`, `created_date_time`, `status` ) "
        query += "VALUES "
        query +=    "( %s, %s, %s, %s, %s ) "

        return self.postman.create(query, [
            self.matrix_idx, self.company_idx, self.processed, str(datetime.now().strftime("%Y-%m-%d %H:%I:%S")), '1'
        ])

    def updateProcessed(self):

        self.postman.execute(
            " UPDATE `matrix_match` SET `processed`=%s WHERE `idx`=%s ",
            [self.processed, self.idx]
        )

    def get(self, select = ' idx,matrix_idx,company_idx,processed '):

        query  = "SELECT "
        query +=    select
        query += " FROM "
        query +=    "`matrix_match` "
        query += "WHERE "
        if self.idx:                query += "`idx`=%s AND "
        if self.matrix_idx:         query += "`matrix_idx`=%s AND "
        if self.company_idx:        query += "`company_idx`=%s AND "
        query +=    "`status`=%s "

        params = []
        if self.idx:                params.append(self.idx)
        if self.matrix_idx:         params.append(self.matrix_idx)
        if self.company_idx:        params.append(self.company_idx)
        params.append('1')

        return MatrixMatch.new(self.postman.get(query, params))

    def getList(self, **kwargs):

        sort_by     = kwargs['sort_by']         if 'sort_by'        in kwargs else 'idx'
        sdirection  = kwargs['sort_direction']  if 'sort_direction' in kwargs else 'desc'
        limit       = kwargs['limit']           if 'limit'          in kwargs else 20
        nolimit     = kwargs['nolimit']         if 'nolimit'        in kwargs else False
        offset      = kwargs['offset']          if 'offset'         in kwargs else 0
        select      = kwargs['select']          if 'select'         in kwargs else ' idx,matrix_idx,company_idx,processed '

        query  = "SELECT "
        query +=    select
        query += " FROM "
        query +=    "`matrix_match` "
        query += "WHERE "
        if self.processed:   query += "`processed`=%s AND "
        query +=    "`status`=%s "
        query += "ORDER BY {0} {1} ".format(sort_by, sdirection)
        if not nolimit:         query += "LIMIT %s offset %s "

        params = []
        if self.processed:      params.append(self.processed)
        params.append('1')
        if not nolimit:         params.extend((limit, offset))

        sqllist     = self.postman.getList(query, params)
        return_list = list(map(lambda x: MatrixMatch.new(x), sqllist))

        return return_list
