from database import *
from tool import *
from model import *
import os
from os import listdir
from os.path import isfile, join
import json

class ModelReadMatch:

    def init(self):

        count = 0
        maxcount = 10

        onlyfiles = [f for f in listdir("./jsonout/") if isfile(join("./jsonout/", f))]

        for file in onlyfiles:

            count = count + 1

            if count > maxcount:
                break

            # lets skip readme file
            if file == "README.md":
                continue

            jsonObject = self.load_json("./jsonout/" + file)

            for item in jsonObject:
                ModelResult.new({
                    "playlist_idx"      : item["playlist_idx"],
                    "train_company_idx" : item["train_company_idx"],
                    "test_company_idx"  : item["test_company_idx"],
                    "f1"                : "0",
                    "accuracy"          : "0",
                    "recall"            : "0",
                    "precise"           : "0",
                    "score"             : float(item["score"]),
                    "type"              : item["type"],
                    "duration"          : item["duration"]
                }).checkCreate()

            if os.path.isfile("./jsonout/" + file):
                os.remove("./jsonout/" + file)

    def load_json(self, filename):

        if filename.find("solved_playlist_") == -1:
            return None
        else:
            try:
                config = open(filename)
                return json.load(config)
            except FileNotFoundError:
                pass
        return None
