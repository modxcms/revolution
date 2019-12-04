<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors;

/**
 * A utility class for pre-2.2-style, or flat file, processors.
 *
 * @deprecated
 *
 * @package MODX\Revolution
 */
class DeprecatedProcessor extends Processor
{
    /**
     * Rather than load a class for processing, include the processor file directly.
     *
     * {@inheritDoc}
     * @return mixed
     */
    public function process()
    {
        $info = 'Flat file processor support, used for action ' . $this->getProperty('action',
                'unknown action') . ' with path ' . $this->path . ',';
        $this->modx->deprecated('2.7.0', '', $info);

        $modx =& $this->modx;
        $scriptProperties = $this->getProperties();
        $o = include $this->path;

        return $o;
    }
}
