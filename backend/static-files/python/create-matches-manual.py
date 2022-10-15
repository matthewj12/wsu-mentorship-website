import matchfuncs, miscfuncs, sys
import globvars


if len(sys.argv) != 5:
	print('Error: create-matches-manual.py requires 4 arguments');
	quit()


# # mpdb = "mentorship program database"
mpdb, cursor = miscfuncs.createCursor('localhost', 'PHP', 'xBPCeD19z', 'mp')


start_date, end_date = sys.argv[1], sys.argv[2]
matches = [[sys.argv[3], sys.argv[4]]]

print(matches[0])

matchfuncs.addMatchesToDatabase(cursor, matches, start_date, end_date)

mpdb.commit()
cursor.close()
mpdb.close()

print("1 match inserted into database.")
