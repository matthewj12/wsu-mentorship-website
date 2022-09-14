<?php

require 'backend\static-files\php\functions.inc.php';

session_start();

//set the verification code to null
echo updateParticipant(array(null, $_SESSION["email"]));
