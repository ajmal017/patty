import sys
import time

class Stopwatch:

    # stopwatch singleton
    singleton = None

    # list holding accounts
    timer_list = {}

    @staticmethod
    def init():

        if Stopwatch.singleton == None:

            # create new object
            Stopwatch.singleton = Stopwatch()

        return Stopwatch.singleton

    def start(self, name):
        self.timer_list[name] = time.time()

    def check(self, name):
        start = self.timer_list[name]
        end = time.time()
        return str(end - start)

    def end(self, name):
        worktime = self.check(name)
        del self.timer_list[name]
        return worktime
