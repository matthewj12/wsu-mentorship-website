import matchingfunctions, miscellaneousfunctions
import testmodeparticipants
import globalvariables


# # mpdb = "mentorship program database"
mpdb, cursor = miscellaneousfunctions.createCursor('localhost', 'root', '', 'mp')
participants = []

print()

if globalvariables.test_mode:
	participants = testmodeparticipants.gettestmodeparticipants()

else:
	participants = matchingfunctions.buildParticipantsListFromQuery(cursor)

	if globalvariables.debugging_on:
		if matchingfunctions.getParticipantByStarid(participants, globalvariables.debug_participant_id) == None:
			print("Invalid globalvariables.debug_participant_id; could not find participant with id '{}' (could be because they're not available for matching).".format(globalvariables.debug_participant_id))
			print('Set debugging_on = False in main.py ignore.')
			quit()

		print("debug_participant_id = '{}'".format(globalvariables.debug_participant_id))
		print()
		

	for p in participants:
		p.generateRanking(cursor, globalvariables.debugging_on and p.data_points['starid'] == globalvariables.debug_participant_id)


#----------------------------------------------------------------------------
matches = matchingfunctions.createMatches(cursor, participants)
#----------------------------------------------------------------------------


if globalvariables.test_mode:
	print("0 matches inserted into database (test mode).")

else:
	matchingfunctions.addMatchesToDatabase(cursor, matches)

	mpdb.commit()
	cursor.close()
	mpdb.close()

	print(len(matches), "match" if len(matches) == 1 else "matches", "inserted into database.")
