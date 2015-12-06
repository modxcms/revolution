<?php
if (!file_exists('phpThumb.config.php')) {
	if (file_exists('phpThumb.config.php.default')) {
		echo 'WARNING! "phpThumb.config.php.default" MUST be renamed to "phpThumb.config.php"';
	} else {
		echo 'WARNING! "phpThumb.config.php" should exist but does not';
	}
	exit;
}
header('Location: ./demo/');
?>