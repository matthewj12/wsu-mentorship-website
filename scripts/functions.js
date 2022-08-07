let enabledLabel = '';
let disabledLabel = '';
let disabledSubstr = '<br><span class="context-message">Mentees can only be matched with one mentor.<span>';

function disableMaxMatches() {
	let id = 'max-matches-assoc-tbl_max-matches-id';

	document.getElementById(id).value = 1;
	document.getElementById(id).disabled = true;

	document.getElementById('max-matches-label').innerHTML = disabledLabel;
}

function enableMaxMatches() {
	document.getElementById('max-matches-assoc-tbl_max-matches-id').disabled = false;

	document.getElementById('max-matches-label').innerHTML = enabledLabel;
}

function replaceTextContentXWithY(className, X, Y) {
	let surveyItemLabels = document.getElementsByClassName(className);
	let found;

	for (let i = 0; i < surveyItemLabels.length; i++) {
		if (surveyItemLabels[i].textContent.includes(X)) {
			surveyItemLabels[i].textContent = surveyItemLabels[i].textContent.replaceAll(X, Y)
		}
	}
}

function replaceMentorWithMentee() {
	replaceTextContentXWithY('survey-item-label', 'mentor', 'mentee');
}

function replaceMenteeWithMentor() {
	replaceTextContentXWithY('survey-item-label', 'mentee', 'mentor');
}

function addIsMentorListeners() {
	enabledLabel = document.getElementById('max-matches-label').innerHTML;
	disabledLabel = enabledLabel + disabledSubstr;

	document.getElementById('participant_is-mentor_0').addEventListener("click", disableMaxMatches);
	document.getElementById('participant_is-mentor_1').addEventListener("click", enableMaxMatches);

	document.getElementById('participant_is-mentor_0').addEventListener("click", replaceMenteeWithMentor);
	document.getElementById('participant_is-mentor_1').addEventListener("click", replaceMentorWithMentee);
}

function updateResults() {
	let input, filter, ul, li, a, i, txtValue;

    input = document.getElementById("search-box");
    filter = input.value.toLowerCase();
    ul = document.getElementById("search-results-ul");
    li = ul.getElementsByTagName("li");

    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("a")[0];
        txtValue = a.textContent || a.innerText;

        if (txtValue.toLowerCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        }
		else {
            li[i].style.display = "none";
        }
    } 
}