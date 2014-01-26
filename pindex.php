<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
define('MODX_API_MODE', 1);
include 'index.php';

$auth = $modx->user->getUserToken($modx->context->key);

echo 'Auth: ' . $auth;
