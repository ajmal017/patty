
class BusinessModel:

    def extend(self, data):
        if data:
            try:
                for key,val in data.items():
                    setattr(self, str(key), val)
            except AttributeError as error:
                pass
