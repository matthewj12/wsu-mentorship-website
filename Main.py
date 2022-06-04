import mysql.connector
from Participant import Participant


def joinTuple(tup):
	res = ''

	for val in tup:
		res += str(val) + ','

	return res

def createCursor(host, user, password, database):
	mpdb = mysql.connector.connect(
		host=host,
		user=user,
		password=password,
		database=database
	)

	return mpdb, mpdb.cursor(buffered=True)

def buildParticipantsListFromQuery(cursor, where_clause_filter):
	cursor.execute('select * from participant where {};'.format(where_clause_filter))

	# Populate atomized data points
	results = []
	for tup in cursor:
		p = Participant(joinTuple(tup))
		results.append(p)

	# Populate aggregate data points (utilizing seperate tables, each referencing the `participant` table)
	cursor.execute("select hobby from `has hobby` where starid = '{}'"
		.format(results[-1].data_points['starid'])
	)
	results[-1].data_points['hobbies'] = joinTuple(tup[0]).split(',')

	return results




# mpdb = "mentorship program database"
mpdb, cursor = createCursor('localhost', 'root', '', 'mp')
participants = buildParticipantsListFromQuery(cursor, where_clause_filter='TRUE')

debugging_on = True
# not used when debugging is disabled
debug_participant_id = 'qn5161nm'
debug_participant_index = None

for p in participants:
	p.generateRanking(cursor, participants, debug_participant_id == p.data_points['starid'])




# ________________________________ DEBUGGING ________________________________
if debugging_on:
	for i in range(len(participants)):
		if participants[i].data_points['starid'] == debug_participant_id:
			debug_participant_index = i

	print("{}'s rankings:".format(participants[debug_participant_index].data_points['starid']))
	rank = 1
	for starid in participants[debug_participant_index].ranking:
		print('{}: {}'.format(rank, starid))
		rank += 1