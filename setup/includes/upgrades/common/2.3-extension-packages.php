<?php
/**
 * Common upgrade script in 2.3 for migrating extension_packages system setting to a custom database table
 *
 * @var modX $modx
 * @package setup
 */

/** @var modSystemSetting $extensionPackagesSetting */
$extensionPackagesSetting = $modx->getObject('modSystemSetting',array('key' => 'extension_packages'));
if ($extensionPackagesSetting) {
    $extPackages = $modx->fromJSON($extensionPackagesSetting->get('value'));
    if (!is_array($extPackages)) $extPackages = array();

    if (!empty($extPackages)) {
        foreach ($extPackages as $extPackage) {
            if (!is_array($extPackage)) continue;

            foreach ($extPackage as $packageName => $package) {
                /** @var modNamespace $namespace */
                $namespace = $modx->getObject('modNamespace',array('name' => $packageName));
                if (!empty($package) && !empty($package['path'])) {
                    /* if no correlating Namespace, make one */
                    if (!$namespace) {
                        $namespace = $modx->newObject('modNamespace');
                        $namespace->set('name',$packageName);
                        /* convert old extension_packages format of [[++]] to Namespace format of { } */
                        $namespacePath = str_replace(array(
                            '[[++core_path]]',
                            '[[++base_path]]',
                            '[[++assets_path]]',
                            '[[++manager_path]]',
                        ),array(
                            '{core_path}',
                            '{base_path}',
                            '{assets_path}',
                            '{manager_path}',
                        ),$package['path']);
                        $namespace->set('path',$namespacePath);
                        $namespace->save();
                    }

                    /* Figure out the new ext pack path by making sure its translated path isn't the same as the
                       Namespace path. If it is, just set it to blank, as it will inherit its Namespace path later
                       on and append model/[namespace.name]/. If not, store its raw (with tags) version which
                       will be the absolute path (when translated) to the package model.
                    */
                    $namespacePath = $modx->call('modNamespace','translatePath',array(&$modx,$namespace->get('path')));
                    $oldExtensionPackagePath = str_replace(array(
                        '[[++core_path]]',
                        '[[++base_path]]',
                        '[[++assets_path]]',
                        '[[++manager_path]]',
                    ),array(
                        $modx->getOption('core_path',null,MODX_CORE_PATH),
                        $modx->getOption('base_path',null,MODX_BASE_PATH),
                        $modx->getOption('assets_path',null,MODX_ASSETS_PATH),
                        $modx->getOption('manager_path',null,MODX_MANAGER_PATH),
                    ),$package['path']);
                    $oldExtensionPackagePath = rtrim($oldExtensionPackagePath,'/');
                    $namespacePath = rtrim($namespacePath,'/');
                    $namespacePathWithModel = $namespacePath.'/model';

                    /* ensure doesnt match */
                    $extPackPath = $package['path'];
                    if ($oldExtensionPackagePath == $namespacePath || $oldExtensionPackagePath == $namespacePathWithModel) {
                        $extPackPath = '';
                    }

                    /* skip if it's already there */
                    $exists = $modx->getObject('modExtensionPackage',array(
                        'namespace' => $namespace->get('name'),
                        'path:=' => $extPackPath,
                    ));
                    if (!empty($exists)) { continue; }

                    /** @var modExtensionPackage $packageObject */
                    $packageObject = $modx->newObject('modExtensionPackage');
                    $packageObject->fromArray(array(
                        'namespace' => $namespace->get('name'),
                        'name' => $packageName,
                        'path' => $extPackPath,
                        'table_prefix' => !empty($package['tablePrefix']) ? $package['tablePrefix'] : '',
                        'service_class' => !empty($package['serviceClass']) ? $package['serviceClass'] : '',
                        'service_name' => !empty($package['serviceName']) ? $package['serviceName'] : '',
                        'created_at' => strftime('%Y-%m-%d %H:%M:%S'),
                    ));
                    $packageObject->save();
                }
            }
        }
    }
}