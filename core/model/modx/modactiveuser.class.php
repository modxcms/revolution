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
 * Represents activity by a user in the system.
 *
 * @property int $internalKey The ID of the User referred to
 * @property string $username The username of the User referred to
 * @property int $lasthit The last time this User accessed
 * @property int $id Deprecated
 * @property string $action The modAction this User last accessed
 * @property string $ip The IP of the User
 *
 * @see modUser
 * @package modx
 */
class modActiveUser extends xPDOObject {
    /**
     * Overrides xPDOObject::__construct to set the _cacheFlag var for this class to false.
     *
     * @param xPDO $xpdo Reference to the xPDO|modX instance
     */
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->_cacheFlag= false;
    }
}
