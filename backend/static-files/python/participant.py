import rankings, miscfuncs, globvars

class Participant():
	columns = miscfuncs.getParticipantColumns()

	def __init__(self, starid=None, cursor=None):
		if starid != None and cursor != None:
			self.data_points = {}
			self.ranking = []
			# the index in 'ranking' of the participant we will propose to next (if we need too)
			self.next_proposal_index = 0

			for col in self.columns:
				sql_query = f"SELECT `{col}` FROM `participant` WHERE `participant`.`starid` = '{starid}'"

				cursor.execute(sql_query)

				col_val = ''
				for tup in cursor:
					col_val = str(tup[0])
				
				self.data_points[col] = col_val

			non_participant_cols = ['religious affiliation', 'major']

			for col in non_participant_cols:
				sql_query = f"SELECT `{col} id` FROM `{col} assoc tbl` WHERE `{col} assoc tbl`.`starid` = '{starid}'"

				cursor.execute(sql_query)

				col_val = []
				for tup in cursor:
					col_val.append(str(tup[0]))
				
				self.data_points[col] = col_val


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
		x_no_spaces = x.replace(' ', '_')
		
		if x in self.rankable_non_ref_tbl_dp:
			# count(*) will evaluate to either 0 or 1
			return (
				f"{bi}with\n"
				f"{bi}\t`p1_{x_no_spaces}` as (select `starid`, `{x}` from `participant` where `participant`.`starid` = '{starid}'),\n"
				f"{bi}\t`p2_{x_no_spaces}` as (select `starid`, `{x}` from `participant`)\n"
				f"{bi}select `p2_{x_no_spaces}`.`starid` as `candidate starid`, count(*) as `matching_{x_no_spaces}_count`\n"
				f"{bi}from `p1_{x_no_spaces}`, `p2_{x_no_spaces}`\n"
				f"{bi}group by `p2_{x_no_spaces}`.`starid`\n"
			)
		if x in ('religious affiliation'):
			# rankings.py
			# this participant's own value for this data point
			x_value = miscfuncs.idToName(self.data_points[x][0], x, cursor)
			cases = rankings.rankings[x][x_value]

			# the column name "matching ___ count" doesn't really make sense when we're summing the score based on the rankings of the value(s) a participant has for the relevant columns, but we're keeping it for simplicities sake so there's only one column to order by
			case_expr = 'case '
			i = len(cases) # higher = better to be consistent with when we're just counting matching values and sorting by descending
			for r in cases:
				id = miscfuncs.nameToId(r, x, cursor)
				case_expr += f"when `p2_{x_no_spaces}`.`{x} id` = '{id}' then {i} "
				i -= 1

			case_expr += 'else 0 '

			case_expr += f"end"

			return (
				f"{bi}with\n"
				f"{bi}\t`p1_{x_no_spaces}` as (select * from `{x} assoc tbl` where `starid` = '{starid}'),\n"
				f"{bi}\t`p2_{x_no_spaces}` as (select * from `{x} assoc tbl`)\n"
				f"{bi}select `p2_{x_no_spaces}`.`starid` as `candidate starid`, sum({case_expr}) as `matching_{x_no_spaces}_count`\n"
				f"{bi}from `p1_{x_no_spaces}`, `p2_{x_no_spaces}`\n"
				f"{bi}where `p1_{x_no_spaces}`.`{x} id` = `p2_{x_no_spaces}`.`{x} id`\n"
				f"{bi}group by `p2_{x_no_spaces}`.`starid`\n"
			)
		else:
			return (
				f"{bi}with\n"
				f"{bi}\t`p1_{x}` as (select * from `{x} assoc tbl` where `starid` = '{starid}'),\n"
				f"{bi}\t`p2_{x}` as (select * from `{x} assoc tbl`)\n"
				f"{bi}select `p2_{x}`.`starid` as `candidate starid`, count(*) as `matching {x} count`\n"
				f"{bi}from `p1_{x}`, `p2_{x}`\n"
				f"{bi}where `p1_{x}`.`{x} id` = `p2_{x}`.`{x} id`\n"
				f"{bi}group by `p2_{x}`.`starid`\n"
			)
	

	def generateCandidateRankingQuery(self, as_statements, custom_select_as_exprs, join_clause, custom_order_exprs):
		return (
			f"WITH\n"
			f"\t`available participants tbl` AS (\n"
			f"{globvars.get_available_participants_query}),\n"
			f"{''.join(as_statements)[:-2]}\n"
			f"SELECT `starid`, {custom_select_as_exprs}\n"
			f"FROM {join_clause}\n"
			f"WHERE `is mentor` = {'FALSE' if int(self.data_points['is mentor']) else 'TRUE'}\n"
			f"ORDER BY {''.join(custom_order_exprs)[:-2]};\n" # [-2] removes the last comma + space
		)
	

	def generateRanking(self, cursor, am_debug_participant):	
		important_qualities = self.getImportantQualitiesList(cursor)

		doubleTab = '\t\t'
		as_statements = [
			f"\t`matching {iqs} tbl` AS (\n"
			f"{self.generateCountMatchingXQuery(iqs, doubleTab)}\t),\n" for iqs in important_qualities
		]

		t = self.generateCountMatchingXQuery('religious affiliation', doubleTab)

		join_clause = '`available participants tbl`'
		for iqs in important_qualities:
			join_clause += (
				f"\n\tLEFT JOIN `matching {iqs} tbl`\n"
				f"\t\tON `available participants tbl`.`starid` = `matching {iqs} tbl`.`candidate starid`"
			)

		if am_debug_participant:
			# print("Generating {}'s ranking...".format(self.data_points['starid']))
			for iq in important_qualities:
				print(f'\tConsidering: {iqs}...')
			print()

		custom_order_exprs = [f"`matching {iqs} count` desc, " for iqs in important_qualities]
		custom_select_as_exprs = ''.join([f"IFNULL(`matching {iqs} count`, 0) as `matching {iqs} count`, " for iqs in important_qualities])[:-2]

		query = self.generateCandidateRankingQuery(as_statements, custom_select_as_exprs, join_clause, custom_order_exprs)

		with open('temp.sql', 'w') as temp_file:
			temp_file.write(query)
		
		cursor.execute(query)

		# if am_debug_participant:
		# 	print('\nquery: {}\n'.format(candidate_ranking_query))

		for tup in cursor:
			self.ranking.append(tup[0])

		# Append all the rest of the participants with 0 matches at last place
		all_available = []
		cursor.execute(globvars.get_available_participant_starids_query)
		for tup in cursor:
			all_available.append(tup[0])

		if am_debug_participant:
			self.printRanking(base_indent=1)


if __name__ == '__main__':
	db, cursor = miscfuncs.createCursor()
	p = Participant('aaa', cursor)
	p.generateRanking(cursor, 1)
	print(p.ranking)
	