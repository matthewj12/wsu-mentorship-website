# Calm down; none of these variables are modified after being set in this
# file (just shut the fuck up, ok? global varbiables are ok in moderation.)


# The default MySQL secure_file_privileges directory to import local files
# + directory for this database's sample data (SampleData)
sample_data_dir = 'C:/ProgramData/MySQL/MySQL Server 8.0/Uploads/sample-data/'

# uses participants explicitly defined in main.py when set to True
test_mode = False
# Prints useful debugging info throughought the matching process
debugging_on = False
# ignored when globvars.debugging_on == False
debug_participant_id = 'aaaaaaaa'

get_available_participants_query = (
	'\tSELECT *\n'
	'\tFROM `participant`\n'
	'\tWHERE (\n'
	'\t\tSELECT COUNT(*)\n'
	'\t\tFROM `mentorship`\n'
	'\t\tWHERE `mentor starid` = `participant`.`starid` OR `mentee starid` = `participant`.`starid`\n'
	'\t) < (select `max matches id` from `max matches assoc tbl` where `max matches assoc tbl`.`starid` = `participant`.`starid`)\n'
)
# `max matches`.`max matches id` is identical to `max matches`.`max matches`
