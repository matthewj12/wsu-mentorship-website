function showEmailVerify() {
	document.getElementById('lg-step-1-container').hidden = true;
	document.getElementById('ev-container').hidden = false;
}

function showLoginStep1() {
	document.getElementById('lg-step-1-container').hidden = false;
	document.getElementById('ev-container').hidden = true;
}

function showOnlyAdminInputs() {
	document.getElementById('participant-login-inputs').hidden = true;
	document.getElementById('admin-login-inputs').hidden = false;
	document.getElementById('participant-starid-inp').value = '';
}

function showOnlyParticipantInputs() {
	document.getElementById('participant-login-inputs').hidden = false;
	document.getElementById('admin-login-inputs').hidden = true;

	document.getElementById('admin-code-inp').value = '';
}

function disableOrEnableSubmit() {
	let idOfInputToCheck = document.getElementById('admin-login-inputs').hidden ? 'participant-starid-inp' : 'admin-code-inp';
	let inputElem = document.getElementById(idOfInputToCheck);
	let submitBtnElem = document.getElementById('lg-step-1-submit-btn');
	submitBtnElem.disabled = inputElem.value == '';
}
