<?php

require_once('backend/static-files/php/functions.inc.php');

session_start();
redirectIfNotLoggedIn('participant');

if (participantExistsInDb($_SESSION['participant-starid'])) {
	header('Location: participant-dashboard-has-taken-survey.php');
}
else {
	header('Location: participant-dashboard-has-not-taken-survey.php');
}