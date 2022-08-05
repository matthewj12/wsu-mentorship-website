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

function addIsMentorListeners() {
	enabledLabel = document.getElementById('max-matches-label').innerHTML;
	disabledLabel = enabledLabel + disabledSubstr;
	document.getElementById('participant_is-mentor_0').addEventListener("click", disableMaxMatches);
	document.getElementById('participant_is-mentor_1').addEventListener("click", enableMaxMatches);
}