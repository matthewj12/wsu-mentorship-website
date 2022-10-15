let mentorsSelected = true;
// manual match confirmation
selectedMatchModeIdPrefix = 'mmc';

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

function updateResults() {
	let group = mentorsSelected ? 'mentor-row' : 'mentee-row';

	let filter = document.getElementById('starid-search-box').value.toLowerCase();
	let results = document.getElementsByClassName('participant-row');

	for (let i = 0; i < results.length; i++) {
		if (results[i].id.split('-')[2].includes(filter)) {
			results[i].style.visibility = "visible";
		}
		else {
			results[i].style.visibility = "hidden";
		}
	}
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


function setMentorsSelected(p_mentorsSelected) {
	let selectedBtnClass = "btn btn-primary";
	let unselectedBtnClass = "btn btn-info";

	mentorsSelected = p_mentorsSelected;

	if (mentorsSelected) {
		replaceXWithY('Mentee', 'Mentor');
		showMentorsMentees(false);
		menteeBtnClass = unselectedBtnClass
		mentorBtnClass = selectedBtnClass;
	}
	else {
		replaceXWithY('Mentor', 'Mentee');
		showMentorsMentees(true);
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
		toUnselect[i].style.borderWidth = "var(--unselected-border-width)";
		toUnselect[i].style.zIndex = "0";
	}
}

let selectedStarid = "";
function setSelectedStarid(newSelectedStarid) {
	selectedStarid = newSelectedStarid;

	clearStaridBorders();
	
	if (selectedStarid != "") {
		// set border to black
		let starid = document.getElementById('participant-dashboard-'+selectedStarid);
		starid.style.borderColor = "var(--selected-border-color)";
		// starid.style.borderWidth = "var(--selected-border-width)";

		let matchStarids = document.getElementsByClassName('match-starid-'+selectedStarid);
		for (let i = 0; i < matchStarids.length; i++) {
			matchStarids[i].style.borderColor = "var(--selected-border-color";
			matchStarids[i].style.borderWidth = "var(--selected-border-width)";
			matchStarids[i].style.zIndex = "1";
		}
	}
}

function hideAllParticipantInfoExceptSelected() {
	let infoDivs = document.getElementsByClassName('participant-info-values-set');
	
	if (selectedStarid == "") {
		let infoDivs = document.getElementsByClassName('participant-info-values-set');
		for (let i = 0; i < infoDivs.length; i++) {
			infoDivs[i].visibility = "collapsed";
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

function hideCreateMatchesConfirmation() {
	enableAllButtons();
	
	let toHide = document.getElementsByClassName("mc");

	for (let i = 0; i < toHide.length; i++) {
		toHide[i].hidden = true
	}

	let toUnrequire = document.getElementsByTagName('input');
	for (let i = 0; i < toUnrequire.length; i++) {
		toUnrequire[i].required = false;
	}
}

function showCreateMatchesConfirmation() {
	// hide all version of the confirmation dialog box to start with
	hideCreateMatchesConfirmation();
	hideInvalidMessages();
	disableAllButtonsExceptConfirmCancel();

	if (selectedMatchModeIdPrefix != 'a') {
		document.getElementById(selectedMatchModeIdPrefix + "-mentor-starid").value = "";
		document.getElementById(selectedMatchModeIdPrefix + "-mentee-starid").value = "";
	}

	let toShow = document.getElementById(selectedMatchModeIdPrefix + 'mc');
	toShow.hidden = false;
	toShow.required = true;

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

function validateStarid(inputToValidate) {
	// build list of valid starids
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

	let errorMsgElem, starid;
	// check that starid is in that list
	errorMsgElem = document.getElementById(selectedMatchModeIdPrefix + '-invalid-' + inputToValidate + '-starid');

	starid = document.getElementById(selectedMatchModeIdPrefix + '-' + inputToValidate + '-starid').value;

	let starids = inputToValidate == 'mentor' ? mentorStarids : menteeStarids;

	if (!(starids.includes(starid) || starid == '')) {
		errorMsgElem.style.visibility = "visible";
		return false;
	}
	else {
		errorMsgElem.style.visibility = "hidden";
		return true;
	}
}

function validateExtend(inputToValidate) {
	let menteeStarid = document.querySelector('#e-mentee-starid').value;
	let mentorStarid = document.querySelector('#e-mentor-starid').value;

	let confirmBtn = document.querySelector('#e-btn-confirm');
	confirmBtn.disabled = false;
	
	if ((inputToValidate != 'date' && !validateStarid(inputToValidate)) || (menteeStarid == '' || mentorStarid == '')) {
		confirmBtn.disabled = true;
		return;
	}

	let matches = JSON.parse(document.querySelector('#invisible-matches-elem').innerHTML);
	let isExtendable, maxExtendDate;

	// ensure there is a match between them
	let matchExists = false;
	for (let i = 0; i < matches.length; i++) {
		if (matches[i]['mentor starid'] == mentorStarid && matches[i]['mentee starid'] == menteeStarid) {
			matchExists = true;
			isExtendable = matches[i]['is extendable'] == '1' ? true : false;
			maxExtendDate = matches[i]['earlier grad date']
		}
	}

	if (!matchExists) {
		document.querySelector('#no-match-exists').style.visibility = "visible";
		document.querySelector('#invalid-match-to-extend').style.visibility = "hidden";
		confirmBtn.disabled = true;
		return;
	}
	else {
		document.querySelector('#no-match-exists').style.visibility = "hidden";

		if (!isExtendable) {
			document.querySelector('#invalid-match-to-extend').style.visibility = "visible";
			confirmBtn.disabled = true;
		}
		else {
			document.querySelector('#invalid-match-to-extend').style.visibility = "hidden";
		}
	}

	let enteredDate = document.querySelector('#e-end-date').value;
	if (enteredDate == '') {
		confirmBtn.disabled = true;
	}

	else if (inputToValidate == 'date') {
		if (enteredDate > maxExtendDate) {
			document.querySelector('#e-invalid-end-date').style.visibility = "visible";
			confirmBtn.disabled = true;
		}
		else {
			document.querySelector('#e-invalid-end-date').style.visibility = "hidden";
		}
	}
}

function validateDates() {

}

function validateManual(inputToValidate) {
	let menteeStarid = document.querySelector('#m-mentee-starid').value;
	let mentorStarid = document.querySelector('#m-mentor-starid').value;

	let confirmBtn = document.querySelector('#m-btn-confirm');
	confirmBtn.disabled = false;
	
	if ((!inputToValidate.includes('date') && !validateStarid(inputToValidate)) || (menteeStarid == '' || mentorStarid == '')) {
		confirmBtn.disabled = true;
		return;
	}

	let enteredStartDate = document.querySelector('#m-start-date').value;
	let enteredEndDate = document.querySelector('#m-end-date').value;

	if (inputToValidate.includes('date')) {
		let startOrEnd = inputToValidate.split(' ')[1];
		
		if (enteredStartDate >= enteredEndDate) {
			document.querySelector('#m-invalid-start-end-dates').style.visibility = "visible";
			confirmBtn.disabled = true;
		}
		else {
			document.querySelector('#m-invalid-start-end-dates').style.visibility = "hidden";
		}
	}
}

function validateAuto(inputToValidate) {
	let confirmBtn = document.querySelector('#a-btn-confirm');
	confirmBtn.disabled = false;
	
	let enteredStartDate = document.querySelector('#a-start-date').value;
	let enteredEndDate = document.querySelector('#a-end-date').value;

	if (inputToValidate.includes('date')) {
		let startOrEnd = inputToValidate.split(' ')[1];

		if (enteredStartDate >= enteredEndDate) {
			document.querySelector('#a-invalid-start-end-dates').style.visibility = "visible";
			confirmBtn.disabled = true;
		}
		else {
			document.querySelector('#a-invalid-start-end-dates').style.visibility = "hidden";
		}
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
