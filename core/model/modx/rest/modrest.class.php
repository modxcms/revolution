<?php

if (!class_exists('modRest')) {
    class_alias('MODX\Rest\modRest', 'modRest');
}

if (!class_exists('RestClientRequest')) {
    class_alias('MODX\Rest\RestClientRequest', 'RestClientRequest');
}

if (!class_exists('RestClientResponse')) {
    class_alias('MODX\Rest\RestClientResponse', 'RestClientResponse');
}
