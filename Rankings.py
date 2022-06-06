# All unlisted pairings share last place.
rankings = {
	'major' : {
		'chemistry' : (
			'chemistry',
			'biology (allied health or cell molecular)',
			'biology (medical lab science)', 
			'biology (radiography)', 
			'biology (ecology or environmental science)',
			'geoscience'
		),

		'geoscience' : (
			'geoscience',
			'chemistry', 
			'biology (ecology or environmental science)',
			'biology (radiography)', 
			'biology (allied health or cell molecular)', 
			'biology (medical lab science)'
		),

		'math' : (
			'math',
			'statistics', 
			'data science', 
			'computer science', 
			'general engineering', 
			'composite material engineering', 
			'physics'
		),

		'data science' : (
			'data science',
			'computer science', 
			'statistics', 
			'math', 
			'physics'
		),

		'undecided' : (
			'undecided'
			# 'economics' # lol
		),

		'general engineering' : (
			'general engineering',
			'composite materials engineering',
			'physics',
			'math'
		),

		'computer science' : (
			'computer science',
			'data science',
			'math',
			'statistics'
		),

		'statistics' : (
			'statistics',
			'math',
			'data science',
			'computer science'
		),

		'composite materials engineering' : (
			'composite materials engineering',
			'general engineering',
			'chemistry',
			'physics',
			'math'
		),

		'physics' : (
			'physics',
			'general engineering',
			'composite materials engineering',
			'math'
		),

		'biology (allied health or cell molecular)' : (
			'biology (allied health or cell molecular)',
			'biology (medical lab science)',
			'biology (ecology or environmental science)',
			'biology (radiography)',
			'chemistry'
		),

		'biology (medical lab science)' : (
			'biology (medical lab science)',
			'biology (allied health or cell molecular)',
			'biology (radiography)',
			'biology (ecology or environmental science)',
			'chemistry'
		),

		'biology (ecology or environmental science)' : (
			'biology (ecology or environmental science)',
			'biology (allied health or cell molecular)',
			'biology (medical lab science)',
			'biology (radiography)',
			'chemistry'
		),

		'biology (radiography)' : (
			'biology (radiography)',
			'biology (medical lab science)',
			'biology (allied health or cell molecular)',
			'biology (ecology or environmental science)',
			'chemistry'
		),
	},

	'gender' : {
		'male'                 : ('male',),
		'female'               : ('female',),
		'non-binary'           : ('non-binary',),
		'other'                : ('other',),
		'prefers not to answer': ('prefer not to answer',)
	},

	'religious affiliation' : {
		'christianity'                 : ('christianity',),
		'judaism'                      : ('judaism',),
		'islam'                        : ('islam',),
		'buddhism'                     : ('buddhism',),
		'hinduism'                     : ('hinduism',),
		'taoism'                       : ('taoism',),
		'spiritual but not religious'  : ('spiritual but not religious',),
		'agnostic'                     : ('agnostic',),
		'atheist'                      : ('atheist',),
		'pastafarian'                  : ('westboro baptist',),
		'other'                        : ('other',),
		'prefers not to answer'        : ('prefer not to answer',)
	},

	'race' : {
		'white'                                : ('white',),
		'black'                                : ('black',),
		'aboriginal'                           : ('aboriginal',),
		'native american'                      : ('native american',),
		'native hawaiian or pacific islander'  : ('native hawaiian or pacific islander',),
		'asian'                                : ('asian',),
		'hispanic'                             : ('hispanic',),
		'other'                                : ('other',),
		'prefers not to answer'                : ('prefer not to answer',),
		'mixed'                                : ('mixed',)
	},

	'interested in diversity groups' : {
		1 : (1,),
		0 : (0,)
	},

	'second language' : {
		'american sign language' : ('american sign language'),
		'arabic'                 : ('arabic'),
		'bangla'                 : ('bangla'),
		'chinese'                : ('chinese'),
		'french'                 : ('french '),
		'german'                 : ('german'),
		'hindi/urdu'             : ('hindi/urdu'),
		'japanese'               : ('japanese'),
		'korean'                 : ('korean'),
		'russian'                : ('russian'),
		'somali'                 : ('somali'),
		'spanish'                : ('spanish'),
		'thai'                   : ('thai'),
		'vietnamese'             : ('vietnamese'),
		'other'                  : ('other'),
		'none'                   : ('none')
	}
}
