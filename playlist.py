import sys
from component import *

def minute():
    """this function should be called every minute!!"""

    # solve SVM problems every minute
    PlaylistProcessSVM().init()


def daily():
    """this function should be called only once daily"""

    # solve the top volumen daily
    PlaylistFindTopVolume().init()


def hourly():
    """this function should be called hourly"""

    pass


"""check if the function that needs to be called has been passed """
if len(sys.argv) >= 2:
    locals()[sys.argv[1]]()
