let disabledLabel = '';
let disabledSubstr = '<br><span class="context-message">Mentees can only be matched with one mentor.<span>';

function disablemaxMatchesDropdown() {
	let id = 'max-matches-assoc-tbl.max-matches-id';

	document.getElementById(id).value = 1;
	document.getElementById(id).disabled = true;

	document.getElementById('max-matches-assoc-tbl.max-matches-id.label').innerHTML = disabledLabel;
}

function enablemaxMatchesDropdown() {
	document.getElementById('max-matches-assoc-tbl.max-matches-id').disabled = false;

	document.getElementById('max-matches-assoc-tbl.max-matches-id.label').innerHTML = enabledLabel;
}

// SIL = survey item label
function tryReplaceXThenYWithZSurvey(X, Y, Z) {
	let sil = document.getElementsByClassName('survey-item-label');

	// This label reads "Are you a mentor or mentee?" which we obviously don't want to modify
	exclusion = document.getElementById('participant.is-mentor.0');

	for (let i = 0; i < sil.length; i++) {
		if (!sil[i].parentNode.contains(exclusion) && sil[i].innerHTML.includes(X)) {
			sil[i].innerHTML = sil[i].innerHTML.replaceAll(X, Z);
		}
		else if (!sil[i].parentNode.contains(exclusion) && sil[i].innerHTML.includes(Y)) {
			sil[i].innerHTML = sil[i].innerHTML.replaceAll(Y, Z);
		}
	}
}

function updateTextToMentee() {
	tryReplaceXThenYWithZSurvey('mentors/mentees', 'mentors', 'mentees');
}

function updateTextToMentor() {
	tryReplaceXThenYWithZSurvey('mentors/mentees', 'mentees', 'mentors');
}

function addIsMentorListeners() {
	// We refer to the number of matches via an id that is identical to the actual number smiply to be consistent with the other association tables
	enabledLabel = document.getElementById('max-matches-assoc-tbl.max-matches-id.label').innerHTML;
	disabledLabel = enabledLabel + disabledSubstr;

	document.getElementById('participant.is-mentor.0').addEventListener("click", disablemaxMatchesDropdown);
	document.getElementById('participant.is-mentor.1').addEventListener("click", enablemaxMatchesDropdown);

	document.getElementById('participant.is-mentor.0').addEventListener("click", updateTextToMentor);
	document.getElementById('participant.is-mentor.1').addEventListener("click", updateTextToMentee);
}

function formatMiscDataPoints() {
	sil = document.getElementsByTagName('input');

	sil[9].parentNode.getElementsByTagName('label')[0].innerHTML = 'Check all that apply.';

	for (let i = 9; i < 17; i++) {
		sil[i].parentNode.getElementsByTagName('label')[0].remove();
	}
}


