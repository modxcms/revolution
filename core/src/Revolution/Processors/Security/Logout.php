<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Security;

use MODX\Revolution\modUser;
use MODX\Revolution\Processors\Processor;

/**
 * Properly log out the user, running any events and flushing the session.
 */
class Logout extends Processor
{
    public $loginContext;

    public $addContexts;

    public $isMgr;

    /** @var modUser User who is logging out (preserved for after-logout events). */
    public $user;

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['login'];
    }

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $this->loginContext = $this->getProperty('login_context', $this->modx->context->get('key'));
        $this->addContexts = $this->getProperty('add_contexts', []);
        $this->addContexts = empty($this->addContexts) ? [] : explode(',', $this->addContexts);
        $this->isMgr = ($this->loginContext === 'mgr');

        return true;
    }

    /**
     * Fire event at the start of logout process
     */
    public function fireBeforeLogoutEvent()
    {
        $this->modx->invokeEvent($this->isMgr ? 'OnBeforeManagerLogout' : 'OnBeforeWebLogout', [
            'userid' => $this->modx->user->get('id'),
            'username' => $this->modx->user->get('username'),
            'user' => &$this->modx->user,
            'loginContext' => &$this->loginContext,
            'addContexts' => &$this->addContexts
        ]);
    }

    /**
     * Remove user from Session by contexts
     */
    public function removeSessionContexts()
    {
        $contexts = array_merge([$this->loginContext], $this->addContexts);
        foreach ($contexts as $loginCtx) {
            $this->modx->user->removeSessionContext($loginCtx);
        }
    }

    /**
     * Reload $modx->user from session tokens after logout contexts were removed,
     * while keeping the logged-out user available for OnWebLogout / OnManagerLogout.
     */
    public function refreshUserAfterLogout()
    {
        $this->user = $this->modx->user;
        $this->modx->user = null;
        // Do not pass $forceLoadSettings=true: that reloads user settings into
        // $modx->config / session for a context that was just cleared.
        $this->modx->getUser($this->loginContext);
    }

    /**
     * Fire event after removing user from Session
     */
    public function fireAfterLogoutEvent()
    {
        if (!$this->user instanceof modUser) {
            $this->user = $this->modx->user;
        }
        $this->modx->invokeEvent($this->isMgr ? 'OnManagerLogout' : 'OnWebLogout', [
            'userid' => $this->user->get('id'),
            'username' => $this->user->get('username'),
            'user' => &$this->user,
            'loginContext' => &$this->loginContext,
            'addContexts' => &$this->addContexts
        ]);
    }

    public function process()
    {
        if (!$this->modx->user->isAuthenticated($this->loginContext)) {
            return $this->failure($this->modx->lexicon('not_logged_in'));
        }

        $this->fireBeforeLogoutEvent();
        $this->removeSessionContexts();
        $this->refreshUserAfterLogout();
        $this->fireAfterLogoutEvent();

        return $this->success();
    }
}
