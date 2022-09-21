# Calm down; none of these variables are modified after being set in this
# file (just shut the fuck up, ok? global varbiables are ok in moderation.)


# The default MySQL secure_file_privileges directory to import local files
# + directory for this database's sample data (SampleData)
sample_data_dir = 'C:/ProgramData/MySQL/MySQL Server 8.0/Uploads/sample-data/'

# Prints useful debugging info throughought the matching process
debugging_on = False
# ignored when globvars.debugging_on == False
debug_participant_id = 'a'

get_available_participants_query = (
	'\tSELECT *\n'
	'\tFROM `participant`\n'
	'\tWHERE `is active` and (\n'
	'\t\tSELECT COUNT(*)\n'
	'\t\tFROM `mentorship`\n'
	'\t\tWHERE'
	'\t\t`mentor starid` = `participant`.`starid` OR `mentee starid` = `participant`.`starid`\n'
	'\t) < (select `max matches id` from `max matches assoc tbl` where `max matches assoc tbl`.`starid` = `participant`.`starid`)\n'
)

get_available_participant_starids_query = f"SELECT `starid` FROM ({get_available_participants_query}) as temp;"

# `max matches`.`max matches id` is identical to `max matches`.`max matches`
