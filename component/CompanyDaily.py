from urllib.parse import quote_plus, unquote_plus, urlparse, parse_qs
from bs4 import BeautifulSoup
import requests
import sys
from database import *
from tool import *
import time

class CompanyDaily:

    progress_sofar = 0
    progress_total = 0
    create_list = []

    def getPage(self, company, repeat = 0):

        document = ""

        try:

            # make page string
            page_str = "&page={0}".format(page) if page > 0 else ""

            # create url to page
            url = "http://finance.naver.com/item/sise_day.nhn?code={0}".format(company.code)

            # get page
            document = requests.get(url).content

        except:

            # check if its attempted more than 2 times
            if repeat >= 2:
                return None

            # increase repeat count by one
            repeat  += 1

            # sleep for 6 seconds to make sure naver is stop tracking me
            time.sleep(6)

            # re-call getPage recursively
            return self.getPage(company, repeat)

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
                if i == 2:
                    s = s.replace(',','').strip()
                    img = element.find('img')
                    if img != None and img.has_attr('src'):
                        if 'down' in img['src']:
                            s = "-" + s
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
                percentage = 0
                if row["open"] == '0':
                    percentage = 0
                else:
                    yesterday_price = int(row["price"]) + (int(row["prev_diff"]) * -1)
                    try:
                        percentage = ((int(row["price"]) - yesterday_price) / yesterday_price) * 100
                    except ZeroDivisionError as err:
                        percentage = 0

                self.addcreate(CompanyStock.new({
                    "company_idx"   : company.idx,
                    "price"         : row["price"],
                    "prev_diff"     : row["prev_diff"],
                    "percentage"    : str(percentage),
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
            company_list = Company.new({"need_history":COMPANY_NEED_HISTORY.NO}).getList(sort_by = 'idx', sort_direction = 'desc', limit = 1000, offset = (i*1000), select = ' idx,code ')

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
