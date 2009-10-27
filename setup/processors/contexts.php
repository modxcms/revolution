<?php
/**
 * @package setup
 */
$mode= isset ($_POST['installmode']) ? intval($_POST['installmode']) : modInstall::MODE_NEW;

/* validate database settings */
$install->setConfig($mode);
if ($mode == modInstall::MODE_NEW) {
    $install->getAdminUser();
}

$install->getContextPaths();

/* test context paths and offer bootstrap for context assets if they don't exist */
/* $testResults= $install->testContextPaths($mode); */
$testResults= array (
    'result' => true,
    'content' => ''
);
if (!$testResults['result']) {
    $this->error->setType('error');
    $this->error->failure($testResults['content']);
}
$response= 'summary';

$this->error->success($response);