from participant import *

def getTestModeParticipants():
	participants = []

	# for if you want to mess around and test the matching algorithm manually
	for i in range(8):
		participants.append(Participant())

	# def debug_constructor(self, is_active, is_mentor, starid, max_matches, ranking):

	# mentees
	participants[0].debugConstructor('1', '0', 'a', '1', ['e', 'g', 'f', 'h'])
	participants[1].debugConstructor('1', '0', 'b', '1', ['e', 'h', 'g', 'f'])
	participants[2].debugConstructor('1', '0', 'c', '1', ['f', 'e', 'g', 'h'])
	participants[3].debugConstructor('1', '0', 'd', '1', ['f', 'h', 'e', 'g'])
	# mentors
	participants[4].debugConstructor('1', '1', 'e', '2', ['a', 'b', 'c', 'd'])
	participants[5].debugConstructor('1', '1', 'f', '2', ['a', 'b', 'c', 'd'])
	participants[6].debugConstructor('1', '1', 'g', '2', ['a', 'b', 'c', 'd'])
	participants[7].debugConstructor('1', '1', 'h', '2', ['a', 'b', 'c', 'd'])

	return participants
