from database import *
from tool import *

class CompanyExclude:

    def change_exlucde(self, company):
        Company.new({"idx" : company.idx, "exclude_learn" : COMPANY_EXCLUDE_LEARN.YES}).updateExcludeLearn()


    def init(self):
        limit = 100
        company_list = []
        show_log = False

        for i in range(30):
            company_list.extend(Company.new({"exclude_learn" : COMPANY_EXCLUDE_LEARN.NO }).getList(offset = (i * limit), limit = limit, sort_by = "idx", sort_direction = "desc"))

        list_len = len(company_list)

        for ind,company in enumerate(company_list):

            if show_log:
                print(str(ind) + "/" + str(list_len))

            need_to_remove = False

            #
            #
            # Check If Taboo Name
            if "ETN" in company.name:
                need_to_remove = True
            if "etn" in company.name:
                need_to_remove = True
            if "KODEX" in company.name:
                need_to_remove = True
            if "kodex" in company.name:
                need_to_remove = True
            if "ANKOR" in company.name:
                need_to_remove = True
            if "ankor" in company.name:
                need_to_remove = True
            if "KBSTAR" in company.name:
                need_to_remove = True
            if "kbstar" in company.name:
                need_to_remove = True
            if "TIGER" in company.name:
                need_to_remove = True
            if "tiger" in company.name:
                need_to_remove = True
            if "KINDEX" in company.name:
                need_to_remove = True
            if "kindex" in company.name:
                need_to_remove = True
            if "KOSEF" in company.name:
                need_to_remove = True
            if "kosef" in company.name:
                need_to_remove = True
            if "China" in company.name:
                need_to_remove = True

            if "TRUE" in company.name:
                need_to_remove = True
            if "true" in company.name:
                need_to_remove = True
            if "인버스" in company.name:
                need_to_remove = True
            if "ETN(H)" in company.name:
                need_to_remove = True

            if need_to_remove:
                self.change_exlucde(company)
                continue

            #
            #
            # used for listing company stock
            listing = CompanyStock.new({"company_idx" : company.idx})

            stock_list_1 = listing.getList(sort_by = "date", sort_direction = "desc", limit = 100, offset = 0)
            for stock in stock_list_1:
                if stock.open == 0 and stock.high == 0 and stock.low == 0:
                    self.change_exlucde(company)
                    continue

            stock_list_2 = listing.getList(sort_by = "date", sort_direction = "desc", limit = 100, offset = 100)
            for stock in stock_list_2:
                if stock.open == 0 and stock.high == 0 and stock.low == 0:
                    self.change_exlucde(company)
                    continue

            stock_list_3 = listing.getList(sort_by = "date", sort_direction = "desc", limit = 100, offset = 200)
            for stock in stock_list_3:
                if stock.open == 0 and stock.high == 0 and stock.low == 0:
                    self.change_exlucde(company)
                    continue

            stock_list_4 = listing.getList(sort_by = "date", sort_direction = "desc", limit = 100, offset = 300)
            for stock in stock_list_4:
                if stock.open == 0 and stock.high == 0 and stock.low == 0:
                    self.change_exlucde(company)
                    continue

            stock_list_5 = listing.getList(sort_by = "date", sort_direction = "desc", limit = 100, offset = 300)
            for stock in stock_list_5:
                if stock.open == 0 and stock.high == 0 and stock.low == 0:
                    self.change_exlucde(company)
                    continue
