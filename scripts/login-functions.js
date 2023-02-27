function showEmailVerify() {
	document.getElementById('lg-step-1-container').style.visibility = 'hidden';
	document.getElementById('ev-container').style.visibility = 'visible';
}

function showLoginStep1() {
	document.getElementById('lg-step-1-container').style.visibility = 'visible';
	document.getElementById('ev-container').style.visibility = 'hidden';
}

function showOnlyAdminInputs() {
	document.getElementById('participant-login-inputs').hidden = true;
	document.getElementById('admin-login-inputs').hidden = false;
	document.getElementById('participant-starid-inp').value = '';
}

function showOnlyPartInputs() {
	document.getElementById('participant-login-inputs').hidden = false;
	document.getElementById('admin-login-inputs').hidden = true;
	document.getElementById('admin-code-inp').value = '';
}

function updateSubmitBtn() {
	let id = document.getElementById('admin-login-inputs').hidden ? 'participant-starid-inp' : 'admin-code-inp';
	// let id;
	// if (document.getElementById('admin-code-inp').hidden) {
	// 	id = 'participant-starid-inp';
	// } else {
	// 	id = 'admin-code-inp';
	// }

	let inputElem = document.getElementById(id);
	let submitBtnElem = document.getElementById('lg-step-1-submit-btn');
	// both StarIDs and admin codes are 8 characters long
	// alert(inputElem.value);
	submitBtnElem.disabled = inputElem.value == null || inputElem.value.length != 8;
}


let selectedBtn = 'neither';

let partBtn = document.getElementById("i-am-part");
let adminBtn = document.getElementById("i-am-admin");

function mouseoverPart() {
	if (selectedBtn != 'part') {
		partBtn.style.backgroundColor = 'var(--btn-hover)';
	}
}

function mouseoverAdmin() {
	if (selectedBtn != 'admin') {
		adminBtn.style.backgroundColor = 'var(--btn-hover)';
	}
}

function selectPart() {
	selectedBtn = 'part';
	partBtn.style.backgroundColor = "var(--btn-select)";
	adminBtn.style.backgroundColor = "var(--btn-default)";
}

function selectAdmin() {
	selectedBtn = 'admin';
	adminBtn.style.backgroundColor = "var(--btn-select)";
	partBtn.style.backgroundColor = "var(--btn-default)";
}

function mouseoutPart() {
	if (selectedBtn != 'part') {
		partBtn.style.backgroundColor = 'var(--btn-default)';
	}
}

function mouseoutAdmin() {
	if (selectedBtn != 'admin') {
		adminBtn.style.backgroundColor = 'var(--btn-default)';
	}
}


