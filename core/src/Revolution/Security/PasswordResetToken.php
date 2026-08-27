<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Security;

use MODX\Revolution\modUser;
use MODX\Revolution\modX;

/**
 * Signs and verifies the manager password-reset / magic-login tokens that are
 * stored in the database-backed "user" register (topics /pwd/change/ and
 * /pwd/magiclink/).
 *
 * The register is a generic, writable message bus; storing a bare username as
 * the token value lets anyone who can reach the registry Send processor forge a
 * reset token for any account. To make the token unforgeable without changing
 * who may use the bus, the stored value is bound to the target user with an
 * HMAC keyed on that user's own stored password hash (plus salt and id) — a
 * per-user secret that is never exposed and cannot be reproduced by an
 * attacker. The token is verified against the same key on consumption; a forged
 * or legacy (bare-string) value fails verification.
 *
 * @package MODX\Revolution\Security
 */
final class PasswordResetToken
{
    /**
     * Build the signed value to store as the register message for a token.
     *
     * @param modUser $user  The user the token authorizes.
     * @param string  $topic The register topic the token is issued for.
     * @param string  $hash  The token hash (the register message key).
     *
     * @return array The value to store, as [$hash => self::sign(...)].
     */
    public static function sign(modUser $user, string $topic, string $hash): array
    {
        return [
            'u' => $user->get('username'),
            'm' => self::mac($user, $topic, $hash, $user->get('username')),
        ];
    }

    /**
     * Verify a value read back from the register and return the user it
     * authorizes, or null if the value is missing, malformed, in the legacy
     * bare-string format, names an unknown user, or fails the signature check.
     *
     * @param modX   $modx
     * @param string $topic The register topic the token is being consumed for.
     * @param string $hash  The token hash submitted by the client.
     * @param mixed  $value The value read from the register (reset($record)).
     *
     * @return modUser|null
     */
    public static function verify(modX $modx, string $topic, string $hash, $value): ?modUser
    {
        if (!is_array($value) || !isset($value['u'], $value['m'])) {
            return null;
        }
        $username = (string)$value['u'];

        /** @var modUser $user */
        $user = $modx->getObject(modUser::class, ['username' => $username]);
        if (!$user) {
            return null;
        }

        $expected = self::mac($user, $topic, $hash, $username);
        return hash_equals($expected, (string)$value['m']) ? $user : null;
    }

    /**
     * Compute the token MAC. The key is per-user, in-DB, and scrubbed from every
     * output path; the message binds the topic so a token issued for one topic
     * cannot be replayed against another.
     *
     * @param modUser $user
     * @param string  $topic
     * @param string  $hash
     * @param string  $username
     *
     * @return string
     */
    private static function mac(modUser $user, string $topic, string $hash, string $username): string
    {
        $key = $user->get('password') . '|' . $user->get('salt') . '|' . $user->get('id');
        return hash_hmac('sha256', $topic . '|' . $hash . '|' . $username, $key);
    }
}
