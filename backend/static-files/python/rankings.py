
# All unlisted options share last place.

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
			'composite materials engineering', 
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
		)
	},

	'religious affiliation' : {
		'christianity'                : (
			'christianity',
			'judaism',
			'islam',
			'agnostic'
			),
		'judaism'                     : (
			'judaism',
			'christianity',
			'islam',
			'agnostic'
			),
		'islam'                       : (
			'islam',
			'christianity',
			'judaism',
			'agnostic'
			),
		'buddhism'                    : (
			'buddhism',
			'taoism',
			'hinduism',
			'agnostic'
			),
		'hinduism'                    : (
			'hinduism',
			'buddhism',
			'taoism',
			'agnostic'
			),
		'taoism'                      : (
			'taoism',
			'buddhism',
			'hinduism',
			'agnostic'
			),
		'spiritual but not religious' : (
			'spiritual but not religious',
			'agnostic',
			'atheist'
			),
		'agnostic'                    : (
			'agnostic',
			'atheist',
			'spiritual but not religious'
			),
		'atheist'                     : (
			'atheist',
			'agnostic',
			'spiritual but not religious'
			),
		'other'                       : (
			'other',
			),
		'prefer not to answer'        : (
			'prefer not to answer',
			)
	},
}