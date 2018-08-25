from system.database.DataModel import DataModel
from system.database.BusinessModel import BusinessModel
from datetime import datetime

class ModelTrainingHistory(DataModel, BusinessModel):

    idx                 = None
    startt              = None
    endt                = None
    created_date_time   = None
    status              = None


    @staticmethod
    def new(data = {}):
        new = ModelTrainingHistory()
        new.extend(data)
        return new

    def create(self):

        query  = "INSERT INTO `model_training_history` "
        query +=    "( `startt`, `endt`, `created_date_time`, `status` ) "
        query += "VALUES "
        query +=    "( %s, %s, %s, %s ) "

        return self.postman.create(query, [
            self.startt, self.endt, str(datetime.now().strftime("%Y-%m-%d %H:%I:%S")), '1'
        ])

    def update_endt(self):
        
        self.postman.execute(
            " UPDATE `model_training_history` SET `endt`=%s WHERE `idx`=%s ",
            [self.endt, self.idx]
        )
