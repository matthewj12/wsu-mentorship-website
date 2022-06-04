from Rankings import rankings

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
		# hobbbies is an aggregate data point. A seperate `hobbies` table references the `participant` table
		# Currently, we determine matching hobbies via query without storing them. Ideally, this would/will also be the case for the rest of the fields
		# 'hobbies'
	]


	def __init__(self, row):
		# atomized data points within `participant` table
		self.data_points = {}

		self.ranking = []

		i = 0
		for data_point_val in row.split(',', 24):
			self.data_points[Participant.columns[i]] = data_point_val
			i += 1


	# mapping of Important Quality Specifier to Participant Data Point Key
	iqs_to_pdpk = {
		'prefers not to answer' : 'prefer not to answer'
	}
	@staticmethod
	def iqsToPdpk(iqs):
		if iqs in Participant.iqs_to_pdpk.keys():
			return Participant.iqs_to_pdpk[iqs] 
		else:
			# If an entry for an iqs doesn't exist, just use iqs as the key.
			return iqs


	def generateOrderByExpr(self, cursor, iqs, debug):
		# returns subsection of 'order by' clause from 
		# iqs   = Important Quality Specifier (is the outer key in 'rankings' dict)
		if iqs != 'hobbies':
			cur_rank = 1

			# important quality key
			iqk = Participant.iqsToPdpk(iqs)
			# iqv   = Important Quality Value belonging to participant whose ranking of candidate
			# mentors/mentees is being created (is a value in 'rankings' dict)
			iqv = self.data_points[iqk]

			if debug:
				print('rankings[{}][{}]'.format(iqk, iqv))

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

		else:
			# order by ... 
			return '`matching hobbies count` desc'


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

		custom_order_exprs = [
			self.generateOrderByExpr(
				cursor,
				self.data_points['{} most important quality'.format(['1st', '2nd', '3rd'][i])],
				debug
			) for i in range(3)
		]

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
				*custom_order_exprs
			)

		cursor.execute(candidate_ranking_query)

		# if debug_participant_id != None and p.data_points['starid'] == debug_participant_id:
		# 	print('\nquery: {}\n'.format(candidate_ranking_query))

		i = 0
		for tup in cursor:
			self.ranking.append(tup[0])
			i += 1