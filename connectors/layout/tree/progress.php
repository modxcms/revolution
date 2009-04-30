<?php
// vim: ts=2:sw=2:nu:fdc=4
/**
  * upload progress file
  *
  * uploadprogress extension doesn't work together with
  * suhosin extension (hardened PHP)
  *
  * @author        Ing. Jozef Sakalos <jsakalos@aariadne.com>
  * @copyright     (c) 2007 by Ing. Jozef Sakalos
  * @date          15. July 2007
  * @version       $Id: progress.php 31 2007-07-16 17:27:13Z jozo $
  * @see           http://www.whenpenguinsattack.com/2006/12/12/how-to-create-a-php-upload-progress-meter/
  * @see           http://pecl.php.net/package/uploadprogress
  * @filesource
  */
require_once dirname(dirname(dirname(__FILE__))).'/index.php';

$buff = '';

if(array_key_exists('UPLOAD_IDENTIFIER', $_POST)) {
  $uid = $_POST['UPLOAD_IDENTIFIER'];
  //$status = uploadprogress_get_info($uid);
  $status = array();
	if(is_array($status)) {
		$status['success'] = true;
	}

//  error_log($uid);
  $buff = $modx->toJSON($status);
}

header('Content-Type: application/json');
header('Content-Size: ' . strlen($buff));
echo $buff;

// end of file