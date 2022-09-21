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

//  together under a single heading
// group all misc data points (boolean columns in `participant`)
function formatMiscDataPoints() {
	let inputs = document.getElementsByTagName('input');

	inputs[7].parentNode.getElementsByTagName('label')[0].innerHTML = 'Check all that apply.';

	for (let i = 8; i < 16; i++) {
		inputs[i].parentNode.getElementsByTagName('label')[0].remove();
	}
}

// every time the user selects an option
function changeIqOptionsUntilNoDuplicates() {
	let iq1Select = document.getElementById('important-quality-1-assoc-tbl.important-quality-1-id');
	let iq2Select = document.getElementById('important-quality-2-assoc-tbl.important-quality-2-id');
	let iq3Select = document.getElementById('important-quality-3-assoc-tbl.important-quality-3-id');
	
	while (iq2Select.selectedIndex == iq1Select.selectedIndex) {
		iq2Select.selectedIndex++;
	}

	while (iq3Select.selectedIndex == iq1Select.selectedIndex || iq3Select.selectedIndex == iq2Select.selectedIndex) {
		iq3Select.selectedIndex++;
	}
}

function addNoDuplicateIqListeners() {
	let iq1Options = document.getElementById('important-quality-1-assoc-tbl.important-quality-1-id').getElementsByTagName('option');
	let iq2Options = document.getElementById('important-quality-2-assoc-tbl.important-quality-2-id').getElementsByTagName('option');
	let iq3Options = document.getElementById('important-quality-3-assoc-tbl.important-quality-3-id').getElementsByTagName('option');
	
	for (let i = 0; i < iq1Options.length; i++) {
		iq1Options[i].addEventListener("click", changeIqOptionsUntilNoDuplicates);
	}

	for (let i = 0; i < iq2Options.length; i++) {
		iq2Options[i].addEventListener("click", changeIqOptionsUntilNoDuplicates);
	}

	for (let i = 0; i < iq3Options.length; i++) {
		iq3Options[i].addEventListener("click", changeIqOptionsUntilNoDuplicates);
	}
}