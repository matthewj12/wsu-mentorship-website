import mysql.connector
from Participant import Participant
from matching.games import StableMarriage


def createCursor(host, user, password, database):
	mpdb = mysql.connector.connect(
		host=host,
		user=user,
		password=password,
		database=database
	)

	return mpdb, mpdb.cursor(buffered=True)

# copies all rows from the participant-related SQL tables into Participant objects. The only table this currently doesn't include is `mentorship`
def buildParticipantsListFromQuery(cursor, where_clause_filter):
	cursor.execute('select * from participant where {};'.format(where_clause_filter))

	# Populate atomized data points
	results = []
	for row_tuple in cursor:
		results.append(Participant(row_tuple))
		

	# # Populate aggregate data points (utilizing seperate tables, each referencing the `participant` table)
	# cursor.execute("select hobby from `has hobby` where starid = '{}'"
	# 	.format(results[-1].data_points['starid'])
	# )
	# results[-1].data_points['hobbies'] = joinTuple(tup[0]).split(',')

	return results


def printRanking(participants, participant_id):
	# Determine participants index where starid == debug_participant_starid
	for i in range(len(participants)):
		if participants[i].data_points['starid'] == debug_participant_id:
			participant_index = i

	# Print out debug participant's ranking
	print("\n{}'s rankings:".format(participants[participant_index].data_points['starid']))
	rank = 1
	for starid in participants[participant_index].ranking:
		print('{}: {}'.format(rank, starid))
		rank += 1


def matchParticipants(cursor, participants):
	global debug_participant_id

	mentee_prefs = dict( [(p.data_points['starid'], p.ranking) for p in participants if not p.data_points['is mentor']] )
	mentor_prefs = dict( [(p.data_points['starid'], p.ranking) for p in participants if     p.data_points['is mentor']] )

	game = StableMarriage.create_from_dictionaries(mentee_prefs, mentor_prefs)
	stable_pairings = game.solve()

	print()

	# for key in stable_pairings.keys():
	# 	print('Mentee \'{}\' paired with mentor \'{}\''.format(key, stable_pairings[key]))

	# for pairing in stable_pairings:
	# 	print(type(pairing))

	mentees = list(stable_pairings.keys())
	mentors = list(stable_pairings.values())

	for i in range(len(mentors)):
		insert_str = '''
			INSERT INTO mentorship
			(`mentor starid`, `mentee starid`, `start date`, `end date`)
			VALUES
			('{}', '{}', '2022-09-01', '2023-05-01');
		'''.format(
			# mentors/mentees list items are of type 'Player' (from matching library), and 'name' is simply therir unique identifier (in this case, starid).
			mentors[i].name,
			mentees[i].name
		)

		# print(insert_str)

		cursor.execute(insert_str)


debugging_on = True
# ignored when debugging is disabled
debug_participant_id = 'bbbbbbbb'

def main():
	# mpdb = "mentorship program database"
	mpdb, cursor = createCursor('localhost', 'root', '', 'mp')
	participants = buildParticipantsListFromQuery(cursor, where_clause_filter='TRUE')

	if debugging_on: print('Generating {}\'s ranking...\n'.format(debug_participant_id))

	for p in participants:
		p.generateRanking(cursor, participants, debugging_on and debug_participant_id == p.data_points['starid'])

	if debugging_on:
		print()
		printRanking(participants, debug_participant_id)


	matchParticipants(cursor, participants)

	mpdb.commit()
	cursor.close()
	mpdb.close()

main()