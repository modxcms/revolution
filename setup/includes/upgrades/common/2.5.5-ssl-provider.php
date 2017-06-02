<?php
/**
 * Common upgrade script for 2.5.5 to update provider URL to SSL
 *
 * @var modX $modx
 * @package setup
 */

$nonSSLProvider = $modx->getObject('transport.modTransportProvider', array('service_url' => 'http://rest.modx.com/extras/'));
if ($nonSSLProvider) {
    $nonSSLProvider->set('service_url', 'https://rest.modx.com/extras/');
    $nonSSLProvider->save();
}
