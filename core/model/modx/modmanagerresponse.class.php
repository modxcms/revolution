<?php
/**
 * modManagerResponse
 *
 * @package modx
 */
require_once MODX_CORE_PATH . 'model/modx/modresponse.class.php';
/**
 * Encapsulates an HTTP response from the MODx manager.
 *
 * {@inheritdoc}
 *
 * @package modx
 */
class modManagerResponse extends modResponse {
    /**#@+
     * Creates a modManagerResponse instance.
     *
     * {@inheritdoc}
     */
    function modManagerResponse(& $modx) {
        $this->__construct($modx);
    }
    /** @ignore */
    function __construct(& $modx) {
        parent :: __construct($modx);
    }
    /**#@-*/

    /**
     * Overrides modResponse::outputContent to provide mgr-context specific
     * response.
     *
     * {@inheritdoc}
     */
    function outputContent($options = array()) {
        $modx= & $this->modx;
        $error= & $this->modx->error;

        $action = '';
        if (!isset($this->modx->request) || !isset($this->modx->request->action)) {
            $this->body = $this->modx->error->failure($modx->lexicon('action_err_ns'));
        } else {
            $action =& $this->modx->request->action;
        }

        $this->modx->lexicon->load('dashboard','topmenu','file');
        if ($action == 0) {
            $action = $this->modx->getObject('modAction',array(
                'namespace' => 'core',
                'controller' => 'welcome',
            ));
            $action = $action->get('id');
        }

        if ($this->modx->hasPermission('frames')) {
            if (isset($this->modx->actionMap[$action])) {
                $act = $this->modx->actionMap[$action];

                /* assign custom action topics to smarty, so can load custom topics for each page */
                $this->modx->lexicon->load('action');
                $topics = explode(',',$act['lang_topics']);
                foreach ($topics as $topic) { $this->modx->lexicon->load($topic); }
                $this->modx->smarty->assign('_lang_topics',$act['lang_topics']);
                $this->modx->smarty->assign('_lang',$this->modx->lexicon->fetch());
                $this->modx->smarty->assign('_ctx',$this->modx->context->get('key'));

                $this->body = '';

                /* find context path */
                if (!isset($act['namespace']) || $act['namespace'] == 'core') {
                    $f = $act['namespace_path'].'controllers/'.$act['controller'];

                } else { /* if a custom 3rd party path */
                     $f = $act['namespace_path'].$act['controller'];
                }

                /* set context url and path */
                $this->modx->config['namespace_path'] = $act['namespace_path'];

                /* if action is a directory, load base index.php */
                if (substr($f,strlen($f)-1,1) == '/') {
                    $f .= 'index';
                }
                /* append .php */
                $cbody = '';
                if (file_exists($f.'.php')) {
                    $f = $f.'.php';
                    $cbody = include $f;
                /* for actions that don't have trailing / but reference index */
                } elseif (file_exists($f.'/index.php')) {
                    $f = $f.'/index.php';
                    $cbody = include $f;
                } else {
                    $this->modx->log(MODX_LOG_LEVEL_FATAL,'Could not find action file at: '.$f);
                }

                $this->registerActionDomRules($action);

                /* reset path to core modx path for header/footer */
                $this->modx->smarty->setTemplatePath($modx->getOption('manager_path') . 'templates/' . $this->modx->getOption('manager_theme',null,'default') . '/');

                if ($act['haslayout']) {
                    $this->body .= include $this->modx->getOption('manager_path') . 'controllers/header.php';
                }
                /* assign later to allow for css/js registering */
                if (is_array($cbody)) {
                    $this->modx->smarty->assign('_e', $cbody);
                    $cbody = $this->modx->smarty->fetch('error.tpl');
                }
                $this->body .= $cbody;

                if ($act['haslayout']) {
                    $this->body .= include_once $this->modx->getOption('manager_path').'controllers/footer.php';
                }


            } else {
                $this->body = $this->modx->error->failure('No action with ID '.$action.' found.');
            }
        } else {
            $logoutLink = '[ <a href="' . $modx->getOption('connectors_url') . 'security/logout.php" title="' . $this->modx->lexicon('logout') . '">' . $this->modx->lexicon('logout') . '</a> ]';
            $this->body = $this->modx->error->failure($this->modx->lexicon('permission_denied') . " <small>{$logoutLink}</small>");
        }
        if (empty($this->body)) {
            $this->body = $this->modx->error->failure($modx->lexicon('action_err_ns'));
        }
        if (is_array($this->body)) {
            $this->modx->smarty->assign('_e', $this->body);
            echo $this->modx->smarty->fetch('error.tpl');
        } else {
            echo $this->body;
        }
        exit();
    }

    function registerActionDomRules($action) {
        /* now do action dom rules */
        $userGroups = $this->modx->user->getUserGroups();
        $c = $this->modx->newQuery('modActionDom');
        $c->leftJoin('modAccessActionDom','Access');
        $c->where(array(
            'action' => $action,
        ));
        $c->andCondition(array(
            'Access.principal_class' => 'modUserGroup',
            'Access.principal IN ('.implode(',',$userGroups).')',
        ),null,2);
        $c->orCondition(array(
            'Access.principal IS NULL',
        ),null,2);
        //$c->prepare(); echo $c->toSql(); die();
        $domRules = $this->modx->getCollection('modActionDom',$c);
        $rules = array();
        foreach ($domRules as $rule) {
            $r = $rule->apply();
            if (!empty($r)) $rules[] = $r;
        }
        $ruleOutput = '';
        if (!empty($rules)) {
            $ruleOutput .= '<script type="text/javascript">Ext.onReady(function() {';
            $ruleOutput .= implode("\n",$rules);
            $ruleOutput .= '});</script>';
            $this->modx->regClientStartupHTMLBlock($ruleOutput);
        }
    }
}