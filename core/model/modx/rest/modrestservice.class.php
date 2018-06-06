<?php

if (!class_exists('modRestService')) {
    class_alias('MODX\Rest\modRestService', 'modRestService');
}

if (!class_exists('modRestServiceRequest')) {
    class_alias('MODX\Rest\modRestServiceRequest', 'modRestServiceRequest');
}

if (!class_exists('modRestServiceResponse')) {
    class_alias('MODX\Rest\modRestServiceResponse', 'modRestServiceResponse');
}
