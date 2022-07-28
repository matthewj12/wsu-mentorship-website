mockaroo_base_url = "https://api.mockaroo.com/api/"

urls = {
	'participant' : 'b7266940?count=6',
	
	'gender'                : '7024c370?count=6',
	'hobby'                 : '4ba241f0?count=24',
	'primary major'         : 'ca372f90?count=9',
	'secondary major'       : '641893e0?count=2',
	'primary pre program'   : '92987040?count=4',
	'secondary pre program' : '8c177de0?count=2',
	'race'                  : 'e78c2170?count=6',
	'religious affiliation' : '9be133f0?count=6',
	'second language'       : '6b95d550?count=4',

	'important quality' : '2157a8c0?count=18',
	'max matches'       : '7d771050?count=6',

	'preferred gender'                : 'c0420be0?count=12',
	'preferred hobby'                 : 'eae20460?count=4',
	'preferred primary major'         : '3735e520?count=18',
	'preferred secondary major'       : 'cdc61170?count=4',
	'preferred primary pre program'   : '6dda7f50?count=4',
	'preferred secondary pre program' : '00a3ad80?count=4',
	'preferred race'                  : '77ea45e0?count=12',
	'preferred religious affiliation' : '2fb733b0?count=12',
	'preferred second language'       : '4d7ce400?count=8'
}

mockaroo_key = "1d04b730"

# suffix = 'AssocTbl' if distinct != 'participant' else ''
# curl = f"curl \"{mockaroo_base_url}{urls[distinct]}&key={mockaroo_key}\" > \"{toCamelCase(distinct)}{suffix}.csv\"\n"
