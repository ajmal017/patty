from component.CompanyDaily import CompanyDaily
from component.CompanySearch import CompanySearch
from component.CompanyHistory import CompanyHistory

def daily():
    daily_update = CompanyDaily()
    daily_update.init()

    search_for_new = CompanySearch()
    search_for_new.init()

    get_full_history = CompanyHistory()
    get_full_history.init()

def minute():
    pass
