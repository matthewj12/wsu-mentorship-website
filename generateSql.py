from miscellaneousFunctions import *
from sampleDataUrls import *


sample_data_dir = 'C:/ProgramData/MySQL/MySQL Server 8.0/Uploads/SampleData/'


# ___________________________ ASSOCIATION TABLES ___________________________

# There are 2 types of `[...] assoc tbl`s in the `mp` schema: 
#     1) tables where participants can select any subset of the elements, including all and none.
#     2) tables where participants can select one and only one element; they can't select none.


name_strs_dict = {
	'important quality'    : 2,
	'max matches'          : 1,
	'gender'               : 1,
	'hobby'                : 2,
	'primary major'        : 2,
	'secondary major'      : 2,
	'primary pre program'  : 2,
	'secondary pre program': 2,
	'race'                 : 2,
	'religious affiliation': 1,
	'second language'      : 2
}

has_no_preferred_table = [
	'important quality',
	'max matches'
]

# so we can add to the dictionary while iterating through the original keys
name_strs_dict_keys = [key for key in name_strs_dict.keys()]
for distinct in name_strs_dict_keys:
	if not distinct in has_no_preferred_table:
		# Some data points (for example, religious affiliation) only allow participants to select one option, but they can still select multiple options for the corresponding preference table
		name_strs_dict['preferred ' + distinct] = 2


to_remove_for_ref_tbl_name = [
	'primary ',
	'secondary ',
	'preferred ',
	' assoc tbl'
]

def forRefTbl(distinct):
	for to_remove in to_remove_for_ref_tbl_name:
		distinct = distinct.replace(to_remove, '')

	return distinct


def genericCreateStmt(distinct, suffix=' assoc tbl'):
	concat = distinct + suffix
	
	primary_key = '`starid`'
	if name_strs_dict[distinct] == 2:
		primary_key += f", `{distinct} id`"
	# primary and secondary major/pre program both use the major/pre program table
	
	important_quality_rank = ''
	if distinct == 'important quality':
		important_quality_rank = '`important quality rank` tinyint NOT NULL,'

	return (
		f"drop table if exists `{concat}`;\n"
		f"create table `{concat}` (\n"
		f"\t`starid` char(8) NOT NULL,\n"
		f"\t`{distinct} id` tinyint NOT NULL,\n"
		f"{important_quality_rank}"
		f"\tprimary key({primary_key}),\n"
		f"\tforeign key(`starid`) references `participant`(`starid`) on delete cascade,\n"
		f"\tforeign key(`{distinct} id`) references `{forRefTbl(distinct)} ref tbl`(`id`) on delete cascade\n"
		f");\n\n\n"
	)


def genericLoadStmt(distinct, suffix=' assoc tbl'):
	concat = distinct + suffix

	return (
		f"load data local infile  \"{sample_data_dir + toCamelCase(concat)}.csv\"\n"
		f"into table `{concat}`\n"
		f"fields terminated by \",\" enclosed by \"'\"\n"
		f"lines terminated by \"\\n\"\n"
		f"ignore 1 rows;\n\n"
	)

# generates file that loads in sample data for all the associative tables plus the `participant` table
def generateAll():
	create_str = ''
	import_str = ''

	# staticly generated (in other words, non-for-loop) SQL
	create_str += ''.join([line for line in open('staticSql.sql', 'r')]) + '\n\n\n'
	import_str += genericLoadStmt('participant', '')

	# dynamic sql
	for distinct in name_strs_dict.keys():
		create_str += genericCreateStmt(distinct);
		import_str += genericLoadStmt(distinct);

	# remove trailing newlines (I prefer having just one newline at the end of every file)
	create_str = create_str[:-2]
	import_str = import_str[:-2]

	create_out_file = open('createTables.sql', 'w')
	import_out_file = open('importSampleData.sql', 'w')

	create_out_file.write(create_str)
	import_out_file.write(import_str)

	create_out_file.close()
	import_out_file.close()


generateAll()
