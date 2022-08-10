let enabledLabel = '';
let disabledLabel = '';
let disabledSubstr = '<br><span class="context-message">Mentees can only be matched with one mentor.<span>';

function disablemaxMatchesDropdown() {
	let id = 'max-matches-assoc-tbl_max-matches-id';

	document.getElementById(id).value = 1;
	document.getElementById(id).disabled = true;

	document.getElementById('max-matches-label').innerHTML = disabledLabel;
}

function enablemaxMatchesDropdown() {
	document.getElementById('max-matches-assoc-tbl_max-matches-id').disabled = false;

	document.getElementById('max-matches-label').innerHTML = enabledLabel;
}

// SIL = survey item label
function tryReplaceXThenYWithZInSIL(X, Y, Z) {
	let sil = document.getElementsByClassName('survey-item-label');

<<<<<<< Updated upstream
	for (let i = 0; i < surveyItemLabels.length; i++) {
		if (surveyItemLabels[i].textContent.includes(X)) {
			surveyItemLabels[i].textContent = surveyItemLabels[i].textContent.replaceAll(X, Y);
=======
	// This label reads "Are you a mentor or mentee?"ant_is-mentor_0') which we obviously don't want to modify
	let toExclude = document.getElementById('participant_is-mentor_0');

	for (let i = 0; i < sil.length; i++) {
		if (!sil[i].parentNode.contains(toExclude) && sil[i].innerHTML.includes(X)) {
			sil[i].innerHTML = sil[i].innerHTML.replaceAll(X, Z);
		}
		else if (!sil[i].parentNode.contains(toExclude) && sil[i].innerHTML.includes(Y)) {
			sil[i].innerHTML = sil[i].innerHTML.replaceAll(Y, Z);
>>>>>>> Stashed changes
		}
	}
}

function updateTextToMentee() {
	tryReplaceXThenYWithZInSIL('mentor/mentee', 'mentor', 'mentee');
}

function updateTextToMentor() {
	tryReplaceXThenYWithZInSIL('mentor/mentee', 'mentee', 'mentor');
}

function addIsMentorListeners() {
	// We refer to the number of matches via an id that is identical to the actual number smiply to be consistent with the other association tables
	enabledLabel = document.getElementById('max-matches-assoc-tbl.max-matches-id').innerHTML;
	disabledLabel = enabledLabel + disabledSubstr;

	document.getElementById('participant.is-mentor.0').addEventListener("click", disablemaxMatchesDropdown);
	document.getElementById('participant.is-mentor.1').addEventListener("click", enablemaxMatchesDropdown);

	document.getElementById('participant.is-mentor.0').addEventListener("click", updateTextToMentor);
	document.getElementById('participant.is-mentor.1').addEventListener("click", updateTextToMentee);
}

// for admin dashboard
function updateResults() {
	let input, filter, ul, li, a, i, txtValue;

    input = document.getElementById("search-box");
    filter = input.value.toLowerCase();
    ul = document.getElementById("search-results-ul");
    li = ul.getElementsByTagName("li");

    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("a")[0];
        txtValue = a.innerHTML || a.innerText;

        if (txtValue.toLowerCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        }
		else {
            li[i].style.display = "none";
        }
    } 
}
<<<<<<< Updated upstream
=======

function formatMiscDataPoints() {
	sil = document.getElementsByTagName('input');

	sil[9].parentNode.getElementsByTagName('label')[0].innerHTML = 'Check all that apply.';

	for (let i = 10; i < 18; i++) {
		sil[i].parentNode.getElementsByTagName('label')[0].remove();
	}
}
>>>>>>> Stashed changes
