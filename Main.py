from CreateMatches import *
from Variables import *


# mpdb = "mentorship program database"
mpdb, cursor = createCursor('localhost', 'root', '', 'mp')
participants = []

if test_mode:
	# for if you want to mess around and test the matching algorithm manually
	participants.append(Participant())
	participants.append(Participant())
	participants.append(Participant())
	participants.append(Participant())
	participants.append(Participant())
	participants.append(Participant())
	participants.append(Participant())
	participants.append(Participant())

	# def debug_constructor(self, is_active, is_mentor, starid, max_matches, ranking):

	# mentees
	participants[0].debugConstructor('1', '0', 'a', '1', ['e', 'g', 'f', 'h'])
	participants[1].debugConstructor('1', '0', 'b', '1', ['e', 'h', 'g', 'f'])
	participants[2].debugConstructor('1', '0', 'c', '1', ['f', 'e', 'g', 'h'])
	participants[3].debugConstructor('1', '0', 'd', '1', ['f', 'e', 'g', 'h'])
	# mentors
	participants[4].debugConstructor('1', '1', 'e', '2', ['a', 'b', 'c', 'd'])
	participants[5].debugConstructor('1', '1', 'f', '2', ['a', 'b', 'c', 'd'])
	participants[6].debugConstructor('1', '1', 'g', '2', ['a', 'b', 'c', 'd'])
	participants[7].debugConstructor('1', '1', 'h', '2', ['a', 'b', 'c', 'd'])

else:
	for participant in buildParticipantsListFromQuery(cursor, where_clause_filter='TRUE'):
		participants.append(participant)

	if debugging_on:
		print('Generating {}\'s ranking...\n'.format(debug_participant_id))

	for p in participants:
		p.generateRanking(cursor, participants, debugging_on)

	if debugging_on:
		print()
		printRanking(participants, debug_participant_id)



matches = createMatches(cursor, participants)

# don't add anything to the database if we're in test mode
if not test_mode:
	addMatchesToDatabase(cursor, matches)

	mpdb.commit()
	cursor.close()
	mpdb.close()

