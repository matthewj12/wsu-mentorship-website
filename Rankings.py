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

		'biology (allied health or cell molecular)' : (
			'biology (allied health or cell molecular)',
			'biology (medical lab science)',
			'biology (ecology or environmental science)',
			'biology (radiography)',
			'chemistry'
		),

		'biology (medical lab science])' : (
			'biology (medical lab science)',
			'biology (allied health or cell molecular)',
			'biology (radiography)',
			'biology (ecology or environmental science)',
			'chemistry'
		),

		'biology (ecology or environmental science])' : (
			'biology (ecology or environmental science)',
			'biology (allied health or cell molecular)',
			'biology (medical lab science)',
			'biology (radiography)',
			'chemistry'
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

		'biology (radiography])' : (
			'biology (radiography)',
			'biology (medical lab science)',
			'biology (allied health or cell molecular)',
			'biology (ecology or environmental science)',
			'chemistry'
		),
													
		'physics' : (
			'physics',
			'general engineering',
			'composite materials engineering',
			'math'
		)
	},

	'gender' : {
		'male'                : ('male',),
		'female'              : ('female',),
		'non-binary'          : ('non-binary',),
		'other'               : ('other',),
		'prefer not to answer': ('prefer not to answer',)
	},

	'religious affiliation' : {
		'christianity'                : ('christianity',),
		'judaism'                     : ('judaism',),
		'islam'                       : ('islam',),
		'buddhism'                    : ('buddhism',),
		'hinduism'                    : ('hinduism',),
		'taoism'                      : ('taoism',),
		'spiritual but not religious' : ('spiritual but not religious',),
		'agnostic'                    : ('agnostic',),
		'atheist'                     : ('atheist',),
		'pastafarian'                 : ('westboro baptist',),
		'other'                       : ('other',),
		'prefer not to answer'        : ('prefer not to answer',)
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

	'second language' : {
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
