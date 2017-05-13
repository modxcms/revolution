<?php
/**
 * @var modInstall $install
 * @var modInstallParser $parser
 * @var modInstallRequest $this
 * @package setup
 */
if (!empty($_POST['proceed'])) {
    // Try to increase the time limit as installing a lot of packages may take a while
    @set_time_limit(0);

    // Install selected packages
    $packages = array_key_exists('packages', $_POST) && is_array($_POST['packages']) ? $_POST['packages'] : array();

    foreach ($packages as $package) {
        $this->install->downloadAndInstallPackage($package, array());
    }

    $this->proceed('complete');
}

return $parser->render('distribution.tpl');
