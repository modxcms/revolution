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
 * Loads the system info page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SystemInfoManagerController extends modManagerController {
    public $pi, $version;
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('view_sysinfo');
    }

    /**
     * Custom logic code here for setting placeholders, etc
     *
     * @param array $scriptProperties
     * @return array
     */
    public function process(array $scriptProperties = array()) {
        $pi = $this->getPhpInfo(INFO_GENERAL);
        $m = $this->parsePHPModules();
        $dbtype_mysql = $this->modx->config['dbtype'] == 'mysql';
        $dbtype_sqlsrv = $this->modx->config['dbtype'] == 'sqlsrv';
        if ($dbtype_mysql && !empty($m['mysql'])) $pi = array_merge($pi,array('mysql' => $m['mysql']));
        if ($dbtype_mysql && !empty($m['mysqlnd'])) $pi = array_merge($pi,array('pdo' => $m['mysqlnd']));
        if ($dbtype_sqlsrv && !empty($m['sqlsrv'])) $pi = array_merge($pi,array('sqlsrv' => $m['sqlsrv']));
        if (!empty($m['PDO'])) $pi = array_merge($pi,array('pdo' => $m['PDO']));
        if ($dbtype_mysql && !empty($m['pdo_mysql'])) $pi = array_merge($pi,array('pdo_mysql' => $m['pdo_mysql']));
        if ($dbtype_sqlsrv && !empty($m['pdo_sqlsrv'])) $pi = array_merge($pi,array('pdo_sqlsrv' => $m['pdo_sqlsrv']));
        if (!empty($m['zip'])) $pi = array_merge($pi,array('zip' => $m['zip']));
        $mailerService = $this->modx->getService('mail', 'mail.modPHPMailer');
        $this->version = [
            'smarty'=> $this->modx->smarty->_version,
            'PHPMailer'=> $mailerService->mailer::VERSION
        ];

        $this->pi = array_merge($pi,$this->getPhpInfo(INFO_CONFIGURATION));
        return array(
            'pi' => $this->pi,
        );

    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $this->addJavascript($this->modx->getOption('manager_url')."assets/modext/widgets/system/{$this->modx->getOption('dbtype')}/modx.grid.databasetables.js");
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/widgets/resource/modx.grid.resource.active.js');
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/sections/system/info.js');
        $this->addHtml('<script>
        Ext.onReady(function() {
            MODx.load({
                xtype: "modx-page-system-info"
                ,data: '.$this->modx->toJSON($this->pi).'
                ,version: '. $this->modx->toJSON($this->version) .'
            });
        });
        </script>');
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('view_sysinfo');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return '';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('system_info');
    }

    public function getPhpInfo($type = -1) {
         ob_start();
         phpinfo($type);

         $pi = preg_replace(
         array('#^.*<body>(.*)</body>.*$#ms', '#<h2>PHP License</h2>.*$#ms',
         '#<h1>Configuration</h1>#',  "#\r?\n#", "#</(h1|h2|h3|tr)>#", '# +<#',
         "#[ \t]+#", '#&nbsp;#', '#  +#', '# class=".*?"#', '%&#039;%',
          '#<tr>(?:.*?)" src="(?:.*?)=(.*?)" alt="PHP Logo" /></a>'
          .'<h1>PHP Version (.*?)</h1>(?:\n+?)</td></tr>#',
          '#<h1><a href="(?:.*?)\?=(.*?)">PHP Credits</a></h1>#',
          '#<tr>(?:.*?)" src="(?:.*?)=(.*?)"(?:.*?)Zend Engine (.*?),(?:.*?)</tr>#',
          "# +#", '#<tr>#', '#</tr>#'),
         array('$1', '', '', '', '</$1>' . "\n", '<', ' ', ' ', ' ', '', ' ',
          '<h2>PHP Configuration</h2>'."\n".'<tr><td>PHP Version</td><td>$2</td></tr>'.
          "\n".'<tr><td>PHP Egg</td><td>$1</td></tr>',
          '<tr><td>PHP Credits Egg</td><td>$1</td></tr>',
          '<tr><td>Zend Engine</td><td>$2</td></tr>' . "\n" .
          '<tr><td>Zend Egg</td><td>$1</td></tr>', ' ', '%S%', '%E%'),
         ob_get_clean());

         $sections = explode('<h2>', strip_tags($pi, '<h2><th><td>'));
         unset($sections[0]);

         $pi = array();
         foreach($sections as $section){
           $n = substr($section, 0, strpos($section, '</h2>'));
           preg_match_all(
           '#%S%(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?%E%#',
             $section, $askapache, PREG_SET_ORDER);
           foreach($askapache as $m)
               $pi[$n][$m[1]]=(!isset($m[3])||$m[2]==$m[3])?$m[2]:array_slice($m,2);
         }
         return $pi;
    }

    public function parsePHPModules() {
        ob_start();
        phpinfo(INFO_MODULES);
        $s = ob_get_contents();
        ob_end_clean();

        $s = strip_tags($s,'<h2><th><td>');
        $s = preg_replace('/<th[^>]*>([^<]+)<\/th>/',"<info>\\1</info>",$s);
        $s = preg_replace('/<td[^>]*>([^<]+)<\/td>/',"<info>\\1</info>",$s);
        $vTmp = preg_split('/(<h2>[^<]+<\/h2>)/',$s,-1,PREG_SPLIT_DELIM_CAPTURE);
        $vModules = array();
        for ($i=1;$i<count($vTmp);$i++) {
            if (preg_match('/<h2>([^<]+)<\/h2>/',$vTmp[$i],$vMat)) {
                $vName = trim($vMat[1]);
                $vTmp2 = explode("\n",$vTmp[$i+1]);
                foreach ($vTmp2 AS $vOne) {
                    $vPat = '<info>([^<]+)<\/info>';
                    $vPat3 = "/$vPat\s*$vPat\s*$vPat/";
                    $vPat2 = "/$vPat\s*$vPat/";
                    if (preg_match($vPat3,$vOne,$vMat)) { // 3cols
                        $vModules[$vName][trim($vMat[1])] = array(trim($vMat[2]),trim($vMat[3]));
                    } elseif (preg_match($vPat2,$vOne,$vMat)) { // 2cols
                        $vModules[$vName][trim($vMat[1])] = trim($vMat[2]);
                    }
                }
            }
        }
        return $vModules;
    }
}
