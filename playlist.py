import sys
from component import *

def minute():
    """this function should be called every minute!!"""

    pass


def daily():
    """this function should be called only once daily"""

    # solve the top volumen daily
    PlaylistFindTopVolume().init()


def hourly():
    """this function should be called hourly"""

    pass


def singleCore():
    """this function should be called for single core testing"""

    # solve SVM problems every minute
    PlaylistProcessSVM().initSingleCore()


def multiCore():
    """this function should be called for multicore testing"""

    # solve SVM problems every minute
    PlaylistProcessSVM().initMultiCore()


"""check if the function that needs to be called has been passed """
if len(sys.argv) >= 2:
    locals()[sys.argv[1]]()
