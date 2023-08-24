let mentorsSelected = true;
// manual match confirmation
selectedMatchModeIdPrefix = 'mmc';
let viewMentorsBtn = document.getElementById('view-mentors');
let viewMenteesBtn = document.getElementById('view-mentees');
let viewMatchReasonsBtn = document.getElementById('view-match-reasons-btn');


function replaceXWithY(X, Y) {
	let elem = document.getElementById('group-desc');
	elem.innerHTML = elem.innerHTML.replace(X, Y);

	elem = document.getElementById('starid-search-box');
	elem.placeholder = elem.placeholder.replace(X, Y);

	let elems = document.getElementsByClassName('participant-search-text-input');
	
	for (let i = 0; i < elems.length; i++) {
		elems[i].placeholder = elems[i].placeholder.replace(X, Y);
	}
}

function updateResultsByStarid() {
	let group = mentorsSelected ? 'mentor-row' : 'mentee-row';
	let filter = document.getElementById('starid-search-box').value.toLowerCase();
	let results = document.getElementsByClassName('participant-row ' + group);

	for (let i = 0; i < results.length; i++) {
		// The starid is in the element's ID.
		if (results[i].id.split('-')[2].includes(filter)) {
			results[i].style.display = "inline-grid";
		}
		else {
			results[i].style.display = "none";
		}
	}
}

function updateResultsByName() {
	let group = mentorsSelected ? 'mentor-row' : 'mentee-row';
	let filter = document.getElementById('name-search-box').value.toLowerCase();
	let results = document.getElementsByClassName('participant-row ' + group);

	for (let i = 0; i < results.length; i++) {
		// The name is in the element's ID.
		let fname = results[i].classList[2][0].toLowerCase();
		let lname = results[i].classList[2][1].toLowerCase();
		
		if (fname.startsWith(filter) || lname.startsWith(filter)) {
			results[i].style.display = "inline-grid";
		}
		else {
			results[i].style.display = "none";
		}
	}
}

function clearSearchBoxes() {
	document.getElementById('starid-search-box').value = '';
	document.getElementById('name-search-box').value = '';
	updateResultsByName();
	updateResultsByStarid();
}

function showMentorsMentees() {
	let group         = mentorsSelected ? 'mentor-row' : 'mentee-row';
	let groupOpposite = mentorsSelected ? 'mentee-row' : 'mentor-row';

	let toHide = document.getElementsByClassName(groupOpposite);
	let toShow = document.getElementsByClassName(group);
	
	for (i = 0; i < toShow.length; i++) {
		toShow[i].style.display = "";
	}
	for (i = 0; i < toHide.length; i++) {
		toHide[i].style.display = "none";
	}
}

function viewMentors() {
	setMentorsSelected(true);
	viewMentorsBtn.style.backgroundColor = 'var(--btn-select)';
	viewMenteesBtn.style.backgroundColor = 'var(--btn-default)';
	clearSearchBoxes();
}

function viewMentees() {
	setMentorsSelected(false);
	viewMentorsBtn.style.backgroundColor = 'var(--btn-default)';
	viewMenteesBtn.style.backgroundColor = 'var(--btn-select)';
	clearSearchBoxes();
}

function setMentorsSelected(p_mentorsSelected) {
	let selectedBtnClass = "btn btn-primary";
	let unselectedBtnClass = "btn btn-info";

	mentorsSelected = p_mentorsSelected;

	if (mentorsSelected) {
		replaceXWithY('Mentee', 'Mentor');
		showMentorsMentees();
		menteeBtnClass = unselectedBtnClass
		mentorBtnClass = selectedBtnClass;
	}
	else {
		replaceXWithY('Mentor', 'Mentee');
		showMentorsMentees();
		menteeBtnClass = selectedBtnClass
		mentorBtnClass = unselectedBtnClass;
	}

	document.getElementById('view-mentees').className = menteeBtnClass;
	document.getElementById('view-mentors').className = mentorBtnClass;
}

function highlightRow(starid) {
	// also clear highlighting for rows in bottom-left section
	
	let selectedRow = document.getElementById("participant-row-" + starid);
	selectedRow.style.backgroundColor = 'rgb(200, 100, 150)';
}

function clearStaridBorders() {
	let toUnselect = document.getElementsByClassName('starid');
	for (let i = 0; i < toUnselect.length; i++) {
		toUnselect[i].style.borderColor = "var(--norm-border-color)";
		toUnselect[i].style.borderWidth = "var(--border-width)";
		toUnselect[i].style.zIndex = "0";
	}
}

let selectedStarid = "";
function setSelectedStarid(newSelectedStarid) {
	document.getElementById('default-part-info').hidden = true;

	selectedStarid = newSelectedStarid;

	clearStaridBorders();
	
	if (selectedStarid != "") {
		let starid = document.getElementById('participant-dashboard-'+selectedStarid);
		starid.style.borderColor = "var(--selected-border-color)";

		let matchStarids = document.getElementsByClassName('match-starid-'+selectedStarid);
		for (let i = 0; i < matchStarids.length; i++) {
			matchStarids[i].style.borderColor = "var(--selected-border-color";
			matchStarids[i].style.borderWidth = "var(--border-width)";
			// matchStarids[i].style.zIndex = "1";
		}
	}
}

function hideAllParticipantInfoExceptSelected() {
	let infoDivs = document.getElementsByClassName('participant-info-values-set');
	
	if (selectedStarid == "") {
		let infoDivs = document.getElementsByClassName('participant-info-values-set');
		for (let i = 0; i < infoDivs.length; i++) {
			infoDivs[i].style.visibility = "collapsed";
		}
		return;
	}

	for (let i = 0; i < infoDivs.length; i++) {
		if (infoDivs[i].id == 'participant-info-' + selectedStarid) {
			infoDivs[i].style.display = "inline-grid";
		}
		else {
			infoDivs[i].style.display = "none";
		}
	}
}

function disableAllButtonsExceptConfirmCancel() {
	let buttons = document.getElementsByTagName('button');

	for (let i = 0; i < buttons.length; i++) {
		if (buttons[i].id == selectedMatchModeIdPrefix + '-btn-cancel') {
			buttons[i].disabled = false;
		}
		else {
			buttons[i].disabled = true;
		}
	}
}

function enableAllButtons() {
	let buttons = document.getElementsByTagName('button');

	for (let i = 0; i < buttons.length; i++) {
		buttons[i].disabled = false;
	}
}

function setSelectedMatchMode(val) {
	selectedMatchModeIdPrefix = val;
	updateButtonHighlighting('match-type-btn', selectedMatchModeIdPrefix);
}

function hideInvalidMessages() {
	let invalidMessages = document.getElementsByClassName("invalid-input");

	for (let i = 0; i < invalidMessages.length; i++) {
		invalidMessages[i].value = "";
	}
}

function showMatchReasons() {
	let toShow = document.getElementById("match-reasons")
	toShow.hidden = false;

	// Make the relevant input tags required.
	let inputs = toShow.getElementsByTagName('input');
	for (let i = 0; i < inputs.length; i++) {
		inputs[i].required = true;
	}
	
}

function hideAllDialogBoxes() {
	document.getElementById("match-reasons").hidden = true;
	let inputs = document.getElementById('confirmation-dialogs').getElementsByTagName('input');
	for (let i = 0; i < inputs.length; i++) {
		inputs[i].value = '';
		inputs[i].required = false;
	}

	enableAllButtons();
	
	let toHide = document.getElementsByClassName("mc");

	for (let i = 0; i < toHide.length; i++) {
		toHide[i].hidden = true
	}

	toHide = document.getElementsByClassName('invalid-input');
	for (let i = 0; i < toHide.length; i++) {
		toHide[i].style.visibility = 'hidden';
	}
}

function showCreateMatchesConfirmation() {
	// hide all versions of the confirmation dialog box to start with
	hideAllDialogBoxes();
	hideInvalidMessages();
	disableAllButtonsExceptConfirmCancel();

	// Reset the mentor and mentee starids from the last time a manual match was created or a match was extended.
	if (selectedMatchModeIdPrefix != 'a') {
		document.getElementById(selectedMatchModeIdPrefix + "-mentor-starid").value = "";
		document.getElementById(selectedMatchModeIdPrefix + "-mentee-starid").value = "";
	}

	// Show and require the relevant matching confirmation container.
	let toShow = document.getElementById(selectedMatchModeIdPrefix + 'mc');
	toShow.hidden = false;
	toShow.required = true;

	// Make the relevant input tags required.
	let inputs = toShow.getElementsByTagName('input');
	for (let i = 0; i < inputs.length; i++) {
		inputs[i].required = true;
	}
}

function showDeleteMatchesConfirmation() {
	setMatchToDelete("", "")

	document.getElementById('dmc-confirm').disabled = true;
	
	matchToDelete = "";
	disableAllButtonsExceptConfirmCancel();

	let toShow = document.getElementById("dmc");
	toShow.hidden = false;

	let toHide = document.getElementsByClassName("dmc-match-set");
	for (let i = 0; i < toHide.length; i++) {
		toHide[i].hidden = true;
	}

	document.getElementById("dmc-match-set-" + selectedStarid).hidden = false;
	document.getElementById('dmc-btn-cancel').disabled = false;
}

function hideDeleteMatchesConfirmation() {
	enableAllButtons();

	let toHide = document.getElementById("dmc");
	toHide.hidden = true;
}

let matchToDelete = "";
function setMatchToDelete(starid, matchStarid) {
	matchToDelete = matchStarid;

	document.getElementById('dmc-confirm').disabled = false;


	document.getElementById('match-to-delete-input').value = mentorsSelected ? starid + '-' + matchToDelete : matchToDelete + '-' + starid;

	let toHide = document.getElementsByClassName('dmc-match-set-' + matchToDelete);
	for (let i = 0; i < toHide.length; i++) {
		toHide[i].hidden = true
	}

	if (starid != "") {
		let toShow = document.getElementById('dmc-match-set-' + starid);
		toShow.hidden = false;
	}

	clearStaridBorders();

	if (matchToDelete != "") {
		let toSelect = document.getElementById('match-to-delete-' + starid + '-' + matchToDelete);
		toSelect.style.borderColor = "var(--selected-border-color)";
		// toSelect.style.zIndex = "1";
	}
}

function updateButtonHighlighting(baseBtnClass, alsoCall=null) {
	// let selectedColor = 'rgb(150, 150, 200)';
	// let unselectedColor = 'rgb(200, 200, 200)';
	let selectedBtnClass = " btn btn-primary";
	let unselectedBtnClass = " btn btn-info";

	let buttons = document.getElementsByClassName(baseBtnClass);

	for (let i = 0; i < buttons.length; i++) {
		if (buttons[i].id.includes(selectedMatchModeIdPrefix)) {
			buttons[i].className = baseBtnClass + selectedBtnClass;
		}
		else {
			buttons[i].className = baseBtnClass + unselectedBtnClass;
		}
	}
}

function getParticipants() {
	let participantRows = document.getElementsByClassName("participant-row");
	let mentorStarids = [];
	let menteeStarids = [];

	for (let i = 0; i < participantRows.length; i++) {
		// id of participant row is "participant-row-{starid}"
		if (participantRows[i].className.includes("mentor")) {
			mentorStarids.push(participantRows[i].id.split("-")[2]);
		}
		else {
			menteeStarids.push(participantRows[i].id.split("-")[2]);
		}
	}

	return [mentorStarids, menteeStarids];
}

// returns whether the starid exists in the database and shows/hides the error message for the relevant input accordingly
function validateStarid(inputToValidate, displayErrorMsg) {
	let staridElem = document.getElementById(selectedMatchModeIdPrefix + '-' + inputToValidate + '-starid');

	// Don't give the error message until the user is done typing the starid ('cause that's annoying)
	// build list of valid starids
	let participants = getParticipants();
	let mentorStarids = participants[0];
	let menteeStarids = participants[1];

	// check that starid is in that list
	let errorMsgElem = document.getElementById(selectedMatchModeIdPrefix + '-invalid-' + inputToValidate + '-starid');

	let starids = inputToValidate == 'mentor' ? mentorStarids : menteeStarids;

	if (displayErrorMsg) {
		// starids are 8 characters long
		// don't show error message for partially-typed starid (when input is focused)
		if ((document.activeElement !== staridElem && !starids.includes(staridElem.value)) || (document.activeElement === staridElem && staridElem.value.length == 8 && !starids.includes(staridElem.value))) {
			errorMsgElem.style.visibility = "visible";
		}
		else {
			errorMsgElem.style.visibility = "hidden";
		}
	}
	return starids.includes(staridElem.value);
}

function matchExists(mentorStarid, menteeStarid) {
	let matches = JSON.parse(document.querySelector('#invisible-matches-elem').innerHTML);

	for (let i = 0; i < matches.length; i++) {
		if (matches[i]['mentor starid'] == mentorStarid && matches[i]['mentee starid'] == menteeStarid) {
			return true;
		}
	}
	return false;
}

function validateExtend(inputToValidate) {
	let menteeStarid = document.querySelector('#e-mentee-starid').value;
	let mentorStarid = document.querySelector('#e-mentor-starid').value;
	let confirmBtn = document.querySelector('#e-btn-confirm');
	let noMatchErrorMsg = document.querySelector('#e-no-match-exists');
	let cannotExtendErrorMsg = document.querySelector('#invalid-match-to-extend');
	let matches = JSON.parse(document.querySelector('#invisible-matches-elem').innerHTML);
	let isExtendable, maxExtendDate;
	let validMentorStarid = validateStarid('mentor', inputToValidate == 'mentor');
	let validMenteeStarid = validateStarid('mentee', inputToValidate == 'mentee');
	let curMatchEndDate;
	confirmBtn.disabled = true;

	if (!validMenteeStarid || !validMentorStarid) {
		noMatchErrorMsg.style.visibility = 'hidden';
		cannotExtendErrorMsg.style.visibility = 'hidden';
	}
	else if (!matchExists(mentorStarid, menteeStarid)) {
		noMatchErrorMsg.style.visibility = "visible";
		cannotExtendErrorMsg.style.visibility = 'hidden';
	}
	else {
		noMatchErrorMsg.style.visibility = "hidden";

		for (let i = 0; i < matches.length; i++) {
			if (matches[i]['mentor starid'] == mentorStarid && matches[i]['mentee starid'] == menteeStarid) {
				isExtendable = matches[i]['is extendable'] == '1' ? true : false;
				maxExtendDate = matches[i]['earlier grad date'];
				curMatchEndDate = matches[i]['end date'];
			}
		}

		if (!isExtendable) {
			cannotExtendErrorMsg.style.visibility = "visible";
		}
		else {
			cannotExtendErrorMsg.style.visibility = "hidden";
			confirmBtn.disabled = false;
		}
	}

	// validate extend-to date
	let dateErrorMsg1 = document.querySelector('#e-invalid-end-date1');
	let dateErrorMsg2 = document.querySelector('#e-invalid-end-date2');
	let dateElem = document.querySelector('#e-end-date');
	if (dateElem.value == '') {
		confirmBtn.disabled = true;
	}
	else if (inputToValidate.includes('date')) {
		dateErrorMsg1.style.visibility = "hidden";
		dateErrorMsg2.style.visibility = "hidden";
		if (dateElem.value > maxExtendDate) {
			dateErrorMsg1.style.visibility = "visible";
			confirmBtn.disabled = true;
		}
		else if (dateElem.value <= curMatchEndDate) {
			dateErrorMsg2.style.visibility = "visible";
			confirmBtn.disabled = true;
		}
	}
}

function validateViewMatchReasons(inputToValidate) {
	let menteeStarid = document.querySelector('#r-mentee-starid').value;
	let mentorStarid = document.querySelector('#r-mentor-starid').value;
	let errorMsgElem = document.getElementById('r-no-match-exists');
	let confirmBtn = document.querySelector('#r-btn-confirm');
	confirmBtn.disabled = false;
	
	if (!validateStarid(inputToValidate, true)) {
		confirmBtn.disabled = true;
	}
	// valid starids, but no match between them
	// don't set the error messages to visible (second parameter = false) because validateViewMatchReasons is only called the display the error messages for one input. The "hooks" that its attached too are also the ones that the "no match exists between these participants" should be attached to, which is why we have the code below.

	errorMsgElem.style.visibility = 'hidden';
	if (validateStarid('mentor', false) && validateStarid('mentee', false) && !matchExists(mentorStarid, menteeStarid)) {
		errorMsgElem.style.visibility = 'visible';
		confirmBtn.disabled = true;
	}
}

function isActive(starid) {

}

function isAtMaxMatches(starid) {

}

function getMaxMatches(starid) {
	return parseInt(document.getElementById('participant-row-' + starid).getElementsByClassName('max-matches')[0].innerHTML);
}

function countCurMatches(starid) {
	let matches = JSON.parse(document.querySelector('#invisible-matches-elem').innerHTML);

	return matches.reduce((accumulator, match) => {
		if (match['mentor starid'] == starid || match['mentee starid'] == starid) {
			accumulator++;
		}
		return accumulator;
	}, 0);
}

function validateManual(inputToValidate) {
	let invalidDateErrorMsg = document.querySelector('#m-invalid-start-end-dates');
	let mentorStaridErrorMsg = document.querySelector('#m-invalid-mentee-starid');
	let menteeStaridErrorMsg = document.querySelector('#m-invalid-mentor-starid');
	let cannotMatchErrorMsg = document.querySelector('#m-cannot-match');

	let enteredStartDate = document.querySelector('#m-start-date').value;
	let enteredEndDate = document.querySelector('#m-end-date').value;
	let startOrEnd = inputToValidate.split(' ')[1];

	let menteeStarid = document.querySelector('#m-mentee-starid').value;
	let mentorStarid = document.querySelector('#m-mentor-starid').value;
	let confirmBtn = document.querySelector('#m-btn-confirm');
	let validMentorStarid = validateStarid('mentor', inputToValidate == 'mentor');
	let validMenteeStarid = validateStarid('mentee', inputToValidate == 'mentee');
	confirmBtn.disabled = true;

	if (!validMenteeStarid || !validMentorStarid) {
		cannotMatchErrorMsg.style.visibility = 'hidden';
	}
	// current matches should never be greater than max matches, so we could use == instead of >=
	else if (countCurMatches(mentorStarid) >= getMaxMatches(mentorStarid) || countCurMatches(menteeStarid) >= getMaxMatches(menteeStarid)) {
		cannotMatchErrorMsg.style.visibility = 'visible';
	}
	else if (enteredStartDate >= enteredEndDate && enteredEndDate != '' && enteredEndDate != '') {
		invalidDateErrorMsg.style.visibility = "visible";
		confirmBtn.disabled = true;
	}
	else {
		invalidDateErrorMsg.style.visibility = "hidden";
		confirmBtn.disabled = false;
	}
}

function validateAuto(inputToValidate) {
	let confirmBtn = document.querySelector('#a-btn-confirm');
	let errorMsgElem = document.querySelector('#a-invalid-start-end-dates');
	confirmBtn.disabled = true;
	
	let enteredStartDate = document.querySelector('#a-start-date').value;
	let enteredEndDate = document.querySelector('#a-end-date').value;

	let startOrEnd = inputToValidate.split(' ')[1];
	console.log(enteredEndDate);

	if (enteredEndDate == '' || enteredEndDate == '') {
		errorMsgElem.style.visibility = "hidden";
	}
	else if (enteredStartDate >= enteredEndDate) {
		errorMsgElem.style.visibility = "visible";
	}
	else {
		errorMsgElem.style.visibility = "hidden";
		confirmBtn.disabled = false;
	}
}

function orderParticipantInfo() {
	let miscInfoHeader = document.getElementById('participant-info-row-name-misc-info');
	let miscInfoValues = document.getElementsByClassName('participant-info-val-misc-info');

	miscInfoHeader.parentNode.appendChild(miscInfoHeader);

	miscInfoHeader.style.gridRow = "25";
	
	for (let i = 0; i < miscInfoValues.length; i++) {
		miscInfoValues[i].parentNode.appendChild(miscInfoValues[i]);
		miscInfoValues[i].style.gridRow = "25";
	}
}

let selectedMatch = "";

function hideAllMatchReasonsExceptSelected() {
	document.getElementById('match-reasons').style.height = '300px';
	Array.from(document.getElementsByClassName('match-reasons-tbl')).forEach(tbl => {
		tbl.style.visibility = "hidden";
		if (tbl.id == selectedMatch) {
			tbl.style.visibility = "visible";
			document.getElementById('match-reasons').style.height = '600px';
		}
	});
}