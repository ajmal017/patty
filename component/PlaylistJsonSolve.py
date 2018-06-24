from database import *
from tool import *
from model import *
from os import listdir
from os.path import isfile, join
import json

class PlaylistJsonSolve:

    def init(self):

        list_to_save = []

        onlyfiles = [f for f in listdir("./json/") if isfile(join("./json/", f))]

        for file in onlyfiles:

            # lets skip readme file
            if file == "README.md":
                continue

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

                list_to_save.append({
                    "playlist_idx"      : jsonObject["playlist"]["idx"],
                    "train_company_idx" : jsonObject["playlist"]["company_idx"],
                    "test_company_idx"  : away["company_idx"],
                    "score"             : float(score),
                    "type"              : MODEL_TYPE.SVM,
                    "duration"          : jsonObject["size"]
                })

            filename = "solved_playlist_" + str(jsonObject["playlist"]["idx"]) + ".json"
            j = json.dumps(list_to_save)
            f = open("./json/" + filename, "w")
            f.write(j)
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
