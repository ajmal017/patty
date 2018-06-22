from system.database.DataModel import DataModel
from system.database.BusinessModel import BusinessModel
from datetime import datetime

class MODEL_TYPE:
    SVM = 1
    HMM = 2

class ModelResult(DataModel, BusinessModel):

    idx                 = None
    playlist_idx        = None
    train_company_idx   = None
    test_company_idx    = None
    type                = None
    f1                  = None
    recall              = None
    accuracy            = None
    precise             = None
    score               = None
    duration            = None
    created_date_time   = None
    status              = 1


    @staticmethod
    def new(data = {}):
        new = ModelResult()
        new.extend(data)
        return new


    def checkCreate(self):
        check = self.get()
        if not check.idx:
            self.create()


    def create(self):

        query  = "INSERT INTO `model_result` "
        query +=    "( `playlist_idx`, `train_company_idx`, `test_company_idx`, `type`, `f1`, `recall`, `accuracy`, `precise`, `score`,`duration`,`created_date_time`, `status` ) "
        query += "VALUES "
        query +=    "( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s ) "

        return self.postman.create(query, [
            self.playlist_idx, self.train_company_idx, self.test_company_idx, self.type, self.f1, self.recall, self.accuracy, self.precise, self.score, self.duration, str(datetime.now().strftime("%Y-%m-%d %H:%I:%S")), '1'
        ])


    def get(self, select = " idx "):

        query  = "SELECT "
        query +=    select
        query += " FROM "
        query +=    "`model_result` "
        query += "WHERE "
        if self.idx:                query += "`idx`=%s AND "
        if self.playlist_idx:       query += "`playlist_idx`=%s AND "
        if self.train_company_idx:  query += "`train_company_idx`=%s AND "
        if self.test_company_idx:   query += "`test_company_idx`=%s AND "
        query +=    "`status`=%s "

        params = []
        if self.idx:                params.append(self.idx)
        if self.playlist_idx:       params.append(self.playlist_idx)
        if self.train_company_idx:  params.append(self.train_company_idx)
        if self.test_company_idx:   params.append(self.test_company_idx)
        params.append('1')

        return ModelResult.new(self.postman.get(query, params))
