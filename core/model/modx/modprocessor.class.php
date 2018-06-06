<?php

if (!class_exists('modProcessor')) {
    class_alias('MODX\modProcessor', 'modProcessor');
}

if (!class_exists('modDeprecatedProcessor')) {
    class_alias('MODX\modDeprecatedProcessor', 'modDeprecatedProcessor');
}

if (!class_exists('modDriverSpecificProcessor')) {
    class_alias('MODX\modDriverSpecificProcessor', 'modDriverSpecificProcessor');
}

if (!class_exists('modObjectProcessor')) {
    class_alias('MODX\modObjectProcessor', 'modObjectProcessor');
}

if (!class_exists('modObjectGetProcessor')) {
    class_alias('MODX\modObjectGetProcessor', 'modObjectGetProcessor');
}

if (!class_exists('modObjectGetListProcessor')) {
    class_alias('MODX\modObjectGetListProcessor', 'modObjectGetListProcessor');
}

if (!class_exists('modObjectCreateProcessor')) {
    class_alias('MODX\modObjectCreateProcessor', 'modObjectCreateProcessor');
}

if (!class_exists('modObjectDuplicateProcessor')) {
    class_alias('MODX\modObjectDuplicateProcessor', 'modObjectDuplicateProcessor');
}

if (!class_exists('modObjectRemoveProcessor')) {
    class_alias('MODX\modObjectRemoveProcessor', 'modObjectRemoveProcessor');
}

if (!class_exists('modObjectSoftRemoveProcessor')) {
    class_alias('MODX\modObjectSoftRemoveProcessor', 'modObjectSoftRemoveProcessor');
}

if (!class_exists('modObjectImportProcessor')) {
    class_alias('MODX\modObjectImportProcessor', 'modObjectImportProcessor');
}

if (!class_exists('modProcessorResponse')) {
    class_alias('MODX\modProcessorResponse', 'modProcessorResponse');
}

if (!class_exists('modProcessorResponseError')) {
    class_alias('MODX\modProcessorResponseError', 'modProcessorResponseError');
}
