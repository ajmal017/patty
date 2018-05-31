import sys
from component import *

def daily():
    """this function should be called only once daily"""

    # get yesterday's company stock information
    CompanyDaily().init()

    # search for new company added to the KOSPI & KOSDAK Index
    # do not need to get stock information (Do this after daily)
    CompanySearch().init()


def hourly():
    """this function should be called hourly"""

    # get the full history for any new stock added to the database
    CompanyHistory().init()


"""check if the function that needs to be called has been passed """
if len(sys.argv) >= 2:
    locals()[sys.argv[1]]()
