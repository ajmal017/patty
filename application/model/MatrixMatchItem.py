from system.database.DataModel import DataModel
from system.database.BusinessModel import BusinessModel
from datetime import datetime

class MatrixMatchItem(DataModel, BusinessModel):

    idx                 = None
    matrix_match_idx    = None
    company_idx         = None
    point               = None
    created_date_time   = None

    @staticmethod
    def new(data = {}):
        new = MatrixMatchItem()
        new.extend(data)
        return new


    def create(self):

        query  = "INSERT INTO `matrix_match_item` "
        query +=    "( `matrix_match_idx`, `company_idx`, `point`, `created_date_time`, `status` ) "
        query += "VALUES "
        query +=    "( %s, %s, %s, %s, %s ) "

        return self.postman.create(query, [
            self.matrix_match_idx, self.company_idx, self.point, str(datetime.now().strftime("%Y-%m-%d %H:%I:%S")), '1'
        ])
