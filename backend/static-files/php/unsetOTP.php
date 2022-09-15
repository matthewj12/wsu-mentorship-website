<?php

require 'functions.inc.php';

session_start();

//set the verification code to null
echo updateParticipant(array(null, $_SESSION["email"]));
