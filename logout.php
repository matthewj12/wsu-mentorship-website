<?php

require_once('backend/static-files/php/functions.inc.php');

session_start();
sessionUnsetIfSet('admin-logged-in');
sessionUnsetIfSet('participant-logged-in');
header('Location: index.php');