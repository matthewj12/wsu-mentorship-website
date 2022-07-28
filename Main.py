from CreateMatches import *
from TestModeParticipants import *
from Variables import *


# mpdb = "mentorship program database"
mpdb, cursor = createCursor('localhost', 'root', '', 'mp')
participants = []

if test_mode:
	participants = getTestModeParticipants()

else:
	participants = buildParticipantsListFromQuery(cursor)

	if debugging_on:
		if getParticipantByStarid(participants, debug_participant_id) == None:
			print("Invalid debug_participant_id; could not find participant with id '{}'".format(debug_participant_id))
			debugging_on = False

		print("Generating {}'s ranking...".format(debug_participant_id))

	for p in participants:
		p.generateRanking(cursor, debugging_on and p.data_points['starid'] == debug_participant_id)

	if debugging_on:
		printRanking(participants, debug_participant_id)



matches = createMatches(cursor, participants)


# don't add anything to the database if we're in test mode
if not test_mode:
	addMatchesToDatabase(cursor, matches)

	mpdb.commit()
	cursor.close()
	mpdb.close()

	new_match_count = len(matches)

	print(new_match_count, "match" if new_match_count == 1 else "matches", "inserted into database.")

else:
	print("0 matches inserted into database.")