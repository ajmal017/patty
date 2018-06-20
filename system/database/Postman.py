import mysql.connector
from mysql.connector import errorcode
import json
import sys
import time

class Postman:

    # postan singleton
    singleton = None

    # mysql connection
    mysqlConnection = None

    # mysql cursor
    mysqlCursor = None

    @staticmethod
    def init():

        if Postman.singleton == None:

            # create new object
            Postman.singleton = Postman()

            # create new connection
            Postman.singleton.connect()

        return Postman.singleton


    def connect(self):

        # get database config
        config = self.get_config()

        # connection to database
        self.mysqlConnection = mysql.connector.connect(user=config["user"], password=config["password"], host=config["host"], database=config["database"], charset=config["charset"], port=config["port"], raise_on_warnings=True)

        # makes life easy
        self.mysqlConnection.autocommit = True

        # create cusor
        self.mysqlCursor = self.mysqlConnection.cursor(dictionary=True, buffered=True)

        # set names
        self.mysqlCursor.execute("SET NAMES " + config["connection"])


    def get_config(self):

        try:
            # load config file
            config = open("/var/www/project/patty/config/database.config")

            # decode to json
            return json.load(config)

        except FileNotFoundError:
            sys.exit("[Error] Cannot find database config file (location: " + "/../config/database.config" +")")


    def execute(self, sql, params = [], show_sql = False):

        # save start time
        start_time = time.time()

        try:

            # execute sql
            self.mysqlCursor.execute( sql, tuple(params) )

        except  mysql.connector.Error as err:
            print("[MYSQL ERROR] " , err)
            pass

        if show_sql:
            print(self.mysqlCursor.statement)

        # get total time taken
        result_time = (time.time() - start_time)

        # check if time take is larger than 5 miliseconds
        if result_time >= 0.05:

            # save query to file
            with open("log/slowquery.log", "a") as fp:
                str_time = "{:.3f}".format(result_time)
                fp.write(str_time + " py explain " + self.mysqlCursor.statement + "\n")

        return self.mysqlCursor


    def create(self, sql, params = [], show_sql = False):

        result = self.execute(sql, params, show_sql)
        return result.lastrowid


    def get(self, sql, params = [], show_sql = False):

        result = self.execute(sql, params, show_sql)

        for row in result:
            return row

        return None

    def getList(self, sql, params = [], show_sql = False):

        result = self.execute(sql, params, show_sql)

        # return list
        list = []

        # loop through result
        for item in result:
            list.append(item)

        return list


    def __del__(self):

        try:

            # clean up cursor
            self.mysqlCursor.close()

            # clean up mysql
            self.mysqlConnection.close()

        except ReferenceError:
            pass

    def __exit__(self, exc_type, exc_value, traceback):

        # clean up cursor
        self.mysqlCursor.close()

        # clean up mysql
        self.mysqlConnection.close()
