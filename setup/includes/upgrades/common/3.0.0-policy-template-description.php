<?php
/**
 * Updates descriptions for policy templates using lexicon keys instead plain text.
 */

use MODX\Revolution\modAccessPolicyTemplate;

$templates = $modx->getIterator(
    modAccessPolicyTemplate::class,
    ['name:IN' => modAccessPolicyTemplate::getCoreTemplates()]
);

foreach ($templates as $template) {
    $template->set('description', sprintf(
        'policy_template_%s_desc',
        str_replace('template', '', strtolower($template->get('name')))
    ));
    $template->save();
}
