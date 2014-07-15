<?php
/*
 * Copyright 2010-2014 by MODX, LLC.
 *
 * This file is part of xPDO.
 *
 * xPDO is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * xPDO is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * xPDO; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 */

/**
 * Metadata map for the xPDOSimpleObject class.
 *
 * Provides an integer primary key column which uses SQLite's native
 * integer primary key generation facilities.
 *
 * @see xPDOSimpleObject
 * @package xpdo
 * @subpackage om.sqlite
 */
$xpdo_meta_map['xPDOSimpleObject'] = array (
    'table' => null,
    'fields' => array (
        'id' => null,
    ),
    'fieldMeta' => array (
        'id' => array(
            'dbtype' => 'INTEGER',
            'phptype' => 'integer',
            'null' => false,
            'index' => 'pk',
            'generated' => 'native',
        )
    ),
    'indexes' => array (
        'PRIMARY' => array (
            'columns' => array(
                'id' => array()
            ),
            'primary' => true,
            'unique' => true
        )
    )
);
