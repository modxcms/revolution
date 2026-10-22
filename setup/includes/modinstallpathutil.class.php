<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Setup;

/**
 * Path normalization helpers for the MODX CLI installer.
 *
 * @package setup
 */
class modInstallPathUtil
{
    /**
     * Normalize a filesystem path or URL fragment entered during CLI installation.
     *
     * @param string $value
     * @return string
     */
    public static function normalizePathOrUrl($value)
    {
        $raw = trim($value);
        $isUnc = (bool) preg_match('#^(\\\\|//)#', $raw);
        $isDrive = (bool) preg_match('#^[A-Za-z]:#', $raw);
        $normalized = str_replace('\\', '/', $raw);

        if ($isUnc) {
            $normalized = ltrim($normalized, '/');
            $normalized = '//' . preg_replace('#/+#', '/', $normalized);

            return rtrim($normalized, '/') . '/';
        }

        if (!$isDrive) {
            $normalized = '/' . ltrim($normalized, '/');
        }

        return preg_replace('#/+#', '/', rtrim($normalized, '/') . '/');
    }
}
