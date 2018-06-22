import sys
from component import *

def daily():
    """this function should be called only once daily"""
    pass

def hourly():
    """this function should be called hourly"""
    pass


"""check if the function that needs to be called has been passed """
if len(sys.argv) >= 2:
    locals()[sys.argv[1]]()
