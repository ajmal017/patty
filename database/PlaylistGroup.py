from system.database.DataModel import DataModel
from system.database.BusinessModel import BusinessModel
from datetime import datetime

class PlaylistGroup(DataModel, BusinessModel):

    idx                 = None
    sort_idx            = None
    name                = None
    created_date_time   = None


    @staticmethod
    def new(data = {}):
        new = PlaylistGroup()
        new.extend(data)
        return new

    def create(self):

        self.sort_idx  = 0

        query  = "INSERT INTO `playlist_group` "
        query +=    "( `sort_idx`, `name`, `created_date_time`, `status` ) "
        query += "VALUES "
        query +=    "( %s, %s, %s, %s ) "

        return self.postman.create(query, [
            self.sort_idx, self.name, str(datetime.now().strftime("%Y-%m-%d %H:%I:%S")), '1'
        ])

    def get(self, select = ' idx,sort_idx,name '):

        query  = "SELECT "
        query +=    select
        query += " FROM "
        query +=    "`playlist_group` "
        query += "WHERE "
        if self.idx: query += "`idx`=%s AND "
        query +=    "`status`=%s "

        params = []
        if self.idx:                params.append(self.idx)
        params.append('1')

        return Playlist.new(self.postman.get(query, params))

    def getList(self, **kwargs):

        sort_by     = kwargs['sort_by']         if 'sort_by'        in kwargs else 'idx'
        sdirection  = kwargs['sort_direction']  if 'sort_direction' in kwargs else 'desc'
        limit       = kwargs['limit']           if 'limit'          in kwargs else 20
        nolimit     = kwargs['nolimit']         if 'nolimit'        in kwargs else False
        offset      = kwargs['offset']          if 'offset'         in kwargs else 0
        select      = kwargs['select']          if 'select'         in kwargs else ' idx,sort_idx,name '

        query  = "SELECT "
        query +=    select
        query += " FROM "
        query +=    "`playlist_group` "
        query += "WHERE "
        query +=    "`status`=%s "
        query += "ORDER BY {0} {1} ".format(sort_by, sdirection)
        if not nolimit:         query += "LIMIT %s offset %s "

        params = []
        params.append('1')
        if not nolimit:         params.extend((limit, offset))
        sqllist     = self.postman.getList(query, params)
        return_list = list(map(lambda x: PlaylistGroup.new(x), sqllist))

        return return_list

    def update_sort_idx(self):
        self.postman.execute(
            " UPDATE `playlist_group` SET `sort_idx`=%s WHERE `idx`=%s ",
            [self.sort_idx, self.idx]
        )
