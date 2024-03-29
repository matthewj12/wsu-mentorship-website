import mysql.connector, globvars


def createCursor(host=globvars.mysql_host, username=globvars.mysql_username, password=globvars.mysql_password, database=globvars.mysql_database):
	mpdb = mysql.connector.connect(host=host, user=username, password=password, database=database)

	return mpdb, mpdb.cursor(buffered=True)


# currently unused
def getParticipantColumns():
	mpdb, cursor = createCursor()
	cursor.execute("select group_concat(column_name) as '' from information_schema.columns where table_schema = 'mp' and table_name = 'participant';")

	fields = []
	for tup in cursor:
		fields = tup[0].split(',')

	return fields


# hyphenated-snake-case
def toHyphSnakeCase(s):
	l = s.split(' ')
	return l[0] + ''.join([f"-{word}" for word in l[1:]])


def idToName(id, col, cursor):
	query = (
		f"SELECT `{col}`"
		f"FROM `{col} ref tbl`"
		f"WHERE `id` = '{id}'"
	)
	cursor.execute(query)

	for tup in cursor:
		return tup[0]


def nameToId(name, col, cursor):
	query = (
		f"SELECT `id`"
		f"FROM `{col} ref tbl`"
		f"WHERE `{col}` = '{name}'"
	)
	cursor.execute(query)

	for tup in cursor:
		return tup[0]