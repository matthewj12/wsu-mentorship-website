<?php

try {
	echo shell_exec('py ../python/main.py');
}
catch (Exception $e) {
	echo $e;
}