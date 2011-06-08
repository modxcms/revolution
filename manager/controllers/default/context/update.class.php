<?php
/**
 * Loads the view context preview page.
 *
 * @package modx
 * @subpackage manager.context
 */
class ContextUpdateManagerController extends modManagerController {
    /** @var string The key of the current context */
    public $contextKey;
    /** @var string The return value from the OnContextFormRender event */
    public $onContextFormRender;
    
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('edit_context');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/widgets/security/modx.grid.access.context.js');
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/widgets/core/modx.grid.settings.js');
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/widgets/system/modx.grid.context.settings.js');
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/widgets/system/modx.panel.context.js');
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/sections/context/update.js');
        $this->modx->regClientStartupHTMLBlock('<script type="text/javascript">
        // <![CDATA[
        MODx.onContextFormRender = "'.$this->onContextFormRender.'";
        MODx.ctx = "'.$this->contextKey.'";
        // ]]>
        </script>');
    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = array()) {
        $placeholders = array();

        /* get context by key */
        $context= $this->modx->getObjectGraph('modContext', '{"ContextSettings":{}}', $scriptProperties['key']);
        if ($context == null) {
            return $this->failure(sprintf($this->modx->lexicon('context_with_key_not_found'), $scriptProperties['key']));
        }
        if (!$context->checkPolicy(array('view' => true, 'save' => true))) return $this->failure($this->modx->lexicon('permission_denied'));

        /* prepare context data for display */
        if (!$context->prepare()) {
            return $this->failure($this->modx->lexicon('context_err_load_data'), $context->toArray());
        }

        /* invoke OnContextFormPrerender event */
        $onContextFormPrerender = $this->modx->invokeEvent('OnContextFormPrerender',array(
            'key' => $context->get('key'),
            'context' => &$context,
            'mode' => modSystemEvent::MODE_UPD,
        ));
        if (is_array($onContextFormPrerender)) $onContextFormPrerender = implode('',$onContextFormPrerender);
        $placeholders['OnContextFormPrerender'] = $onContextFormPrerender;

        /* invoke OnContextFormRender event */
        $this->onContextFormRender = $this->modx->invokeEvent('OnContextFormRender',array(
            'key' => $context->get('key'),
            'context' => &$context,
            'mode' => modSystemEvent::MODE_UPD,
        ));
        if (is_array($this->onContextFormRender)) $this->onContextFormRender = implode('',$this->onContextFormRender);
        $this->onContextFormRender = str_replace(array('"',"\n","\r"),array('\"','',''),$this->onContextFormRender);

        $placeholders['OnContextFormRender'] = $this->onContextFormRender;

        /*  assign context to smarty and display */
        $placeholders['context'] = $context;
        $placeholders['_ctx'] = $context->get('key');
        $this->contextKey = $context->get('key');
        return $placeholders;
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('context').': '.$this->contextKey;
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'context/update.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('context','setting','access','policy','user');
    }
}