<?php
/**
 * Updates descriptions for policy templates groups using lexicon keys instead plain text.
 */

use MODX\Revolution\modAccessPolicyTemplateGroup;

$groups = $modx->getIterator(
    modAccessPolicyTemplateGroup::class,
    ['name:IN' => array_merge(modAccessPolicyTemplateGroup::getCoreGroups(), ['Admin'])]
);

foreach ($groups as $group) {
    if ($group->get('name') === 'Admin') { // Renaming of the old Admin group to new one - Administrator
        $group->set('name', modAccessPolicyTemplateGroup::GROUP_ADMINISTRATOR);
    }
    $group->set('description', sprintf(
        'policy_template_group_%s_desc',
        strtolower($group->get('name'))
    ));
    $group->save();
}
