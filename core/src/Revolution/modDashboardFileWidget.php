<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution;


/**
 * A file-based widget that returns only the content of its include.
 *
 * @package MODX\Revolution
 */
class modDashboardFileWidget extends modDashboardWidgetInterface
{
    public function render()
    {
        return $this->content;
    }
}
