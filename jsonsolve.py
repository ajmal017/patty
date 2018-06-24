import sys
from component import *

def dump():
    PlaylistToFile().init()

def solve():
    PlaylistJsonSolve().init()

def feed():
    ModelReadMatch().init()

"""check if the function that needs to be called has been passed """
if len(sys.argv) >= 2:
    locals()[sys.argv[1]]()
