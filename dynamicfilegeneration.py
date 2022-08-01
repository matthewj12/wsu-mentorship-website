# This file contains all the functions/dictionaries/code used in dynamically generating .sql and 
# .bat files for the entire project (see file-structure.svg for exactly how they are used.)

import miscellaneousfunctions, os, globalvariables


base_dir = 'dynamically-generated-files\\'

mockaroo_base_url = "https://api.mockaroo.com/api/"
distinct_part_of_urls = {
	'participant' : 'b7266940?count=6',
	
	'gender'                : '7024c370?count=6',
	'hobby'                 : '4ba241f0?count=24',
	'primary-major'         : 'ca372f90?count=9',
	'secondary-major'       : '641893e0?count=2',
	'primary-pre-program'   : '92987040?count=4',
	'secondary-pre-program' : '8c177de0?count=2',
	'race'                  : 'e78c2170?count=6',
	'religious-affiliation' : '9be133f0?count=6',
	'second-language'       : '6b95d550?count=4',

	'important-quality' : '2157a8c0?count=18',
	'max-matches'       : '7d771050?count=6',

	'preferred-gender'                : 'c0420be0?count=12',
	'preferred-hobby'                 : 'eae20460?count=4',
	'preferred-primary-major'         : '3735e520?count=18',
	'preferred-secondary-major'       : 'cdc61170?count=4',
	'preferred-primary-pre-program'   : '6dda7f50?count=4',
	'preferred-secondary-pre-program' : '00a3ad80?count=4',
	'preferred-race'                  : '77ea45e0?count=12',
	'preferred-religious-affiliation' : '2fb733b0?count=12',
	'preferred-second-language'       : '4d7ce400?count=8'
}
mockaroo_key = "1d04b730"

def createDynamicFileDir():
	if os.system('cd {} && cd ..'.format(base_dir)) != 0:
		print("Directory '{}' has been created.".format(base_dir[:-1]))
		os.system(f"mkdir {base_dir}")

# generates and/or executes the batch file (Windows) that creates all the associative tables plus the `participant` table
def generateSampleDataCurlRequestsFile(file_extension='.bat', what_to_do=['generate file', 'execute file']):
	createDynamicFileDir()
	
	if 'generate file' in what_to_do:
		out_file = open(base_dir + 'sample-data-curl-requests' + file_extension, 'w')
		out_file.write('pushd .\n')

		# Create the directory if it doesn't already exist
		if os.system('cd ' + globalvariables.sample_data_dir) != 0:
			print("Directory '{}' has been created.".format(globalvariables.sample_data_dir))
			os.system('mkdir "{}"'.format(globalvariables.sample_data_dir))

		out_file.write('cd ' + globalvariables.sample_data_dir + '\n')

		for distinct in distinct_part_of_urls.keys():
			suffix = '-assoc-tbl' if distinct != 'participant' else ''

			curl = 'curl -s "{}{}&key={}" > "{}{}.csv"\n'.format(
				mockaroo_base_url, distinct_part_of_urls[distinct], mockaroo_key,
				distinct, suffix
			)

			out_file.write(curl)

		out_file.write('popd\n')
		# out_file.write('exit')
		out_file.close()

	if 'execute file' in what_to_do:
		result_code = os.system('{}sample-data-curl-requests{}'.format(base_dir, file_extension))

		if result_code == 0:
			print('Sample data has been successfully downloaded.')
		else:
			print("Error executing 'sample-data-curl-requests.bat' in 'generateSampleDataCurlRequestsBatch()' in 'dynamicfilegeneration.py'")


# Association Tables: There are 2 types of `[...] assoc tbl`s in the `mp` schema: 
#     1) tables where participants can select any subset of the elements, including all and none.
#     2) tables where participants can select one and only one element; they can't select none.

distinct_part_of_assoc_tbl_names = {
	'max matches'          : 1,
	'gender'               : 1,
	'religious affiliation': 1,
	'important quality'    : 2,
	'hobby'                : 2,
	'primary major'        : 2,
	'secondary major'      : 2,
	'primary pre program'  : 2,
	'secondary pre program': 2,
	'race'                 : 2,
	'second language'      : 2
}
has_no_corresponding_preferred_table = [
	'important quality',
	'max matches'
]

to_remove_for_ref_tbl_names = [
	'primary ',
	'secondary ',
	'preferred ',
	' assoc tbl'
]
def forRefTbl(distinct):
	for to_remove in to_remove_for_ref_tbl_names:
		distinct = distinct.replace(to_remove, '')

	return distinct

# so we can add to the dictionary while iterating through the original keys
distinct_part_of_assoc_tbl_names_keys = [key for key in distinct_part_of_assoc_tbl_names.keys()]
for distinct in distinct_part_of_assoc_tbl_names_keys:
	if not distinct in has_no_corresponding_preferred_table:
		# Some data points (for example, religious affiliation) only allow 
		# participants to select one option, but they can still select 
		# multiple options for the corresponding preference table
		distinct_part_of_assoc_tbl_names['preferred ' + distinct] = 2

# only used internally (in this file)
def genericCreateStmt(distinct, suffix=' assoc tbl'):
	concat = distinct + suffix
	
	primary_key = '`starid`'
	if distinct_part_of_assoc_tbl_names[distinct] == 2:
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
	pass # to get VSCode to collapse the function properly

# only used internally (in this file)
def genericLoadStmt(distinct, suffix=' assoc tbl'):
	# This environment variable is set at the beginning of .run-me[os name].bat
	# sample_data_dir = os.environ['sample_data_dir']

	concat = distinct + suffix

	return (
		f"load data local infile \"{globalvariables.sample_data_dir}{miscellaneousfunctions.toHyphenatedSnakeCase(concat)}.csv\"\n"
		f"into table `{concat}`\n"
		f"fields terminated by \",\" enclosed by \"'\"\n"
		f"lines terminated by \"\\n\"\n"
		f"ignore 1 rows;\n\n"
	)
	pass # to get VSCode to fold this function properly

# generates and/or executes the SQL file that creates all the associative tables plus the `participant` table
def generateCreateTablesFile(file_extension='.sql', what_to_do=['generate file', 'execute file']):
	createDynamicFileDir()
	
	if 'execute file' in what_to_do:
		os.system('mysql -u root < "drop-db-and-create-empty-db.sql')

	if 'generate file' in what_to_do:
		create_str = ''

		# staticly generated (in other words, non-for-loop) SQL
		create_str += ''.join([line for line in open('static-sql.sql', 'r')]) + '\n\n\n'

		# dynamic sql
		for distinct in distinct_part_of_assoc_tbl_names.keys():
			create_str += genericCreateStmt(distinct);

		# remove trailing newlines (I prefer having just one newline at the end of every file)
		create_str = create_str[:-2]

		create_out_file = open(base_dir + 'create-tables' + file_extension, 'w')
		create_out_file.write(create_str)
		create_out_file.close()

	if 'execute file' in what_to_do:
		os.system('mysql -u root mp < "{}create-tables{}"'.format(base_dir, file_extension))

# generates and/or executes the SQL file that loads in sample data for all the associative tables plus the `participant` table
def generateImportSampleDataFile(file_extension='.sql', what_to_do=['generate file', 'execute file']):
	createDynamicFileDir()
	
	if 'generate file' in what_to_do:
		import_str = ''

		# staticly generated (in other words, non-for-loop) SQL
		import_str += genericLoadStmt('participant', '')

		# dynamic sql
		for distinct in distinct_part_of_assoc_tbl_names.keys():
			import_str += genericLoadStmt(distinct);

		# remove trailing newlines (I prefer having just one newline at the end of every file)
		import_str = import_str[:-2]

		import_out_file = open(base_dir + 'import-sample-data' + file_extension, 'w')
		import_out_file.write(import_str)
		import_out_file.close()

	if 'execute file' in what_to_do:
		return_code = os.system('mysql -u root --local_infile mp < "{}import-sample-data.{}"'.format(base_dir, file_extension))

		if return_code == 0:
			print('Sample data has been successfully imported.')
		else:
			print("Error executing 'import-sample-data.sql' in 'generateImportSampleDataSql()' in 'dynamicfilegeneration.py'")			