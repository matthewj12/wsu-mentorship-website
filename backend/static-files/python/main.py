import matchfuncs, miscfuncs
import testmodeparticipants
import globvars


# # mpdb = "mentorship program database"
mpdb, cursor = miscfuncs.createCursor('localhost', 'root', '', 'mp')
participants = []

print()

if globvars.test_mode:
	participants = testmodeparticipants.gettestmodeparticipants()

else:
	participants = matchfuncs.buildParticipantsListFromQuery(cursor)

	if globvars.debugging_on:
		if matchfuncs.getParticipantByStarid(participants, globvars.debug_participant_id) == None:
			print("Invalid globvars.debug_participant_id; could not find participant with id '{}' (could be because they're not available for matching).".format(globvars.debug_participant_id))
			print('Set debugging_on = False in main.py ignore.')
			quit()

		print("debug_participant_id = '{}'".format(globvars.debug_participant_id))
		print()
		

	for p in participants:
		p.generateRanking(cursor, globvars.debugging_on and p.data_points['starid'] == globvars.debug_participant_id)


#----------------------------------------------------------------------------
matches = matchfuncs.createMatches(cursor, participants)
#----------------------------------------------------------------------------


if globvars.test_mode:
	print("0 matches inserted into database (test mode).")

else:
	matchfuncs.addMatchesToDatabase(cursor, matches)

	mpdb.commit()
	cursor.close()
	mpdb.close()

	print(len(matches), "match" if len(matches) == 1 else "matches", "inserted into database.")
