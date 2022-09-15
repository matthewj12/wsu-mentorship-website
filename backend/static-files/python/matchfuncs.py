import participant, globvars
import matching.games.hospital_resident
import datetime


# copies all rows from the participant-related SQL tables into Participant objects. The only table this currently doesn't include is `mentorship`
def buildParticipantsListFromQuery(cursor):
	cursor.execute(globvars.get_available_participant_starids_query)

	# Populate atomized data points
	participants = []
	starids = []
	for tup in cursor:
		starids.append(tup[0])

	for starid in starids:
		participants.append(participant.Participant(starid, cursor))

	return participants


def getParticipantByStarid(participants, starid):
	for p in participants:
		if p.data_points['starid'] == starid:
			return p


def countMatches(participant, matches):
	count = 0

	for match in matches:
		if match[0] == participant.data_points['starid'] or match[1] == participant.data_points['starid']:
			count += 1

	return count


def printMatches(message, matches, base_indent=0):
	print(message)

	tabs = ''.join(['\t' for indent in range(base_indent)])
	
	for match in matches:
		print(f"{tabs}{match[0]} matched with {match[1]}")

	print()


def isStillMatchable(participant, matches):
	return countMatches(participant, matches) < int(participant.data_points['max matches'])

# not done with *first stage* (the "normal" gale-shapely algorithm)
# returns false if all matches are stable (doesn't mean that everyone has a match or that we can't increase the number of matches by re-matching mentees paired with mentors with multiple mentees)
def notDone(mentees, matches):
	for mentee in mentees:
		# if mentee isn't matched yet but hasn't "proposed" to every mentor yet
		if isStillMatchable(mentee, matches) and mentee.next_proposal_index < len(mentee.ranking):
			return True


def alreadyPaired(cursor, p):
	cursor.execute('''
		SELECT *
		FROM `mentorship`
		WHERE `mentee starid` = '{}' OR `mentor starid` = '{}'
	'''.format(p.data_points['starid'], p.data_points['starid'])
	)

	res_count = 0
	for tup in cursor:
		res_count += 1

	return res_count > 0


def getStaridsOfTopRankers(mentees_for_um_rankings, um):
	top_rankers = [mentees_for_um_rankings[um][0].data_points['starid']]

	for i in range(1, len(mentees_for_um_rankings[um])):
		# if they tie with the mentee "above" (lower index) them
		if mentees_for_um_rankings[um][i].ranking.index(um) == mentees_for_um_rankings[um][i-1].ranking.index(um):
			top_rankers.append(mentees_for_um_rankings[um][i].data_points['starid'])

	return top_rankers


def allTopRankersAreExclusive(mentees_for_um_rankings, um):
	# for each (tied) top ranker
	for top_ranker in getStaridsOfTopRankers(mentees_for_um_rankings, um):
		# check if they are the top ranker for another unmatched mentor
		for um_iterate in mentees_for_um_rankings.keys():
			if um_iterate != um and top_ranker in getStaridsOfTopRankers(mentees_for_um_rankings, um_iterate): # top_ranker is not top ranker for um_iterate:
				return False

	return True


def getMatchByMenteeStarid(matches, mentee_starid):
	for match in matches:
		if match[1] == mentee_starid:
			return match


def rematch(matches, mentor_starid, mentee_starid):
	if globvars.debugging_on:
		print('removing: ({}, {})'.format(getMatchByMenteeStarid(matches, mentee_starid)[0], mentee_starid))
		print('adding: ({}, {})'.format(mentor_starid, mentee_starid))

	matches.remove((getMatchByMenteeStarid(matches, mentee_starid)[0], mentee_starid))
	matches.append((mentor_starid, mentee_starid))


def updateMenteesForUmRankings(mentees_for_um_rankings, matched_mentor_starid, matched_mentee_starid, participants, matches):
	# remove dictionary element for mentor
	key_to_remove = 0

	for um in mentees_for_um_rankings.keys():
		if um == matched_mentor_starid:
			key_to_remove = um
			break

	mentees_for_um_rankings.pop(key_to_remove)

	# remove mentees from each of the rest of the dictionary elements
	mentees_for_um_rankings_keys = mentees_for_um_rankings.keys()
	for um in mentees_for_um_rankings_keys:
		remove_index = 0

		for i in range(len(mentees_for_um_rankings[um])):
			if mentees_for_um_rankings[um][i].data_points['starid'] == matched_mentee_starid:
				remove_index = i
				break

		mentees_for_um_rankings[um].remove(mentees_for_um_rankings[um][remove_index])

	# remove mentees who are now no longer "extra" (they're the only mentor paired with the mentor that they're paired with)
	mentees_for_um_rankings_keys = mentees_for_um_rankings.keys()
	for um in mentees_for_um_rankings_keys:
		remove_index = 0

		for i in range(len(mentees_for_um_rankings[um])):
			if countMatches(getParticipantByStarid(participants, mentees_for_um_rankings[um][i].data_points['starid']), matches) == 1:
				remove_index = i
				break

		mentees_for_um_rankings[um].remove(mentees_for_um_rankings[um][remove_index])
	
	return mentees_for_um_rankings


def createMatches(cursor, participants):
	# _______________________ Do Hospital Resident Assignment Algorithm ________________________

	mentee_prefs = {p.data_points['starid'] : p.ranking for p in participants if 
		int(p.data_points['is active']) and not alreadyPaired(cursor, p) and not int(p.data_points['is mentor'])}

	mentor_prefs = {p.data_points['starid'] : p.ranking for p in participants if 
		int(p.data_points['is active']) and not alreadyPaired(cursor, p) and int(p.data_points['is mentor'])}

	# print('before:')
	# print(mentor_prefs)
	# print(mentee_prefs)

	# Remove entries corresponding to participants who aren't currently being considered for matching.
	for mentor_key in mentor_prefs.keys():
		mentor_prefs[mentor_key] = [r for r in mentor_prefs[mentor_key] if r in mentee_prefs.keys()]

	for mentee_key in mentee_prefs.keys():
		mentee_prefs[mentee_key] = [r for r in mentee_prefs[mentee_key] if r in mentor_prefs.keys()]

	mentor_prefs = {key : val for key, val in mentor_prefs.items() if val != []}
	mentee_prefs = {key : val for key, val in mentee_prefs.items() if val != []}

	# print('after:')
	# print(mentor_prefs)
	# print(mentee_prefs)

	query = (
		'select `participant`.`starid`, `max matches id`\n'
		'from `participant` join `max matches assoc tbl` on `max matches assoc tbl`.`starid` = `participant`.`starid`\n'
		'where `participant`.`is mentor` = TRUE;'
	)

	cursor.execute(query)

	mentor_max_matches = {}

	for tup in cursor:
		mentor_max_matches[tup[0]] = tup[1]

	# ----------------------------------------------------------------------------------

	game = matching.games.HospitalResident.create_from_dictionaries(mentee_prefs, mentor_prefs, mentor_max_matches)
	stable_pairings = game.solve()


	new_matches_count = len(stable_pairings)
	if new_matches_count == 0:
		print("No new matches can be made at this time.")
		print()
		return []

	# ----------------------------------------------------------------------------------

	print("{} tenative {} made.".format(new_matches_count, "match" if new_matches_count == 1 else "matches"))
	print()

	matches = []
	for mentor in stable_pairings.keys():
		for matched_mentee in stable_pairings[mentor]:
			matches.append((mentor.name, matched_mentee.name))



	# __________________ Rematch Extra Mentees With Unmatched Mentors ___________________

	# "extra" meaning "matched with a mentor who has > 1 mentees"

	# get list of mentors with no matches
	all_mentors = [starid for starid in mentor_prefs.keys()]
	starids_of_unmatched_mentors = all_mentors

	for mentor in stable_pairings.keys():
		if len(stable_pairings[mentor]) > 0:
			starids_of_unmatched_mentors.remove(mentor.name)

	# ----------------------------------------------------------------------------------

	if len(starids_of_unmatched_mentors) == 0:
		print('No rematching done.')
		print()
		return matches

	# ----------------------------------------------------------------------------------

	printMatches('matches before rematching:', matches, base_indent=1)

	all_extra_mentees = [p for p in participants if not int(p.data_points['is mentor']) and countMatches(
		getParticipantByStarid(participants, [match[0] for match in matches if match[1] == p.data_points['starid']][0]), matches) > 1]
	
	# if there are no extra mentees, there's nothing we can do for the unmatched mentors :(
	if len(all_extra_mentees) > 0:
		# ________ For each unmatched mentor, determine which extra mentee(s) ranked them the highest _______

		# best mentees for each unmatched mentors (key = mentor, value = list of mentees)
		mentees_for_um_rankings = {}

		for um in starids_of_unmatched_mentors:
			# ranking of all mentees who are curently matched with a mentor who has > 1 mentees based on how highly they rank the unmatched mentor
			mentees_for_um_ranking = [all_extra_mentees[0]]
			cur_best_ranking = mentees_for_um_ranking[0].ranking.index(um)

			for i in range(1, len(all_extra_mentees)):
				em = all_extra_mentees[i]
				em_rank_of_um = em.ranking.index(um)
				i = len(mentees_for_um_ranking)

				while em_rank_of_um < mentees_for_um_ranking[i-1].ranking.index(um):
					i -= 1
					if i - 1 == -1:
						break

				mentees_for_um_ranking.insert(i, em)
					
			mentees_for_um_rankings[um] = mentees_for_um_ranking

		if globvars.debugging_on:
			print()
			print('Re-matching mentees with unmatched mentors...')
			print()
			# for um in mentees_for_um_rankings.keys():
			# 	print("mentees who prefer {} the most: ".format(um), end='')
			# 	for em in mentees_for_um_ranking:
			# 		print(em.data_points['starid'] + ", ", end='')

		
		# tching in the optimal way (i.e. such that mentees are making the minimum comprimis____

		while len(mentees_for_um_rankings) > 0:
			# we can avoid (most of the time) the case where all top-rankers are top-rankers for multiple ums by iterating through the ums multiple times, handling the simpler cases and removing ums after matching them, turning the afformentioned case into simpler cases. 
			
			# The only time when this doesn't allow us to avoid the complex case is when it's impossible for all ums to be matched with a mentee who is a top-ranker of them. 
			# In this case, the mentee who will be matched with a um that they weren't a top-ranker for should be chosen so that the difference in rank (difference in indexes) between their matched um and the um for which they were a top ranker is minimized.

			# to detect if/when we've reached a deadlock (have to handle the 'suboptimal' case described above)
			did_rematch = False
			starid_of_mentee_to_be_rematched = ''
			starid_of_mentor_to_be_rematched = ''

			for um in mentees_for_um_rankings.keys():
				# if none of the top-ranking mentee(s) are the top-rankers for any other ums (in which case it doesn't matter which one we choose becasue they're only "back on the market" for one um)
				if allTopRankersAreExclusive(mentees_for_um_rankings, um):
					# then rematch them with the um
					starid_of_mentee_to_be_rematched = mentees_for_um_rankings[um][0].data_points['starid']
					starid_of_mentor_to_be_rematched = um
					did_rematch = True
					continue

				# if 1 or more of the top-rankers is the top-ranker of multiple ums
				else:
					# choose one of the top-rankers who is NOT the top-ranker of multiple ums:
					for top_ranker in getStaridsOfTopRankers(mentees_for_um_rankings, um):
						if not isTopRankerForMultipleUms(mentees_for_um_rankings, top_ranker):
							starid_of_mentee_to_be_rematched = top_ranker
							starid_of_mentor_to_be_rematched = um
							did_rematch = True
							continue

			
			rematch(matches, starid_of_mentor_to_be_rematched, starid_of_mentee_to_be_rematched)
			mentees_for_um_rankings = updateMenteesForUmRankings(mentees_for_um_rankings, starid_of_mentor_to_be_rematched, starid_of_mentee_to_be_rematched, participants, matches)

			if not did_rematch:
				# if we triggered this condition, it MUST be the case that ALL top-rankers for all remaining ums are top-rankers for multiple ums
				# TODO: handle case where we need to use top-rankers in third place
				total_top_ranker_count = 0
				for um in mentees_for_um_rankings.keys():
					total_top_ranker_count += len(getStaridsOfTopRankers(mentees_for_um_rankings, um))

				if total_top_ranker_count >= len(mentees_for_um_rankings):
					# arbitrarily choose which top-ranker each remaining um gets
					for um in mentees_for_um_rankings.keys():
						starid_of_mentee_to_be_rematched = mentees_for_um_rankings[um][0].data_points['starid']
						rematch(um, starid_of_mentee_to_be_rematched)
						mentees_for_um_rankings = updateMenteesForUmRankings(mentees_for_um_rankings, um, starid_of_mentee_to_be_rematched, participants, matches)

				else:
					# e.g. if we have 3 ums left and only 1 top-ranker (for all 3), there are 3 different options for who gets the top ranker. For each of them, determine the sum of difference in indexes between the top-ranker and the next-best ranker
					# opportunity costs = {list of um mentor starids who will get matched with their top-ranker : total sum of mentee ranking indexes across all remaining ums}
					ocs = {}

					ums = [starid for starid in mentees_for_um_rankings.keys()]

					max_optimal = total_top_ranker_count
					max_suboptimal = len(mentees_for_um_rankings) - total_top_ranker_count

					# TODO: take into account non-first-place ties (we need to "compress" the ranking so that every element represents a different ranking and not different mentees who have the um ranked equally high)
					# only relevant when we some ums will need to be matched with the top-ranker in third place (otherwise we just choose the lowest-index second place mentee)
					def getOC(suboptimal):
						oc = 0

						for um in suboptimal:
							oc += len(getStaridsOfTopRankers(mentees_for_um_rankings, um)) # - 0 (the index of the top-ranker)

						return oc

					def do(suboptimal, optimal, remaining):
							next_um = remaining[0]
							remaining.remove(remaining[0])

							if len(optimal) < max_optimal:
								optimal.append(next_um)
								do(suboptimal, optimal)
								optimal.remove(next_um)

							if len(suboptimal) < max_suboptimal:
								suboptimal.append(next_um)
								do(suboptimal, optimal)
								optimal.remove(next_um)

							# determine opportunity cost of each scenario, choose scenario with min opportunity cost
							if len(remaining) == 0:
								optimal.sort()
								ocs[optimal] = getOC(suboptimal)


					do([], [], ums)

					# find the scenario with the minimum opportunity cost
					min_oc_key = ocs.keys(0)
					for i in range(1, ocs.keys()):
						oc_value = ocs[ocs.keys()[i]]

						if oc_value < ocs[min_oc_key]:
							min_oc = ocs.keys()[i]

					# to handle the rare cases where we need to match an um with the mentee who ranks them the 3rd, 4th, 5th, etc. highest, we will need a for loop here	

					# ums who get top-ranking mentee
					for um in ocs[min_oc_key]:
						starid_of_mentee_to_be_rematched = mentees_for_um_rankings[um][0]
						rematch(matches, um, starid_of_mentee_to_be_rematched)
						mentees_for_um_rankings = updateMenteesForUmRankings(mentees_for_um_rankings, um, starid_of_mentee_to_be_rematched, participants, matches)
					
					# ums who get second top-ranking mentee
					for um in ocs[min_oc_key]:
						starid_of_mentee_to_be_rematched = mentees_for_um_rankings[um][len(getStaridsOfTopRankers(mentees_for_um_rankings, um))]
						rematch(matches, um, starid_of_mentee_to_be_rematched)
						mentees_for_um_rankings = updateMenteesForUmRankings(mentees_for_um_rankings, um, starid_of_mentee_to_be_rematched, participants, matches)

		if globvars.debugging_on:
			printMatches('matches after rematching:', matches, base_indent=1)


	return matches

# ums = unmatched mentors
def isTopRankerForMultipleUms(mentees_for_um_rankings, mentee_starid):
	# combined list of top-rankers for all ums
	starids_of_all_top_rankers = []

	for um in mentees_for_um_rankings.keys():
		for top_ranker in getStaridsOfTopRankers(mentees_for_um_rankings, um):
			starids_of_all_top_rankers.append(top_ranker)

	count = 0

	for starid in starids_of_all_top_rankers:
		if starid == mentee_starid:
			count += 1

	return count > 1


def addMatchesToDatabase(cursor, matches, start_date, end_date):
	for match in matches:
		is_extendable = False
		earlier_grad_date	= None

		mentor_is_active_query = "SELECT `is active` FROM `participant` WHERE `starid` = 'mentorStarid'";
		mentee_is_active_query = "SELECT `is active` FROM `participant` WHERE `starid` = 'menteeStarid'";
		
		cursor.execute(mentor_is_active_query);
		mentor_is_active = None
		for tup in cursor:
			mentor_is_active = int(tup[0]);

		cursor.execute(mentee_is_active_query);
		mentee_is_active = None
		for tup in cursor:
			mentee_is_active = int(tup[0]);

		if mentor_is_active and mentee_is_active:
			is_extendable = True

		mentor_grad_date_query = f"SELECT `graduation date` FROM `participant` WHERE `starid` = '{match[0]}'";
		mentee_grad_date_query = f"SELECT `graduation date` FROM `participant` WHERE `starid` = '{match[1]}'";

		cursor.execute(mentor_grad_date_query)
		mentor_grad_date = None
		for tup in cursor:
			mentor_grad_date = tup[0];

		cursor.execute(mentee_grad_date_query)
		mentee_grad_date = None
		for tup in cursor:
			mentee_grad_date = tup[0];

		earlier_grad_date = min(mentor_grad_date, mentee_grad_date).strftime('%Y-%m-%d');

		is_extendable = earlier_grad_date > end_date
		
		insert_str = (
			'INSERT INTO mentorship'
			'(`mentor starid`, `mentee starid`, `start date`, `end date`, `is extendable`, `earlier grad date`)'
			' VALUES '
			f"('{match[0]}', '{match[1]}', '{start_date}', '{end_date}', '{int(is_extendable)}', '{earlier_grad_date}');"
		)

		cursor.execute(insert_str)

