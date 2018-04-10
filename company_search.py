from urllib.parse import quote_plus, unquote_plus, urlparse, parse_qs
from bs4 import BeautifulSoup
import requests
import sys
from system.Helper import progressbar
from system.analytics.Stopwatch import Stopwatch
from application.model.Company import COMPANY_MARKET
from application.model.Company import Company

class CompanySearch:

    progress_sofar = 0
    progress_total = 0
    create_list = []
    result_list = []

    def search_key(self):
        alphabet = [
            "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"
        ];
        korean = [
            "가", "나", "다", "라", "마", "바", "사", "아", "애", "자", "카", "파", "차", "여", "타", "하", "증", "우", "제", "건", "현", "대", "기", "지", "운", "은", "서", "너", "저", "구", "유", "조", "주", "디", "닉", "시", "비", "에", "딩", "우", "국", "한", "화", "섬", "노", "스", "루", "미", "디", "어", "씨", "엠", "이", "세", "보", "모", "고", "영", "네", "육", "교", "원", "알", "금", "속", "권", "니", "파", "바", "토", "소", "프", "퓨", "성", "평", "에", "일", "창", "한", "전", "강", "컴", "포", "엔", "물", "산", "태", "온", "웅", "보", "아", "데", "큐", "브", "코", "묘", "뉴", "품", "위", "텍", "엘", "신", "협", "농", "필", "파", "라", "재", "솔", "케", "커", "계", "경", "업", "풍", "캐", "플", "페", "피", "옵", "투", "백", "홈", "렉", "셀", "앤", "론", "디", "겜", "앙", "중", "션", "오", "정", "행", "린", "레", "젠", "공", "트", "익", "문", "칩", "백", "넥", "씨", "늄", "양", "히", "한", "콤", "텍", "연", "서", "구", "즈", "렌", "머", "무", "쳐", "쇼", "방", "울", "후", "성", "융", "쿠", "리", "본", "더", "버", "콜", "삼", "배", "드", "닥", "겟", "볼", "동"
        ];
        number = [
            "1", "2", "3", "4", "5", "6", "7", "8", "9", "0"
        ];
        symbol = [
            "-", "_", "(", ")"
        ];

        return alphabet + korean + number + symbol

    def getPage(self, name, page, repeat = 0):

        # change byte encoding
        partialNameEncoded = name.encode("euc-kr")

        # encode the name for url
        enc_name = quote_plus(partialNameEncoded)

        # create url to page
        url = "http://finance.naver.com/search/searchList.nhn?query={0}&page={1}".format(enc_name, page)

        # get page
        document = requests.get(url).content

        # return page content
        return BeautifulSoup(document, "html.parser")


    def getPagination(self, soup):

        div = soup.find("div", class_="paging")
        if not div: return 0

        list = div.find_all("a")
        lastpage = None

        for element in list:
            try:
                if  element['class']:
                    for cls in element['class']:
                        if cls in 'on':
                            lastpage = element.text.strip()
                    pass
            except AttributeError: # element does not have .name attribute
                pass
            except KeyError: # element does not have a class
                lastpage = element.text.strip()
                pass

        return lastpage


    def getCompanySearchResults(self, soup):

        table = soup.find("table", class_="tbl_search")
        if not table: return []
        tr_list = table.find_all("tr")
        data_list = []

        for tr in tr_list:
            td_list = tr.find_all("td")
            row = {}
            for i,element in enumerate(td_list):
                if  i == 0:
                    link = element.find("a")
                    img = element.find("img")
                    urlp = urlparse(link["href"])
                    urlq = parse_qs(urlp.query)
                    row["name"] = link.text.strip()
                    row["code"] = urlq["code"][0]
                    market = img.get("alt", "")
                    if market in "코스닥":
                        row["market"] = COMPANY_MARKET.KODAK
                    elif market in "코넥스":
                        row["market"] = COMPANY_MARKET.KONEK
                    elif market in "코스피":
                        row["market"] = COMPANY_MARKET.KOSPI
                    else:
                        row["market"] = COMPANY_MARKET.UNKNOWN

            if len(row) > 1:
                data_list.append(row)

        return data_list


    def save_results(self, key, page, max_page):

        # set total search count
        self.progress_total = (self.progress_total + (max_page + page))

        for page_num in range(page, max_page):

            # get page with soup as result
            soup = self.getPage(key, page_num)

            # parse the html data
            # get the stock info into list data
            stock_data  = self.getCompanySearchResults(soup)

            # increase progress time
            self.progress_total = (self.progress_total + len(stock_data))

            # loop through data and insert data
            for row in stock_data:

                # update progress so far
                self.progress_sofar = (self.progress_sofar + 1)

                # check if its only we have already processed
                if row['code'] in self.result_list:
                    continue

                # add to processsed list
                self.result_list.append(row['code'])

                # create company
                company = Company().new(row)

                # check company
                check = company.get(' idx ')

                # msg
                msg = '기존: ' + company.name

                # check if company exists
                if not check.idx:
                    msg = '신규: ' + company.name
                    self.addcreate(company)

                # update progress
                progressbar(self.progress_sofar, self.progress_total, "검색:{0} ({1} / {2}) 시간: {3}  {4} ".format(key, self.progress_sofar, self.progress_total, Stopwatch.init().check("company_search"), msg))

    def init(self):

        # start stopwatch
        stopwatch = Stopwatch.init()

        # start time
        stopwatch.start("company_search")

        # self.progress_sofar
        search_list = self.search_key()

        # set company count
        self.progress_total = len(search_list) + 2

        # go through search list
        for key in search_list:

            current_page        = 0
            current_max_page    = 2

            while True:

                # save results
                self.save_results(key, current_page, current_max_page)

                # update current page
                current_page = current_max_page

                # see what the next max page number is
                check_max_page = int(self.getPagination(self.getPage(key, (current_page + 1))))

                # check to see if there is any new higher max
                if current_max_page >= check_max_page: break

                # update max page
                current_max_page = check_max_page

        # loop through create list
        self.loop_createlist()

        print('-----------------------------------------------------------------------------')
        print('-----------------------------------------------------------------------------')
        print("new: {0} ".format(len(self.create_list)))
        print("progressed: {0} ".format(self.progress_total))
        print("total run time: {0}s ".format(stopwatch.end("company_search")))
        print('-----------------------------------------------------------------------------')
        print('-----------------------------------------------------------------------------')


    def addcreate(self, company):
        self.create_list.append(company)
        if len(self.create_list) > 1000:
            self.loop_createlist()

    def loop_createlist(self):

        # go through create list
        for company in self.create_list:

            # create company
            company.create()

cs = CompanySearch()
cs.init()
