<?php
/**
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 * @package modx-test
 * @subpackage data
 */

if (!empty($scriptProperties['fail'])) {
    return $modx->error->failure('A failure message.',array('bad' => true));
}

return $modx->error->success('Success!',array('id' => 123));
 
