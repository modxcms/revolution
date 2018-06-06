<?php

if (!class_exists('modRestClient')) {
    class_alias('MODX\Rest\modRestClient', 'modRestClient');
}

if (!class_exists('modRestResponse')) {
    class_alias('MODX\Rest\modRestResponse', 'modRestResponse');
}
