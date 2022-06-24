from Rankings import rankings

class Participant():
	columns = [
		'is active',
		'is mentor',
		'first name',
		'last name',
		'starid',
		'gender',
		'major',
		'pre program',
		'religious affiliation',
		'international student',
		'lgbtq+',
		'student athlete',
		'multilingual',
		'not born in this country',
		'transfer student',
		'first gen college student',
		'unsure or undecided about major',
		'interested in diversity groups',
		'preferred gender',
		'preferred race',
		'preferred religious affiliation',
		'1st most important quality',
		'2nd most important quality',
		'3rd most important quality',
		'misc info'
	]
		
	'''
	hobbbies is an aggregate data point. A seperate `hobbies` table references the `participant` table
	Currently, we get a participant's list of hobbies via query without storing them. This eliminates any chance of using out-of-date data. We could 
	handle all data points this way, but it seems excessively strict and slow to re-query the participant being processed every time we need another 
	data point; we can easily update all records all records are up-to-date in linear time by calling 'getParticipantsListFromQuery()'.
	'''
		
	def __init__(self, row):
		# atomized data points within `participant` table
		self.data_points = {}
		self.ranking = []

		i = 0
		for data_point_val in row:
			self.data_points[Participant.columns[i]] = data_point_val
			i += 1


	# mapping of Important Quality Specifier to Participant Data Point Key
	iqs_to_pdpk = {
		# 'second language(s)' : 'second language'
	}

	@staticmethod
	def iqsToPdpk(iqs):
		if iqs in Participant.iqs_to_pdpk.keys():
			return Participant.iqs_to_pdpk[iqs] 
		else:
			# If an entry for an iqs doesn't exist, just use iqs as the key.
			return iqs

	
	# thing that p chose as one of their important qualities : column name to get p's value used in comparison with candidates
	iqs_to_col_name = {
		'gender'   : 'preferred gender',
		'religion' : 'preferred religion',
		'race'     : 'preferred race',
	}

	@staticmethod
	def iqsToColName(iqs):
		if iqs in Participant.iqs_to_col_name.keys():
			return Participant.iqs_to_col_name[iqs] 
		else:
			# If an entry for an iqs doesn't exist, just use iqs as the key.
			return iqs

	def generateOrderByExpr(self, cursor, iqs, debug):
		# returns subsection of 'order by' clause from 
		# iqs   = Important Quality Specifier (is the outer key in 'rankings' dict)
		if iqs == 'hobbies':
			if debug:
				print('Considering: hobbies...')
			# order by ... 
			return '`matching hobbies count` desc'

		elif iqs == 'second language':
			if debug:
				print('Considering: second languages...')

			return '`matching second languages count` desc'

		elif iqs == 'race':
			if debug:
				print('Considering: race...')

			return '`matching races count` desc'

		else:
			cur_rank = 1

			# important quality key
			iqk = Participant.iqsToPdpk(iqs)

			# iqv   = Important Quality Value belonging to participant (self) whose ranking 
			# of candidate mentors/mentees is being created (is a value in 'rankings' dict)
			iqv = self.data_points[Participant.iqsToColName(iqs)]

			if debug:
				# print(self.data_points['starid'])
				print('Considering: rankings[{}][{}]...'.format(iqk, iqv))

			# order by case...
			result = 'case'

			# candidate rankings
			cand_rankings = rankings[iqk][iqv]

			for i in range(len(cand_rankings)):
				# if debug_participant_id != None and participant.data_points['starid'] == debug_participant_id:
				# 	print('important quality candidate value = ' + cand_rankings[i])

				result += " when `{}` = '{}' then {}".format(
					iqk,
					cand_rankings[i],
					cur_rank
				)
				cur_rank += 1
			
			return result + ' when TRUE then {} end asc'.format(cur_rank + 1)

	def generateRanking(self, cursor, participants, debug):
		count_matching_hobbies_query = '''
			with
				p1_hobby as (select * from `has hobby` where starid = '{}'),
				p2_hobby as (select * from `has hobby`)
			select p2_hobby.starid as `candidate starid`, count(*) as `matching hobbies count`
			from p1_hobby, p2_hobby
			where p1_hobby.hobby = p2_hobby.hobby
			group by p2_hobby.starid
		'''.format(
			self.data_points['starid']
		)
		
		count_matching_second_languages_query = '''
			with
				p1_lang as (select * from `speaks second language` where starid = '{}'),
				p2_lang as (select * from `speaks second language`)
			select p2_lang.starid as `candidate starid`, count(*) as `matching second languages count`
			from p1_lang, p2_lang
			where p1_lang.`second language` = p2_lang.`second language`
			group by p2_lang.starid
		'''.format(
			self.data_points['starid']
		)

		count_matching_races_query = '''
			with
				p1_race as (select * from `is race` where starid = '{}'),
				p2_race as (select * from `is race`)
			select p2_race.starid as `candidate starid`, count(*) as `matching races count`
			from p1_race, p2_race
			where p1_race.`race` = p2_race.`race`
			group by p2_race.starid
		'''.format(
			self.data_points['starid']
		)

		custom_order_exprs = [
			self.generateOrderByExpr(
				cursor,
				self.data_points['{} most important quality'.format(['1st', '2nd', '3rd'][i])],
				debug
			) for i in range(3)
		]

		# need to generate the table at the start of the query so we can reference it in the ORDER BY clause
		join_clause = '''
			((participant LEFT JOIN `matching second languages table` ON participant.starid = `matching second languages table`.`candidate starid`)
			             LEFT JOIN `matching hobbies table` ON participant.starid = `matching hobbies table`.`candidate starid`)
			             LEFT JOIN `matching races table` ON participant.starid = `matching races table`.`candidate starid`
		'''

		candidate_ranking_query = '''
			WITH 
				`matching hobbies table` AS ({}),
				`matching races table` AS ({}),
				`matching second languages table` AS ({})
			SELECT starid
			FROM {}
			WHERE `is mentor` = {}
			ORDER BY {}, {}, {};'''.format(
				count_matching_hobbies_query,
				count_matching_races_query,
				count_matching_second_languages_query,
				join_clause,
				'FALSE' if self.data_points['is mentor'] else 'TRUE',
				*custom_order_exprs
			)

		cursor.execute(candidate_ranking_query)

		# if debug_participant_id != None and p.data_points['starid'] == debug_participant_id:
		# 	print('\nquery: {}\n'.format(candidate_ranking_query))

		i = 0
		for tup in cursor:
			self.ranking.append(tup[0])
			i += 1