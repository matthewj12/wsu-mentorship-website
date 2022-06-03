import mysql.connector
import pprint


class Participant():
	columns = [
		'first name',
		'last name',
		'starid',
		'gender',
		'major',
		'pre program',
		'second language',
		'race',
		'preferred gender',
		'religious affiliation',
		'1st most important quality',
		'2nd most important quality',
		'3rd most important quality',
		'international student',
		'lgbtq+',
		'student athlete',
		'multilingual',
		'not born in this country',
		'transfer student',
		'first gen college student',
		'unsure or undecided about major',
		'interested in diversity groups',
		'misc info',
		# hobbbies is an aggregate data point. A seperate `hobbies` table references the `participant` table
		'hobbies'
	]

	def __init__(self, row):
		# atomized data points withing `participant` table
		self.data_points = {}

		self.ranking = []

		i = 0
		for data_point_val in row.split(',', 22):
			self.data_points[Participant.columns[i]] = data_point_val
			i += 1



def createCursor(host, user, password, database):
	mpdb = mysql.connector.connect(
		host=host,
		user=user,
		password=password,
		database=database
	)

	return mpdb, mpdb.cursor(buffered=True)

def joinTuple(tup):
	res = ''

	for val in tup:
		res += str(val) + ','

	return res

def buildParticipantsListFromQuery(cursor, where_clause_condition):
	cursor.execute('select * from participant where {};'.format(where_clause_condition))

	# Populate atomized data points
	results = []
	for tup in cursor:
		p = Participant(joinTuple(tup))
		results.append(p)

	# Populate aggregate data points (utilizing seperate tables, each referencing the `participant` table)
	cursor.execute("select hobby from `has hobby` where starid = '{}'"
		.format(results[-1].data_points['starid'])
	)
	results[-1].data_points['hobbies'] = joinTuple(tup[0]).split(',')

	return results

# mapping of Important Quality Specifier to Participant Data Point Key
iqs_to_pdpk = {
	'major and pre-professional program (if applicable)' : 'major', # TODO: incorporate pre major
	'race' : 'race',
	'language(s) in common' : 'second language', # TODO: make separate table for second language (so they can have more than one)
	'hobbies in common' : 'hobbies',
	'interested in diversity groups' : 'interested in diversity groups',
	'religious affiliation' : 'religious affiliation'
}

# All unlisted pairings share last place.
rankings = {
	'major and pre-professional program (if applicable)' : {
		'chemistry'                                  : tuple(['chemistry',
		        									   'biology (allied health or cell molecular])',
													   'biology (medical lab science])', 
													   'biology (radiography])', 
													   'geoscience'
													  ]),
		'geoscience'                                 : tuple(['geoscience',
													   'chemistry', 
													   'biology (radiography])', 
													   'biology (allied health or cell molecular])', 
													   'biology (medical lab science])'
													  ]),
		'math'                                       : tuple(['math',
													   'statistics', 
													   'data science', 
													   'computer science', 
													   'general engineering', 
													   'composite material engineering', 
													   'physics'
													  ]),
		'data science'                               : tuple(['data science',
													   'computer science', 
													   'statistics', 
													   'math', 
													   'physics'
													  ]),
		'undecided'                                 : tuple(['undecided'
													#  economics, lol
													  ]),
		'biology (allied health or cell molecular])'  : tuple(['biology (allied health or cell molecular])',
													   'biology (medical lab science])',
													   'biology (ecology or environmental science])',
													   'biology (radiography])',
													   'chemistry'
													  ]),
		'biology (medical lab science])'              : tuple(['biology (medical lab science])',
													   'biology (allied health or cell molecular])',
													   'biology (radiography])',
													   'biology (ecology or environmental science])',
													   'chemistry'
													  ]),

		'biology (ecology or environmental science])' : tuple(['biology (ecology or environmental science])',
													   'biology (allied health or cell molecular])',
													   'biology (medical lab science])',
													   'biology (radiography])',
													   'chemistry'
		                                               ]),
		'general engineering'                        : tuple(['general engineering',
													   'composite materials engineering',
													   'physics',
													   'math'
													  ]),
		'computer science'                           : tuple(['computer science',
													   'data science',
													   'math',
													   'statistics'
													  ]),
		'statistics'                                 : tuple(['statistics',
													   'math',
													   'data science',
													   'computer science'
													  ]),
		'composite materials engineering'            : tuple(['composite materials engineering',
													   'general engineering',
													   'chemistry',
													   'physics',
													   'math'
													  ]),
		'biology (radiography])'                      : tuple(['biology (radiography])',
													   'biology (medical lab science])',
													   'biology (allied health or cell molecular])',
													   'chemistry'
													  ]),
													
		'physics'                                    : tuple(['physics',
													   'general engineering',
													   'composite materials engineering',
													   'math'
													  ])
	},
	'religious affiliation' : {
		'christianity'                            : tuple(['christianity']),
		'judaism'                                 : tuple(['judaism']),
		'islam'                                   : tuple(['islam']),
		'buddhism'                                : tuple(['buddhism']),
		'hinduism'                                : tuple(['hinduism']),
		'taoism'                                  : tuple(['taoism']),
		'spiritual (no specific group alignment])' : tuple(['spiritual (no specific group alignment])']),
		'atheist'                                 : tuple(['atheist']),
		'pastafarian'                             : tuple(['pastafarian']),
		'agnostic'                                : tuple(['agnostic']),
		'other'                                   : tuple(['other'])
	},
	'race' : {
		'white'                               : tuple(['white']),
		'black'                               : tuple(['black']),
		'aboriginal'                          : tuple(['aboriginal']),
		'native american'                     : tuple(['native american']),
		'native hawaiian or pacific islander' : tuple(['native hawaiian or pacific islander']),
		'asian'                               : tuple(['asian']),
		'hispanic'                            : tuple(['hispanic']),
		'other'                               : tuple(['other']),
		'prefer not to answer'                : tuple(['prefer not to answer']),
		'mixed'                               : tuple(['mixed'])
	},
	'interested in diversity groups' : {
		'1' : tuple(['1']),
		'0' : tuple(['0'])
	},
	'hobbies in common' : {
		'1' : tuple(['1']),
		'0' : tuple(['0'])
	},
	'language(s) in common' : {
		'american sign language' : tuple(['american sign language']),
		'arabic'                 : tuple(['arabic']),
		'bangla'                 : tuple(['bangla']),
		'chinese'                : tuple(['chinese']),
		'french '                : tuple(['french ']),
		'german'                 : tuple(['german']),
		'hindi/urdu'             : tuple(['hindi/urdu']),
		'japanese'               : tuple(['japanese']),
		'korean'                 : tuple(['korean']),
		'russian'                : tuple(['russian']),
		'somali'                 : tuple(['somali']),
		'spanish'                : tuple(['spanish']),
		'thai'                   : tuple(['thai']),
		'vietnamese'             : tuple(['vietnamese']),
		'other'                  : tuple(['other']),
		'none'                   : tuple(['none'])
	}
}

# returns subsection of 'order by' clause from 
#     iqs   = Important Quality Specifier (is the outer key in 'rankings' dict)
# 	  iqv   = Important Quality Value belonging to participant whose ranking of candidate
# 			  mentors/mentees is being created (is a value in 'rankings' dict)
# 	  iqv_c = value being Compared to iqv
def generateOrderByExpr(cursor, participant, iqs):
	if iqs != 'hobbies in common':
		cur_rank = 1

		iqv = participant.data_points[iqs_to_pdpk[iqs]]

		# print(rankings[iqs])
		# print(rankings[iqs][iqv])
		# order by case...
		result = 'case'

		for i in range(len(rankings[iqs][iqv])):
			iqv_c = rankings[iqs][iqv][i]

			# if participant.data_points['starid'] == 'tg4511pn':
			# 	print('iqv_c = ' + iqv_c)

			result += " when `{}` = '{}' then {}".format(
				iqs_to_pdpk[iqs], 
				iqv_c, 
				cur_rank
			)
			cur_rank += 1
		
		return result + ' when TRUE then {} end asc'.format(cur_rank + 1)

	else:
		# order by ... 
		return '`matching hobbies count` desc'

def generateRankings(cursor, participants, debug_participant=None):
	for p in participants:
		count_matching_hobbies_query = '''
			with
				p1_hobby as (select * from `has hobby` where starid = '{}'),
				p2_hobby as (select * from `has hobby`)
			select p2_hobby.starid as `candidate starid`, count(*) as `matching hobbies count`
			from p1_hobby, p2_hobby
			where p1_hobby.hobby = p2_hobby.hobby
			group by p2_hobby.starid
		'''.format(
			p.data_points['starid']
		)

		custom_order_1 = generateOrderByExpr(cursor, p, p.data_points['1st most important quality'])
		custom_order_2 = generateOrderByExpr(cursor, p, p.data_points['2nd most important quality'])
		custom_order_3 = generateOrderByExpr(cursor, p, p.data_points['3rd most important quality'])

		# s = 'select `first name`, `major`, `Faith/Religion` from participant order by case when major = \'chemistry\' then 1 when major = \'statistics\' then 2 else 3 end asc, case when `Faith/Religion` = \'christianity\' then 1 when `Faith/Religion` = \'taoism\' then 2 else 3 end asc;'

		# s = 'select `{}`, `{}`, `{}`, `{}` from participant order by case {}, case {}, case {};'.format(
		# 	'warrior id',
		# 	p.most_important_quality_1st,
		# 	p.most_important_quality_2nd,
		# 	p.most_important_quality_3rd,
		# 	custom_order_1, 
		# 	custom_order_2, 
		# 	custom_order_3)


		join_with_matching_hobbies_clause = '''
			participant left join `matching hobbies table` 
				on participant.starid = `matching hobbies table`.`candidate starid`
		'''

		candidate_ranking_query = '''
			with `matching hobbies table` as ({})
			select starid
			from {}
			order by {}, {}, {};'''.format(
				count_matching_hobbies_query,
				join_with_matching_hobbies_clause,
				custom_order_1, 
				custom_order_2, 
				custom_order_3
			)

		cursor.execute(candidate_ranking_query)

		if p.data_points['starid'] == debug_participant_id:
			print('query: ' + candidate_ranking_query)

		i = 0
		for tup in cursor:
			p.ranking.append(tup[0])
			i += 1




# mpdb = "mentorship program database"
mpdb, cursor = createCursor('localhost', 'root', '', 'mp')
participants = buildParticipantsListFromQuery(cursor, where_clause_condition='TRUE')

# _________________________________________________________________________________________

debug_participant_id = 'qn5161nm'
debug_participant_index = None

for i in range(len(participants)):
	if participants[i].data_points['starid'] == debug_participant_id:
		debug_participant_index = i
# _________________________________________________________________________________________



generateRankings(cursor, participants, debug_participant_id)



print("{}'s rankings:".format(participants[debug_participant_index].data_points['starid']))
rank = 1
for starid in participants[debug_participant_index].ranking:
	print('{}: {}'.format(rank, starid))
	rank += 1
