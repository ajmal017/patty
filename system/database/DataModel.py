from system.database.Postman import Postman

class DataModel:

    postman = None

    def __init__(self):
        self.postman = Postman.init()
