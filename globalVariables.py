# uses participants explicitly defined in Main.py when set to True
test_mode = False
# Prints useful debugging info throughought the matching process
debugging_on = True
# ignored when debugging_on == False
debug_participant_id = 'aaaaaaaa'

get_available_participants_query = (
	'\tSELECT *\n'
	'\tFROM `participant`\n'
	'\tWHERE (\n'
	'\t	SELECT COUNT(*)\n'
	'\t	FROM `mentorship`\n'
	'\t	WHERE `mentor starid` = `participant`.`starid` OR `mentee starid` = `participant`.`starid`\n'
	'\t) < (select `max matches id` from `max matches assoc tbl` where `max matches assoc tbl`.`starid` = `participant`.`starid`)\n'
)
# `max matches`.`max matches id` is identical to `max matches`.`max matches`
