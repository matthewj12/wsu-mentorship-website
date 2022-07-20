import mysql.connector

def createCursor(host, user, password, database):
	mpdb = mysql.connector.connect(
		host=host,
		user=user,
		password=password,
		database=database
	)

	return mpdb, mpdb.cursor(buffered=True)


def getParticipantFields():
	mpdb, cursor = createCursor('localhost', 'root', '', 'mp')
	cursor.execute("select group_concat(column_name) as '' from information_schema.columns where table_schema = 'mp' and table_name = 'participant';")

	fields = []
	for tup in cursor:
		fields = tup[0].split(',')

	return fields