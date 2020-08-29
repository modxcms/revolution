<?php
/**
 * Common upgrade script for modify modTemplateVar output params
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modTemplateVar;

/** @var modTemplateVar $tvs */
$tvs = $modx->getCollection(modTemplateVar::class);

if ($tvs) {

    foreach ($tvs as $tv) {
        $output_properties = $tv->get('output_properties');

        if (!empty($output_properties['tagid'])) {
            $output_properties['id'] = $output_properties['tagid'];
        }
        if (!empty($output_properties['attrib'])) {
            $output_properties['attributes'] = $output_properties['attrib'];
        }
        unset($output_properties['tagid']);
        unset($output_properties['attrib']);

        $tv->set('output_properties', $output_properties);
        $tv->save();
    }

}