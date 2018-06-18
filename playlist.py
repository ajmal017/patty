import sys
from component import *

def minute():
    """this function should be called every minute!!"""
    PlaylistCustomSVM().init()


def daily():
    """this function should be called only once daily"""

    # get the top volume from yesterday's market
    PlaylistFindTopVolume().init()


def hourly():
    """this function should be called hourly"""
    pass


def devforce():
    """this function should be called by developer only in CLI"""
    PlaylistFindTopVolume().mimic_date()


"""check if the function that needs to be called has been passed """
if len(sys.argv) >= 2:
    locals()[sys.argv[1]]()
