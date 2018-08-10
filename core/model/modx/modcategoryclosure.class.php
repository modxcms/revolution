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
 * Closure tables used for grabbing modCategory trees in one query.
 *
 * @property int $ancestor The ancestor of this closure record
 * @property int $descendant The descendant of this closure record
 * @property int $depth The depth this closure rests at
 *
 * @see modCategory
 * @package modx
 */
class modCategoryClosure extends xPDOObject {
}
