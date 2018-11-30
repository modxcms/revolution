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
 * Regenerate the system's Resource URIs in the database
 *
 * @package modx
 * @subpackage system
 */
class modSystemRefreshurisProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('empty_cache');
    }

    public function process() {
        $this->modx->call('modResource', 'refreshURIs', array(&$this->modx));
        $result = true; // refreshURIs void response
        $output = $this->modx->lexicon('refresh_' . ( $result ? 'success' : 'failure') );
        $this->modx->log(modX::LOG_LEVEL_INFO, $output );

        return $this->success( $output );
    }
}
return 'modSystemRefreshurisProcessor';
