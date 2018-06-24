from database import *
from tool import *
from model import *
import os
from os import listdir
from os.path import isfile, join
import json
from multiprocessing import Pool
from datetime import datetime

class PlaylistJsonSolve:

    def get_files(self):
        onlyfiles = [f for f in listdir("./json/") if isfile(join("./json/", f))]
        return onlyfiles

    def initmultiple(self):
        onlyfiles = self.get_files()
        with Pool(20) as p:
            print(p.map(self.solving, onlyfiles))

    def init(self):
        onlyfiles = self.get_files()
        for file in onlyfiles:
            self.solving(file)

    def solving(self, file):

        list_to_save = []

        # lets skip readme file
        if file == "README.md":
            return

        jsonObject = self.load_json("./json/" + file)

        train_stock_list = list(map(lambda x: CompanyStock.new(x), jsonObject["home"]["stock_list"]))

        svm_model = SVMWrapper()
        svm_model.train_data_x = CompanyStock.getOCHLV(train_stock_list, jsonObject["size"])
        svm_model.train_data_y = CompanyStock.getP(train_stock_list, jsonObject["size"])
        svm_model.train()

        for away in jsonObject["away_list"]:

            predict_stock_list = list(map(lambda x: CompanyStock.new(x), away["stock_list"]))

            svm_model.test_data_x = CompanyStock.getOCHLV(predict_stock_list, jsonObject["size"])
            svm_model.test_data_y = CompanyStock.getP(predict_stock_list, jsonObject["size"])
            score = svm_model.test()

            query  = "INSERT INTO `model_result` "
            query +=    "( `playlist_idx`, `train_company_idx`, `test_company_idx`, `type`, `f1`, `recall`, `accuracy`, `precise`, `score`,`duration`,`created_date_time`, `status` ) "
            query += "VALUES "
            query +=    "( '"+str(jsonObject["playlist"]["idx"])+"', "
            query +=    "'" + str(jsonObject["playlist"]["company_idx"]) + "', "
            query +=    "'" + str(away["company_idx"]) + "', "
            query +=    "'" + str(MODEL_TYPE.SVM) + "', "
            query +=    "'0', "
            query +=    "'0', "
            query +=    "'0', "
            query +=    "'0', "
            query +=    "'"+str(score)+"', "
            query +=    "'"+str(jsonObject["size"])+"', "
            query +=    "'"+str(datetime.now().strftime("%Y-%m-%d %H:%I:%S"))+"', "
            query +=    "'1'); \n"
            list_to_save.append(query)

        if os.path.isfile("./json/" + file):
            os.remove("./json/" + file)

        filename = "solved_playlist_" + str(jsonObject["playlist"]["idx"]) + ".sql"
        f = open("./jsonout/" + filename, "w")
        for line in list_to_save:
            f.write(line)
        f.close()


    def load_json(self, filename):

        if filename.find("need_to_solve_playlist_") == -1:
            return None
        else:
            try:
                config = open(filename)
                return json.load(config)
            except FileNotFoundError:
                pass
        return None
