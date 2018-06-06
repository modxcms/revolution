<?php

if (!class_exists('modUserDuplicateProcessor')) {
    class_alias('MODX\Processors\Security\User\Duplicate', 'modUserDuplicateProcessor');
}