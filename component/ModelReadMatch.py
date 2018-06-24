from database import *
from tool import *
from model import *
import os
from os import listdir
from os.path import isfile, join
import json

class ModelReadMatch:

    def init(self):

        filepath = "/mnt/wwwroot/afreecatv/jsonout/jsonout/"

        count = 0
        maxcount = 3

        onlyfiles = [f for f in listdir(filepath) if isfile(join(filepath, f))]

        for file in onlyfiles:

            count = count + 1

            if count > maxcount:
                break

            # lets skip readme file
            if file == "README.md":
                continue

            jsonObject = self.load_json(filepath + file)

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
                }).create()

            if os.path.isfile(filepath + file):
                os.remove(filepath + file)

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
