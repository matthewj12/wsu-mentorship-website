<?php

require 'functions.inc.php';

session_start();

//set the verification code to null
echo updateSignIn(null, $_SESSION['destAddr']);
