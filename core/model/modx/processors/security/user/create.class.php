<?php

if (!class_exists('modUserCreateProcessor')) {
    class_alias('MODX\Processors\Security\User\Create', 'modUserCreateProcessor');
}