<?php
/**
 * Represents a content element that serves as a resource template.
 *
 * @package modx
 */
class modTemplate extends modElement {

    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->setCacheable(false);
    }

    /**
     * Overrides modElement::save to add custom error logging and fire
     * modX-specific events.
     *
     * {@inheritdoc}
     */
    public function save($cacheFlag = null) {
        $isNew = $this->isNew();
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnTemplateBeforeSave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'template' => &$this,
                'cacheFlag' => $cacheFlag,
            ));
        }
        $saved = parent :: save($cacheFlag);

        if ($saved && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnTemplateSave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'template' => &$this,
                'cacheFlag' => $cacheFlag,
            ));
        } else if (!$saved && !empty($this->xpdo->lexicon)) {
            $msg = $isNew ? $this->xpdo->lexicon('template_err_create') : $this->xpdo->lexicon('template_err_save');
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$msg.$this->toArray());
        }
        
        return $saved;
    }

    /**
     * Overrides modElement::remove to add custom error logging.
     *
     * {@inheritdoc}
     */
    public function remove(array $ancestors= array ()) {
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnTemplateBeforeRemove',array(
                'template' => &$this,
                'ancestors' => $ancestors,
            ));
        }
        $removed = parent :: remove($ancestors);

        if ($removed && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnTemplateRemove',array(
                'template' => &$this,
                'ancestors' => $ancestors,
            ));
        } else if (!$removed && !empty($this->xpdo->lexicon)) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$this->xpdo->lexicon('template_err_remove').$this->toArray());
        }

        return $removed;
    }

    /**
     * Process the template content and return the output.
     *
     * {@inheritdoc}
     */
    public function process($properties= null, $content= null) {
        parent :: process($properties, $content);
        if (!$this->_processed) {
            $this->_output= $this->_content;

            if (is_string($this->_output) && !empty($this->_output)) {
                /* turn the processed properties into placeholders */
                $this->toPlaceholders($this->_properties);

                /* collect element tags in the content and process them */
                $maxIterations= intval($this->xpdo->getOption('parser_max_iterations',null,10));
                $this->xpdo->parser->processElementTags($this->_tag, $this->_output, false, false, '[[', ']]', array(), $maxIterations);
            }
            $this->filterOutput();
            $this->_processed= true;
        }
        return $this->_output;
    }

    /**
     * Gets a collection of objects related by aggregate or composite relations.
     *
     * {@inheritdoc}
     *
     * Includes special handling for related objects with alias {@link
     * modTemplateVar}, respecting framework security unless specific criteria
     * are provided.
     */
    public function getMany($class, $criteria= null, $cacheFlag= false) {
        $collection= array ();
        if (($class === 'TemplateVars' || $class === 'modTemplateVar') && ($criteria === null || strtolower($criteria) === 'all')) {
            $c = $this->xpdo->newQuery('modTemplateVar');
            $c->select('
                DISTINCT modTemplateVar.*,
                modTemplateVar.default_text AS value');
            $c->innerJoin('modTemplateVarTemplate','tvtpl',array(
                '`tvtpl`.`tmplvarid` = `modTemplateVar`.`id`',
                '`tvtpl`.templateid' => $this->get('id'),
            ));
            $c->sortby('`tvtpl`.`rank`,`modTemplateVar`.`rank`');

            $collection = $this->xpdo->getCollection('modTemplateVar', $c);
        } else {
            $collection= parent :: getMany($class, $criteria);
        }
        return $collection;
    }

    /**
     * Grabs an array of Template Variables associated with this Template,
     * bypassing the many-to-many relationship.
     *
     * @access public
     * @return array An array of TVs.
     */
    public function getTemplateVars() {
        $c = $this->xpdo->newQuery('modTemplateVar');
        $c->innerJoin('modTemplateVarTemplate','TemplateVarTemplates');
        $c->where(array(
            'TemplateVarTemplates.templateid' => $this->get('id'),
        ));
        $c->sortby('TemplateVarTemplates.rank','ASC');
        return $this->xpdo->getCollection('modTemplateVar',$c);
    }
    /**
     * @deprecated 2009-10-05 Use getTemplateVars instead.
     */
    public function getTVs() {
        return $this->getTemplateVars();
    }
}