<?php
/**
 * Common upgrade script for modify modTemplateVar params
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modTemplateVar;

/** @var modTemplateVar $tvs_num */
$tvs_num = $modx->getCollection(modTemplateVar::class, ['type' => 'number']);

if ($tvs_num) {

    foreach ($tvs_num as $tv_num) {
        $input_properties = $tv_num->get('input_properties');

        if ($input_properties['allowNegative'] == 'false') {
            $input_properties['minValue'] = '0';
        }
        unset($input_properties['allowNegative']);

        $tv_num->set('input_properties', $input_properties);
        $tv_num->save();
    }

}
