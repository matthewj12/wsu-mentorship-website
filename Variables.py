# uses participants explicitly defined in Main.py when set to True
test_mode = False
# Prints useful debugging info throughought the matching process
debugging_on = False
# ignored when debugging is disabled
debug_participant_id = 'a'

get_available_participants_query = '''
	SELECT *
	FROM participant
	WHERE (
		SELECT COUNT(*)
		FROM mentorship
		WHERE `mentor starid` = participant.starid OR `mentee starid` = participant.starid
	) < CAST(participant.`max matches` as INT)
'''