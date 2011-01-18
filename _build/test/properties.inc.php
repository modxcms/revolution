<?php
/**
 * Copyright 2010 by MODx, LLC.
 *
 * @package modx-test
 */
$properties = array(
    xPDO::OPT_CACHE_PATH => MODX_CORE_PATH.'cache/',
    xPDO::OPT_HYDRATE_FIELDS => true,
    xPDO::OPT_HYDRATE_RELATED_OBJECTS => true,
    xPDO::OPT_HYDRATE_ADHOC_FIELDS => true,
);

/* PHPUnit test config */
$properties['modx_test_path'] = dirname(__FILE__) . '/';
$properties['modx_config_path'] = '';
$properties['logLevel']= MODx::LOG_LEVEL_ERROR;
$properties['logTarget'] = 'ECHO';
$properties['ctx'] = 'web';
$properties['debug'] = false;
