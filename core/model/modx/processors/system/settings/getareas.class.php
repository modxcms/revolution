<?php
/**
 * Get a list of setting areas
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.system.settings
 */
class modSystemSettingsGetAreasProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('settings');
    }
    public function getLanguageTopics() {
        return array('setting','namespace');
    }

    public function initialize() {
        $this->setDefaultProperties(array(
            'dir' => 'ASC',
            'namespace' => 'core',
        ));
        return true;
    }

    public function process() {
        $c = $this->getQuery();
        if (empty($c)) return $this->failure();

        $list = array();
        if ($c->prepare() && $c->stmt->execute()) {
            while($r = $c->stmt->fetch(PDO::FETCH_NUM)) {
                $area = $r[0];
                $name = $area;
                $namespace = $r[1];
                $count = $r[2];
                if ($namespace != 'core') {
                    $this->modx->lexicon->load($namespace.':default',$namespace.':setting');
                }
                $lex = 'area_'.$name;
                if ($this->modx->lexicon->exists($lex)) {
                    $name = $this->modx->lexicon($lex);
                }
                if (empty($name)) {
                    $name = $this->modx->lexicon('none');
                }
                $list[] = array(
                    'd' => "$name ({$count})",
                    'v' => $area,
                );
            }
        }
        return $this->outputArray($list);
    }

    /**
     * Get the query object for the data
     * @return xPDOQuery
     */
    public function getQuery() {
        $namespace = $this->getProperty('namespace','core');
        $query = $this->getProperty('query');

        $c = $this->modx->newQuery('modSystemSetting');
        $c->setClassAlias('settingsArea');
        $c->leftJoin('modSystemSetting', 'settingsCount', array(
            'settingsArea.' . $this->modx->escape('key') . ' = settingsCount.' . $this->modx->escape('key')
        ));
        if (!empty($namespace)) {
            $c->where(array(
                'settingsArea.namespace' => $namespace,
            ));
        }
        if (!empty($query)) {
            $c->where(array(
                'settingsArea.area:LIKE' => "%{$query}%"
            ));
        }
        $c->select(array(
            'settingsArea.' . $this->modx->escape('area'),
            'settingsArea.' . $this->modx->escape('namespace'),
            'COUNT(settingsCount.' . $this->modx->escape('key') . ') AS num_settings'
        ));
        $c->groupby('settingsArea.' . $this->modx->escape('area') . ', settingsArea.' . $this->modx->escape('namespace'));
        $c->sortby($this->modx->escape('area'),$this->getProperty('dir','ASC'));
        return $c;
    }
}
return 'modSystemSettingsGetAreasProcessor';
