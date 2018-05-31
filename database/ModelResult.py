from system.database.DataModel import DataModel
from system.database.BusinessModel import BusinessModel
from datetime import datetime

class ModelResult(DataModel, BusinessModel):

    idx                 = None
    playlist_idx        = None
    train_company_idx   = None
    test_company_idx    = None
    f1                  = None
    recall              = None
    accuracy            = None
    precise             = None
    created_date_time   = None
    status              = 1


    @staticmethod
    def new(data = {}):
        new = ModelResult()
        new.extend(data)
        return new


    def create(self):

        query  = "INSERT INTO `model_result` "
        query +=    "( `playlist_idx`, `train_company_idx`, `test_company_idx`, `f1`, `recall`, `accuracy`, `precise`, `created_date_time`, `status` ) "
        query += "VALUES "
        query +=    "( %s, %s, %s, %s, %s, %s, %s, %s, %s ) "

        return self.postman.create(query, [
            self.playlist_idx, self.train_company_idx, self.test_company_idx, self.f1, self.recall, self.accuracy, self.precise, str(datetime.now().strftime("%Y-%m-%d %H:%I:%S")), '1'
        ])
