<?php
/**
 * Updates descriptions for policy templates using lexicon keys instead plain text.
 */

use MODX\Revolution\modAccessPolicy;

$policies = $modx->getIterator(
    modAccessPolicy::class,
    ['name:IN' => modAccessPolicy::getCorePolicies()]
);

foreach ($policies as $policy) {
    $policy->set('description', sprintf(
        'policy_%s_desc',
        str_replace([',', ' '], ['', '_'], strtolower($policy->get('name')))
    ));
    $policy->save();
}
