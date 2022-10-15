<?php

require_once('functions.inc.php');

session_start();

insertParticipant($_SESSION['participant-starid']);

header('Location: ../../../participant-dashboard.php');