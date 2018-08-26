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
            if company.name.find("ETN") != -1:
                need_to_remove = True
            if company.name.find("etn") != -1:
                need_to_remove = True
            if company.name.find("KODEX") != -1:
                need_to_remove = True
            if company.name.find("kodex") != -1:
                need_to_remove = True
            if company.name.find("ANKOR") != -1:
                need_to_remove = True
            if company.name.find("ankor") != -1:
                need_to_remove = True
            if company.name.find("KBSTAR") != -1:
                need_to_remove = True
            if company.name.find("kbstar") != -1:
                need_to_remove = True
            if company.name.find("TIGER") != -1:
                need_to_remove = True
            if company.name.find("tiger") != -1:
                need_to_remove = True
            if company.name.find("KINDEX") != -1:
                need_to_remove = True
            if company.name.find("kindex") != -1:
                need_to_remove = True
            if company.name.find("KOSEF") != -1:
                need_to_remove = True
            if company.name.find("kosef") != -1:
                need_to_remove = True
            if company.name.find("China") != -1:
                need_to_remove = True

            if company.name.find("TRUE") != -1:
                need_to_remove = True
            if company.name.find("true") != -1:
                need_to_remove = True
            if company.name.find("인버스") != -1:
                need_to_remove = True
            if company.name.find("ETN(H)") != -1:
                need_to_remove = True

            if company.name.find("ARIRANG") != -1:
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
