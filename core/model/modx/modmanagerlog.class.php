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
 * Stores records of all manager actions.
 *
 * @property int $user The user ID of the actor
 * @property datetime $occurred The time the action occurred.
 * @property string $action The action key that occurred.
 * @property string $classKey The class name of the object that was acted upon.
 * @property string $item The PK of the class type defined by classKey that was acted upon.
 *
 * @package modx
 */
class modManagerLog extends xPDOSimpleObject {}
