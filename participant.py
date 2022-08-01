import rankings, miscellaneousfunctions, globalvariables

class Participant():
	# columns = miscellaneousfunctions.getParticipantColumns()

	# We need these explicity defined here (as opposed to fetched via query) because we need them in the order they will appear in the survey.
	columns = [
		'is active',
		'is mentor',
		'first name',
		'last name',
		'starid',
		'international student',
		'lgbtq+',
		'student athlete',
		'multilingual',
		'not born in this country',
		'transfer student',
		'first generation college student',
		'unsure or undecided about major',
		'interested in diversity groups',
		'misc info'
	]


	def __init__(self, row=None):
		if row != None:
			# atomized data points within `participant` table
			self.data_points = {}
			self.ranking = []
			# the index in 'ranking' of the participant we will propose to next (if we need too)
			self.next_proposal_index = 0

			i = 0
			for data_point_val in row:
				self.data_points[Participant.columns[i]] = data_point_val
				i += 1


	# for testing purposed
	def debugConstructor(self, is_active, is_mentor, starid, max_matches, ranking):
		self.data_points = {}

		self.data_points['is mentor'] = is_mentor
		self.data_points['starid'] = starid
		self.data_points['is active'] = is_active
		self.data_points['max matches'] = max_matches
		self.ranking = ranking

		self.next_proposal_index = 0


	def printRanking(self, base_indent):
		tabs = ''.join(['\t' for indent in range(base_indent)])
		
		print(tabs + 'Rankings:')
		
		rank = 1
		for starid in self.ranking:
			print(tabs + '\t{}: {}'.format(rank, starid))
			rank += 1

		print()


	# returns starid of current matched participant who is inferior to candidate or None if none are
	def getInferiorMatch(self, candidate, matches):
		self_index  = 0 if self.data_points['is mentor'] else 1
		match_index = 1 if self.data_points['is mentor'] else 0

		for match in matches:
			# if this match involves us and the participant it matches us with is inferior to candidate
			if match[self_index] == self.data_points['starid'] and self.ranking.index(match[match_index]) < self.ranking.index(candidate.data_points['starid']):
				return match[match_index]
			
		return None

	# rankable non-reference-table data points
	rankable_non_ref_tbl_dp = [
		'international student',
		'lgbtq+',
		'student athlete',
		'multilingual',
		'not born in this country',
		'transfer student',
		'first generation college student',
		'unsure or undecided about major',
		'interested in diversity groups'
	]


	def getImportantQualitiesList(self, cursor):
		query = (
			'select `important quality id`\n'
			'from `important quality assoc tbl`\n'
			f"where `starid` = '{self.data_points['starid']}'\n"
			'order by `important quality rank` asc;\n'
		)

		cursor.execute(query)

		important_quality_ids = []
		for row in cursor:
			important_quality_ids.append(row[0])

		important_qualities = []

		for iq_id in important_quality_ids:
			query = (
				"select `important quality`\n"
				"from `important quality ref tbl`\n"
				f"where `id` = '{iq_id};'\n"
			)

			cursor.execute(query)

			for tup in cursor:
				important_qualities.append(tup[0])

		while 'unused' in important_qualities:
			important_qualities.remove('unused')

		return important_qualities

			
	# returns a query that will generate a table with:
	# column 1) starid of every other participant that is in the opposite group to self (for example, if self is mentor, only query mentees)
	# column 2) The number of X in common self has with the participant with that starid
	def generateCountMatchingXQuery(self, x, base_indent_str):
		bi = base_indent_str
		starid = self.data_points['starid']
		
		if x in  self.rankable_non_ref_tbl_dp:
			# count(*) will evaluate to either 0 or 1
			return (
				f"{bi}with\n"
				f"{bi}\t`p1 {x}` as (select `starid`, `{x}` from `participant` where `participant`.`starid` = '{starid}'),\n"
				f"{bi}\t`p2 {x}` as (select `starid`, `{x}` from `participant`)\n"
				f"{bi}select `p2 {x}`.`starid` as `candidate starid`, count(*) as `matching {x} count`\n"
				f"{bi}from `p1 {x}`, `p2 {x}`\n"
				f"{bi}where `p1 {x}`.`{x}` = `p2 {x}`.`{x}`\n"
				f"{bi}group by `p2 {x}`.`starid`\n"
			)
		else:
			return (
				f"{bi}with\n"
				f"{bi}\t`p1 {x}` as (select * from `{x} assoc tbl` where `starid` = '{starid}'),\n"
				f"{bi}\t`p2 {x}` as (select * from `{x} assoc tbl`)\n"
				f"{bi}select `p2 {x}`.`starid` as `candidate starid`, count(*) as `matching {x} count`\n"
				f"{bi}from `p1 {x}`, `p2 {x}`\n"
				f"{bi}where `p1 {x}`.`{x} id` = `p2 {x}`.`{x} id`\n"
				f"{bi}group by `p2 {x}`.`starid`\n"
			)
	
		pass
		# So VSCode will not exclude the ")" from the function's automatic fold


	def generateCandidateRankingQuery(self, as_statements, join_clause, custom_order_exprs):
		return (
			f"WITH\n"
			f"\t`available participants tbl` AS (\n"
			f"{globalvariables.get_available_participants_query}),\n"
			f"{''.join(as_statements)[:-2]}\n"
			f"SELECT `starid`\n"
			f"FROM {join_clause}"
			f"WHERE `is mentor` = {'FALSE' if self.data_points['is mentor'] else 'TRUE'}\n"
			f"ORDER BY {''.join(custom_order_exprs)[:-2]};\n" # [-2] removes the last comma + space
		)
	

	def generateRanking(self, cursor, am_debug_participant):	
		important_qualities = self.getImportantQualitiesList(cursor)

		doubleTab = '\t\t'
		as_statements = [
			f"\t`matching {iqs} tbl` AS (\n"
			f"{self.generateCountMatchingXQuery(iqs, doubleTab)}\t),\n" for iqs in important_qualities
		# So VSCode will not exclude the ")" from the function's automatic fold
		]

		join_clause = '`available participants tbl`'
		for iqs in important_qualities:
			join_clause += (
				f"\n\tLEFT JOIN `matching {iqs} tbl`\n"
				f"\t\tON `available participants tbl`.`starid` = `matching {iqs} tbl`.`candidate starid`"
			)

		if am_debug_participant:
			print("Generating {}'s ranking...".format(self.data_points['starid']))
			for iq in important_qualities:
				print(f'\tConsidering: {iqs}...')
			print()

		custom_order_exprs = [f"`matching {iqs} count` desc, " for iqs in important_qualities]
		
		cursor.execute(self.generateCandidateRankingQuery(as_statements, join_clause, custom_order_exprs))

		# if am_debug_participant:
		# 	print('\nquery: {}\n'.format(candidate_ranking_query))

		for tup in cursor:
			self.ranking.append(tup[0])

		if am_debug_participant:
			self.printRanking(base_indent=0)
