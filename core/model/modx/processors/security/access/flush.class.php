<?php

if (!class_exists('modFlushPermissionsProcessor')) {
    class_alias('MODX\Processors\Security\Access\Flush', 'modFlushPermissionsProcessor');
}