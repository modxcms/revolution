<?php
/**
 * Properly log out the user, running any events and flushing the session.
 *
 * @package modx
 * @subpackage processors.security
 */

class modSecurityLogoutProcessor extends modProcessor {

    public $loginContext;
    public $addContexts;
    public $isMgr;

    public function getLanguageTopics() {
        return array('login');
    }

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize() {
        $this->loginContext = $this->getProperty('login_context', $this->modx->context->get('key'));
        $this->addContexts = $this->getProperty('add_contexts', array());
        $this->addContexts = empty($this->addContexts) ? array() : explode(',', $this->addContexts);
        $this->isMgr = ($this->loginContext == 'mgr');

        return true;
    }

    /**
     * Fire event at the start of logout process
     */
    public function fireBeforeLogoutEvent() {
        $this->modx->invokeEvent($this->isMgr ? 'OnBeforeManagerLogout' : 'OnBeforeWebLogout', array(
            'userid' => $this->modx->user->get('id'),
            'username' => $this->modx->user->get('username'),
            'user' => &$this->modx->user,
            'loginContext' => &$this->loginContext,
            'addContexts' => &$this->addContexts
        ));
    }

    /**
     * Remove user from Session by contexts
     */
    public function removeSessionContexts() {
        $contexts = array_merge(array($this->loginContext), $this->addContexts);
        foreach ($contexts as $loginCtx) {
            $this->modx->user->removeSessionContext($loginCtx);
        }
    }

    /**
     * Fire event after removing user from Session
     */
    public function fireAfterLogoutEvent() {
        $this->modx->invokeEvent($this->isMgr ? 'OnManagerLogout' : 'OnWebLogout', array(
            'userid' => $this->modx->user->get('id'),
            'username' => $this->modx->user->get('username'),
            'user' => &$this->modx->user,
            'loginContext' => &$this->loginContext,
            'addContexts' => &$this->addContexts
        ));
    }

    public function process() {
        if (!$this->modx->user->isAuthenticated($this->loginContext)) {
            return $this->failure($this->modx->lexicon('not_logged_in'));
        }

        $this->fireBeforeLogoutEvent();
        $this->removeSessionContexts();
        $this->fireAfterLogoutEvent();
        return $this->success();
    }
}

return 'modSecurityLogoutProcessor';
