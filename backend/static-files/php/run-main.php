<?php

try {
	shell_exec('py backend/static-files/python/main.py');
}
catch (Exception $e) {
	echo $e;
}