<?php
/**
 * Loads phpinfo()
 *
 * @package modx
 * @subpackage manager.system
 */
require_once dirname(dirname(__FILE__)).'/index.php';
echo '<div style="font-size: 1.3em;">';
phpinfo();
echo '</div>';