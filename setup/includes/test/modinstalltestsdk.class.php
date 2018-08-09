<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Handles all SDK build-specific checks
 *
 * @package setup
 * @subpackage tests
 */
class modInstallTestSdk extends modInstallTest {

    public function run($mode = modInstall::MODE_NEW) {
        return parent::run($mode);
    }

}
