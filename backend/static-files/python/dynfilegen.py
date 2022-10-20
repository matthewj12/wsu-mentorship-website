# This file contains all the functions/dictionaries/code used in dynamically generating .sql and 
# .bat files for the entire project (see file-structure.svg for exactly how they are used.)

import miscfuncs, os, globvars


backend = (os.getcwd() + '/backend').replace('\\', '/')

mockaroo_base_url = 'https://api.mockaroo.com/api/'
mockaroo_key = '1d04b730'
distinct_part_of_urls = {
	'participant' : 'b7266940',
	
	'gender'                : '7024c370',
	'hobby'                 : '4ba241f0',
	'major'                 : '3735e520',
	'pre-program'           : '92987040',
	'race'                  : 'e78c2170',
	'religious-affiliation' : '9be133f0',
	'second-language'       : '6b95d550',

	'important-quality' : '2157a8c0',
	'max-matches'       : '7d771050',

	'preferred-gender'                : 'c0420be0',
	'preferred-hobby'                 : 'eae20460',
	'preferred-major'                 : '3735e520',
	'preferred-pre-program'           : '6dda7f50',
	'preferred-race'                  : '77ea45e0',
	'preferred-religious-affiliation' : '2fb733b0',
	'preferred-second-language'       : '4d7ce400'
}

def ensureFolderExists(folder):
	if not os.path.exists(folder):
		os.makedirs(folder)
		print(f"Directory '{folder}' has been created.")

# generates and/or executes the batch file (Windows) that creates all the associative tables plus the `participant` table
def genSampleDataCurlRequestsFile(generate_file, execute_file):
	if generate_file:
		ensureFolderExists(f"{backend}/dynamically-generated-files/batch")

		out_file = open(f"{backend}/dynamically-generated-files/batch/sample-data-curl-requests.bat", 'w')
		out_file.write('pushd .\n')

		out_file.write(f"cd {globvars.sample_data_dir}\n")

		for distinct in distinct_part_of_urls.keys():
			suffix = '-assoc-tbl' if distinct != 'participant' else ''

			curl = 'curl -s "{}{}?key={}" > "{}{}.csv"\n'.format(
				mockaroo_base_url, distinct_part_of_urls[distinct], mockaroo_key,
				distinct, suffix
			)

			out_file.write(curl)

		out_file.write('popd\n')
		# out_file.write('exit')
		out_file.close()

	if execute_file:
		ensureFolderExists(globvars.sample_data_dir)
		to_execute = f"{backend}/dynamically-generated-files/batch/sample-data-curl-requests.bat"
		assert(os.path.exists(to_execute))
		return_code = os.system(to_execute)
		assert(return_code == 0)
		print('\nSample data has been successfully downloaded.')

# Association Tables: There are 2 types of `[...] assoc tbl`s in the `mp` schema: 
#     1) tables where participants can select any subset of the elements, including all and none.
#     2) tables where participants can select one and only one element; they can't select none.

assoc_tbl_names_unique_substr = {
	'max matches'           : 1,
	'gender'                : 1,
	'religious affiliation' : 1,
	'important quality'     : 2,
	'hobby'                 : 2,
	'major'                 : 2,
	'pre program'           : 2,
	'race'                  : 2,
	'second language'       : 2
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
assoc_tbl_names_unique_substr_keys = [key for key in assoc_tbl_names_unique_substr.keys()]
for unique in assoc_tbl_names_unique_substr_keys:
	if not unique in has_no_corresponding_preferred_table:
		# Some data points (for example, religious affiliation) only allow 
		# participants to select one option, but they can still select 
		# multiple options for the corresponding preference table
		assoc_tbl_names_unique_substr['preferred ' + unique] = 2

# only used internally (in this file)
def genericCreateStmt(distinct, suffix=' assoc tbl'):
	concat = distinct + suffix
	
	primary_key = '`starid`'
	if assoc_tbl_names_unique_substr[distinct] == 2:
		primary_key += f", `{distinct} id`"
	# primary and secondary major/pre program both use the major/pre program table
	
	# special case for `important quality` table which has one additional column
	important_quality_rank = ''
	if distinct == 'important quality':
		important_quality_rank = '\t`important quality rank` tinyint NOT NULL,\n'

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
		f"load data local infile \"{globvars.sample_data_dir}{miscfuncs.toHyphSnakeCase(concat)}.csv\"\n"
		f"into table `{concat}`\n"
		f"fields terminated by \",\" enclosed by \"'\"\n"
		f"lines terminated by \"\\n\"\n"
		f"ignore 1 rows;\n\n"
	)
	pass # to get VSCode to fold this function properly

# generates and/or executes the SQL file that creates all the associative tables plus the `participant` table
def genCreateTablesFile(generate_file, execute_file):
	ensureFolderExists(f"{backend}/dynamically-generated-files/sql")
	
	if execute_file:
		# os.system(f"mysql -u PHP < \"{backend}/static-files/sql/drop-db-and-create-empty-db.sql\"")

		mpdb, cursor = miscfuncs.createCursor()
		with open(f"{backend}/static-files/sql/drop-db-and-create-empty-db.sql", encoding="utf8") as f:
			cursor.execute(f.read(), multi=True)


	if generate_file:
		create_str = ''
		# staticly generated (in other words, non-for-loop) SQL
		create_str += ''.join([line for line in open(f"{backend}/static-files/sql/ref-tbls.sql", 'r')]) + '\n\n\n'
		create_str += ''.join([line for line in open(f"{backend}/static-files/sql/participant-and-mentorship.sql", 'r')]) + '\n\n\n'
		# dynamic sql
		for distinct in assoc_tbl_names_unique_substr.keys():
			create_str += genericCreateStmt(distinct);
		# remove trailing newlines (I prefer having just one newline at the end of every file)
		create_str = create_str[:-2]

		with open(f"{backend}/dynamically-generated-files/sql/create-tables.sql", 'w') as out_file:
			out_file.write(create_str)

	if execute_file:
		to_execute = f"{backend}/dynamically-generated-files/sql/create-tables.sql"
		assert(os.path.exists(to_execute))

		mpdb, cursor = miscfuncs.createCursor()
		with open(to_execute, encoding="utf8") as f:
			cursor.execute(f.read(), multi=True)

		# return_code = os.system('mysql -u PHP mp < "{}"'.format(to_execute))
		# assert(return_code == 0)
		print()
		print('Tables have been sucessfully created in `mp` database.')

# generates and/or executes the SQL file that loads in sample data for all the associative tables plus the `participant` table
def genImportSampleDataFile(generate_file, execute_file):
	ensureFolderExists(f"{backend}/dynamically-generated-files/sql")
	
	if generate_file:
		import_str = ''
		# staticly generated (in other words, non-for-loop) SQL
		import_str += genericLoadStmt('participant', '')
		# dynamic sql
		for distinct in assoc_tbl_names_unique_substr.keys():
			import_str += genericLoadStmt(distinct)
		# remove trailing newlines (I prefer having just one newline at the end of every file)
		import_str = import_str[:-2]

		with open(f"{backend}/dynamically-generated-files/sql/import-sample-data.sql", 'w') as out_file:
			out_file.write(import_str)

	if execute_file:
		to_execute = f"{backend}/dynamically-generated-files/sql/import-sample-data.sql"
		assert(os.path.exists(to_execute))

		mpdb, cursor = miscfuncs.createCursor()
		with open(to_execute, encoding="utf8") as f:
			cursor.execute(f.read(), multi=True)

		# return_code = os.system(f"mysql -u PHP --local_infile mp < {to_execute}")
		# assert(return_code == 0)
		print('\nSample data has been successfully imported.')