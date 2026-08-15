<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */
namespace MODX\Revolution;

/**
 * A named group of Contexts for manager tree organization and filtering.
 *
 * @property string       $name
 * @property string       $description
 * @property integer      $rank
 *
 * @property modContext[] $Contexts
 *
 * @package MODX\Revolution
 */
class modContextGroup extends \xPDO\Om\xPDOSimpleObject
{
}
