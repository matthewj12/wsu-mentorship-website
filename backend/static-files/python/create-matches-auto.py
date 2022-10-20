import matchfuncs, miscfuncs, sys
import globvars


if len(sys.argv) != 3:
	print('Error: create-matches-auto.py requires 2 arguments');
	quit()

# # mpdb = "mentorship program database"
mpdb, cursor = miscfuncs.createCursor()
participants = []

print()

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

start_date, end_date = sys.argv[1], sys.argv[2]
matchfuncs.addMatchesToDatabase(cursor, matches, start_date, end_date)

mpdb.commit()
cursor.close()
mpdb.close()

print(len(matches), "match" if len(matches) == 1 else "matches", "inserted into database.")
