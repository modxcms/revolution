<?php

if (!class_exists('modPackageCheckForUpdatesProcessor')) {
    class_alias('MODX\Processors\Workspace\Packages\UpdateRemote', 'modPackageCheckForUpdatesProcessor');
}