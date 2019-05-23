<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Forms\Set;

/**
 * Activate multiple FC Sets
 * @package MODX\Revolution\Processors\Security\Forms\Set
 */
class ActivateMultiple extends Activate
{
    public $sets = [];

    /**
     * @return bool|string|null
     */
    public function initialize()
    {
        $sets = $this->getProperty('sets', '');
        if (empty($sets)) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }
        $this->sets = explode(',', $sets);

        return true;
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        foreach ($this->sets as $set) {
            $this->setProperty('id', $set);
            $initialized = parent::initialize();
            if ($initialized === true) {
                $o = parent::process();
                if (!$o['success']) {
                    return $o;
                }
            } else {
                return $this->failure($initialized);
            }
        }
        return $this->success();
    }
}
