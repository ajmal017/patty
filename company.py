import sys
from component import *

def daily():
    """this function should be called only once daily"""

    # get yesterday's company stock information
    CompanyDaily().init()

    # search for new company added to the KOSPI & KOSDAK Index
    # do not need to get stock information (Do this after daily)
    CompanySearch().init()

def baily():
    """this function should be called only once daily but only after daily is called"""

    # check if the company's currently used are all good to go.
    # or if they should be excluded from training or testing phase
    CompanyExclude().init()


def hourly():
    """this function should be called hourly"""

    # get the full history for any new stock added to the database
    CompanyHistory().init()


"""check if the function that needs to be called has been passed """
if len(sys.argv) >= 2:
    locals()[sys.argv[1]]()
