<?php
/*
 * MODx Revolution
 *
 * Copyright 2006-2010 by the MODx Team.
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 */

/**
 * Provides the default input filter implementation for modElement processing.
 * @package modx
 * @subpackage filters
 */

/**
 * Base input filter implementation for modElement processing, based on phX.
 *
 * @package modx
 * @subpackage filters
 */
class modInputFilter {
    public $modx= null;

    function __construct(modX &$modx) {
        $this->modx = &$modx;
    }

    /**
     * Filters a modElement before it is processed.
     *
     * @param modElement &$element The element to apply filtering to.
     */
    public function filter(&$element) {
        /* split commands and modifiers and store them as properties for the output filtering */
        $output= $element->get('name');
        $name= $output;
        $splitPos= strpos($output, ':');
        if ($splitPos !== false && $splitPos > 0) {
            $matches= array ();
            $name= substr($output, 0, $splitPos);
            $modifiers= substr($output, $splitPos);
            if (preg_match_all('~:([^:=]+)(?:=`(.*?)`(?=:[^:=]+|$))?~s', $modifiers, $matches)) {
                $element->_properties['filter_commands'] = $matches[1]; /* modifier commands */
                $element->_properties['filter_modifiers'] = $matches[2]; /* modifier values */
            }
        }
        $element->set('name', $name);
    }
}