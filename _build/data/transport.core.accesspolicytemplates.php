<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use MODX\Revolution\modAccessPolicyTemplate;

$templates = [];

foreach (modAccessPolicyTemplate::getCoreTemplates() as $index => $templateName) {

    $templateNameLowered = str_replace('template', '', strtolower($templateName));

    $template = $xpdo->newObject(modAccessPolicyTemplate::class);
    $template->fromArray(
        [
            'id' => $index + 1,
            'name' => $templateName,
            'description' => sprintf('policy_template_%s_desc', $templateNameLowered),
            'lexicon' => 'permissions',
        ]
    );

    $permissions = include __DIR__ . sprintf('/permissions/transport.policy.tpl.%s.php', $templateNameLowered);

    if (is_array($permissions)) {
        $template->addMany($permissions);
    } else {
        $xpdo->log(xPDO::LOG_LEVEL_ERROR, sprintf('Could not load %s Policy Template.', $templateName));
    }

    $templates[] = $template;
}

return $templates;
