<?php

session_start();

require_once('functions.inc.php');

insertParticipant($_SESSION['starid']);

header('Location: ../../../participant-homepage.php');