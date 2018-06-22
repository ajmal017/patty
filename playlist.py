import sys
from component import *

def minute():
    """this function should be called every minute!!"""

    # solve SVM problems every minute
    PlaylistProcessSVM().init()


def daily():
    """this function should be called only once daily"""
    pass


def hourly():
    """this function should be called hourly"""

    pass


def devforce():
    """this function should be called by developer only in CLI"""
    PlaylistFindTopVolume().mimic_date()


"""check if the function that needs to be called has been passed """
if len(sys.argv) >= 2:
    locals()[sys.argv[1]]()
