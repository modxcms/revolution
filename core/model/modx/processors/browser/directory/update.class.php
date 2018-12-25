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
 * Renames a directory.
 *
 * @deprecated
 *
 * @param string $dir The directory to rename
 * @param boolean $prependPath (optional) If true, will prepend rb_base_dir to
 * the final path
 *
 * @package modx
 * @subpackage processors.browser.directory
 */

require_once dirname(__FILE__) . '/rename.class.php';

class modBrowserFolderUpdateProcessor extends modBrowserFolderRenameProcessor
{
}

return 'modBrowserFolderUpdateProcessor';
