from system.database.Postman import Postman

class DataModel:

    postman = None

    def __init__(self):
        self.postman = Postman.init()

    def multicore(self, new_postman, multicore_status):
        if multicore_status:
            self.postman = new_postman
        return self
