from urllib.parse import quote_plus, unquote_plus, urlparse, parse_qs
from bs4 import BeautifulSoup
import requests
import sys
from system.Helper import progressbar
from system.analytics.Stopwatch import Stopwatch
from application.model.Company import COMPANY_NEED_HISTORY
from application.model.Company import Company
from application.model.CompanyStock import CompanyStock

class CompanyHistory:

    progress_sofar = 0
    progress_total = 0
    create_list = {}

    def getPage(self, company, page):

        # make page string
        page_str = "&page={0}".format(page) if page > 0 else ""

        # create url to page
        url = "http://finance.naver.com/item/sise_day.nhn?code={0}{1}".format(company.code, page_str)

        # get page
        document = requests.get(url).content

        # return page content
        return BeautifulSoup(document, "html.parser")


    def getPagination(self, soup):

        table = soup.find("table", class_="Nnavi")
        if not table:
            return 0
        list = table.find_all("td")
        lastpage = None

        for element in list:
            try:
                if  element['class']:
                    pass
            except AttributeError: # element does not have .name attribute
                pass
            except KeyError: # element does not have a class
                lastpage = element.text.strip()
                pass

        if lastpage:
            return lastpage
        return 0


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
                    if img != None and img['alt'] == '하락':
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


    def save_results(self, company, page, max_page):

        # set total search count
        self.progress_total = (self.progress_total + (max_page + page))

        for page_num in range(page, (max_page+1)):

            # get page with soup as result
            soup = self.getPage(company, page_num)

            # parse the html data
            # get the stock info into list data
            stock_data  = self.getCompanySearchResults(soup)

            # increase progress time
            self.progress_total = (self.progress_total + len(stock_data))

            # loop through data and insert data
            for row in stock_data:

                # update progress so far
                self.progress_sofar = (self.progress_sofar + 1)

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
                        percentage = ((int(row["price"]) - yesterday_price) / yesterday_price) * 100

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

                # update progress
                progressbar(self.progress_sofar, self.progress_total, "검색:{0} ({1} / {2}) 시간: {3}  {4} ".format("{0} {1}".format(row["date"], company.name), self.progress_sofar, self.progress_total, Stopwatch.init().check("company_history"), msg))


    def init(self):

        # start stopwatch
        stopwatch = Stopwatch.init()

        # start time
        stopwatch.start("company_history")

        # self.progress_sofar
        company_list = Company.new({"need_history":COMPANY_NEED_HISTORY.YES}).getList(limit = 300, offset = 0, select = ' idx,name,code ')

        # set company count
        self.progress_total = len(company_list)

        # loop through company list change need_history status
        for company in company_list:
            company.need_history = COMPANY_NEED_HISTORY.NO
            company.updateNeedHistory()

        # loop through company list
        for company in company_list:

            current_page        = 0
            current_max_page    = 2

            while True:

                # save results
                self.save_results(company, current_page, current_max_page)

                # update current page
                current_page = current_max_page

                # see what the next max page number is
                check_max_page = int(self.getPagination(self.getPage(company, (current_page + 1))))

                # check to see if there is any new higher max
                if current_max_page >= check_max_page: break

                # update max page
                current_max_page = check_max_page

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

        if item.date not in self.create_list:
            self.create_list[item.date] = item

        if len(self.create_list) > 1000:

            # go create list
            self.loop_createlist()


    def loop_createlist(self):

        self.progress_total = (self.progress_total + len(self.create_list))

        # loop through and create list
        for k,company_stock in self.create_list.items():

            # create company
            company_stock.create()

            progressbar(self.progress_sofar, self.progress_total, "createing..........")

        # empty the list
        self.create_list.clear()


cs = CompanyHistory()
cs.init()
