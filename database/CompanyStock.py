from system.database.DataModel import DataModel
from system.database.BusinessModel import BusinessModel
from datetime import datetime

class CompanyStock(DataModel, BusinessModel):

    idx                 = None
    company_idx         = None
    price               = None
    prev_diff           = None
    percentage          = None
    open                = None
    high                = None
    low                 = None
    volume              = None
    date                = None
    created_date_time   = None

    search_start_date   = None
    search_end_date     = None

    @staticmethod
    def new(data = {}):
        new = CompanyStock()
        new.extend(data)
        return new

    @staticmethod
    def getOCHLV(stock_list, match_size):
        a = len(stock_list)
        frontend = []
        if match_size > a:
            for i in range(match_size - a):
                frontend.append([0,0,0,0,0])
        return frontend + list(map(lambda stock:[
            int(stock.open),
            int(stock.high),
            int(stock.low),
            int(stock.price), # close
            int(stock.volume)
        ], stock_list))

    @staticmethod
    def getP(stock_list, match_size):
        a = len(stock_list)
        frontend = []
        if match_size > a:
            for i in range(match_size - a):
                frontend.append(0)
        return frontend + list(map(lambda stock: round(stock.percentage), stock_list))

    def create(self):

        query  = "INSERT INTO `company_stock` "
        query +=    "( `company_idx`, `price`, `prev_diff`, `percentage`, `open`, `high`, `low`, `volume`, `date`, `created_date_time`, `status` ) "
        query += "VALUES "
        query +=    "( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s ) "

        return self.postman.create(query, [
            self.company_idx, self.price, self.prev_diff, self.percentage, self.open, self.high, self.low, self.volume, self.date, str(datetime.now().strftime("%Y-%m-%d %H:%I:%S")), '1'
        ])

    def get(self, select = ' idx,price '):

        query  = "SELECT "
        query +=    select
        query += " FROM "
        query +=    "`company_stock` "
        query += "WHERE "
        if self.idx:                query += "`idx`=%s AND "
        if self.company_idx:        query += "`company_idx`=%s AND "
        if self.date:               query += "`date`=%s AND "
        query +=    "`status`=%s "

        params = []
        if self.idx:                params.append(self.idx)
        if self.company_idx:        params.append(self.company_idx)
        if self.date:               params.append(self.date)
        params.append('1')

        return CompanyStock.new(self.postman.get(query, params))


    def getList(self, **kwargs):

        sort_by     = kwargs['sort_by']         if 'sort_by'        in kwargs else 'idx'
        sdirection  = kwargs['sort_direction']  if 'sort_direction' in kwargs else 'desc'
        limit       = kwargs['limit']           if 'limit'          in kwargs else 20
        nolimit     = kwargs['nolimit']         if 'nolimit'        in kwargs else False
        noclass     = kwargs['noclass']         if 'noclass'        in kwargs else False
        offset      = kwargs['offset']          if 'offset'         in kwargs else 0
        select      = kwargs['select']          if 'select'         in kwargs else ' idx,company_idx,price,prev_diff,percentage,open,high,low,volume,date '

        query  = "SELECT "
        query +=    select
        query += " FROM "
        query +=    "`company_stock` "
        query += "WHERE "
        if self.company_idx:        query += "`company_idx`=%s AND "
        if self.date:               query += "`date`=%s AND "
        if self.percentage:         query += "`percentage`=%s AND "
        if self.search_start_date:  query += "`date`>=%s AND "
        if self.search_end_date:    query += "`date`<=%s AND "
        query +=    "`status`=%s "
        query += "ORDER BY {0} {1} ".format(sort_by, sdirection)
        if not nolimit:             query += "LIMIT %s offset %s "

        params = []
        if self.company_idx:        params.append(self.company_idx)
        if self.date:               params.append(self.date)
        if self.percentage:         params.append(self.percentage)
        if self.search_start_date:  params.append(self.search_start_date)
        if self.search_end_date:    params.append(self.search_end_date)
        params.append('1')
        if not nolimit:             params.extend((limit, offset))

        sqllist     = self.postman.getList(query, params)
        if not noclass:
            return list(map(lambda x: CompanyStock.new(x), sqllist))
        return sqllist
