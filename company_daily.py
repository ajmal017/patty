from urllib.parse import quote_plus, unquote_plus, urlparse, parse_qs
from bs4 import BeautifulSoup
import requests
import sys
from system.Helper import progressbar
from system.analytics.Stopwatch import Stopwatch
from application.model.Company import COMPANY_NEED_HISTORY
from application.model.Company import Company
from application.model.CompanyStock import CompanyStock

class CompanyDaily:

    progress_sofar = 0
    progress_total = 0
    create_list = []

    def getPage(self, company):

        # create url to page
        url = "http://finance.naver.com/item/sise_day.nhn?code={0}".format(company.code)

        # get page
        document = requests.get(url).content

        # return page content
        return BeautifulSoup(document, "html.parser")


    def getCompanySearchResults(self, soup):

        table = soup.find("table", class_="type2")
        tr_list = table.find_all("tr")
        data_list = []

        for tr in tr_list:
            td_list = tr.find_all("td")
            row = []
            skip = False
            for i,element in enumerate(td_list):
                s = element.text.replace('\\xa0', '')
                if i == 0 and len(s) <= 3:
                    skip = True
                row.append(s)

            if len(row) > 2:
                if skip:
                    pass
                else:
                    item = {}
                    item['date']      = row[0].replace('.','-')
                    item['price']     = row[1].replace(',','')
                    item['prev_diff'] = row[2].replace(',','').strip()
                    item['open']      = row[3].replace(',','')
                    item['high']      = row[4].replace(',','')
                    item['low']       = row[5].replace(',','')
                    item['volume']    = row[6].replace(',','')

                    data_list.append(item)

        return data_list


    def save_results(self, company):

        # get page with soup as result
        soup = self.getPage(company)

        # parse the html data
        # get the stock info into list data
        stock_data  = self.getCompanySearchResults(soup)

        # loop through data and insert data
        for row in stock_data:

            # create stock
            stock_check = CompanyStock().new({"company_idx":company.idx,"date":row["date"]})

            # check stock
            check = stock_check.get(' idx ')

            # msg
            msg = '기존: ' + row["date"]

            # check if company exists
            if not check.idx:
                msg = '신규: ' + row["date"]
                self.addcreate(CompanyStock.new({
                    "company_idx"   : company.idx,
                    "price"         : row["price"],
                    "prev_diff"     : row["prev_diff"],
                    "open"          : row["open"],
                    "high"          : row["high"],
                    "low"           : row["low"],
                    "volume"        : row["volume"],
                    "date"          : row["date"]
                }))

    def init(self):

        # start stopwatch
        stopwatch = Stopwatch.init()

        # start time
        stopwatch.start("company_history")

        # loop through range of 1,000 four times
        for i in range(0, 4):

            # self.progress_sofar
            company_list = Company.new().getList(sort_by = 'idx', sort_direction = 'desc', limit = 1000, offset = (i*1000), select = ' idx,code ')

            # loop through company list
            for company in company_list:

                # save results
                self.save_results(company)

        # update all companies with last_updated
        Company.new().dailyStockUpdate()

        # go through create list
        self.loop_createlist()

        print('-----------------------------------------------------------------------------')
        print('-----------------------------------------------------------------------------')
        print("new: {0} ".format(len(self.create_list)))
        print("progressed: {0} ".format(self.progress_total))
        print("total run time: {0}s ".format(stopwatch.end("company_history")))
        print('-----------------------------------------------------------------------------')
        print('-----------------------------------------------------------------------------')


    def addcreate(self, item):

        # add item to create list
        self.create_list.append(item)

        if len(self.create_list) > 1000:

            # create item
            self.loop_createlist()


    def loop_createlist(self):

        # loop through and create list
        for company_stock in self.create_list:

            # create company
            company_stock.create()

        # remove all item
        self.create_list.clear()

cs = CompanyHistory()
cs.init()
