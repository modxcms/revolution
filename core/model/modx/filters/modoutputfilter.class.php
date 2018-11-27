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
 * Provides the default output filter implementation for modElement processing.
 * @package modx
 * @subpackage filters
 */

/**
 * Base output filter implementation for modElement processing, based on phX.
 *
 * @package modx
 * @subpackage filters
 */
class modOutputFilter {
    /**
     * @var modX A reference to the modX instance
     */
    public $modx= null;

    /**
     * @param modX $modx A reference to the modX instance
     * @return modOutputFilter A new instance of the modOutputFilter class
     */
    function __construct(modX &$modx) {
        $this->modx= &$modx;
    }

    /**
     * Filters the output
     *
     * @param modElement $element The element to filter
     */
    public function filter(&$element) {
        $usemb = function_exists('mb_strlen') && (boolean)$this->modx->getOption('use_multibyte',null,false);
        $encoding = $this->modx->getOption('modx_charset',null,'UTF-8');

        $output= & $element->_output;
        $inputFilter = $element->getInputFilter();
        if ($inputFilter !== null && $inputFilter->hasCommands()) {
            $modifier_cmd = $inputFilter->getCommands();
            $modifier_value = $inputFilter->getModifiers();
            $count = count($modifier_cmd);
            $condition = array();

            for ($i= 0; $i < $count; $i++) {

                $m_cmd = $modifier_cmd[$i];
                $m_val = $modifier_value[$i];

                $this->log('Processing Modifier: ' . $m_cmd . ' (parameters: ' . $m_val . ')');

                $output = trim($output);

                try {
                    switch ($m_cmd) {
                        /* conditional operators */
                        /* @todo these conditionals should be removed because there are cleaner ways to do this now */
                        case 'input':
                        case 'if':
                            $output= $m_val;
                            break;
                        case 'eq':
                        case 'is':
                        case 'equals':
                        case 'equalto':
                        case 'isequal':
                        case 'isequalto':
                            $condition[]= intval(($output == $m_val));
                            break;
                        case 'ne':
                        case 'neq':
                        case 'isnot':
                        case 'isnt':
                        case 'notequals':
                        case 'notequalto':
                            $condition[]= intval(($output != $m_val));
                            break;
                        case 'gte':
                        case 'isgte':
                        case 'eg':
                        case 'ge':
                        case 'equalorgreaterthan':
                        case 'greaterthanorequalto':
                            $condition[]= intval(($output >= $m_val));
                            break;
                        case 'lte':
                        case 'islte':
                        case 'le':
                        case 'el':
                        case 'lessthanorequalto':
                        case 'equaltoorlessthan':
                            $condition[]= intval(($output <= $m_val));
                            break;
                        case 'gt':
                        case 'isgt':
                        case 'greaterthan':
                        case 'isgreaterthan':
                            $condition[]= intval(($output > $m_val));
                            break;
                        case 'lt':
                        case 'islt':
                        case 'lessthan':
                        case 'lowerthan':
                        case 'islessthan':
                        case 'islowerthan':
                            $condition[]= intval(($output < $m_val));
                            break;
                        case 'contains':
                            $condition[]= intval(stripos($output, $m_val) !== false);
                            break;
                        case 'containsnot':
                            $condition[]= intval(stripos($output, $m_val) === false);;
                            break;
                        case 'ismember':
                        case 'memberof':
                        case 'mo': /* Is Member Of  (same as inrole but this one can be stringed as a conditional) */
                            if (empty($output) || $output == "&_PHX_INTERNAL_&") {
                                $output= $this->modx->user->get('id');
                            }
                            $grps= (strlen($m_val) > 0) ? explode(',', $m_val) : array ();
                            /** @var $user modUser */
                            $user = $this->modx->getObject('modUser',$output);
                            if ($user && is_object($user) && $user instanceof modUser) {
                                $condition[]= (int) $user->isMember($grps);
                            } else {
                                $condition[] = 0;
                            }
                            break;
                        case 'or':
                            $condition[]= "||";
                            break;
                        case 'and':
                            $condition[]= "&&";
                            break;
                        case 'hide':
                            if ($this->validateConditionals($condition)) {
                                $output = self::parseConditions($condition, null, $output);
                            }
                            break;
                        case 'show':
                            if ($this->validateConditionals($condition)) {
                                $output = self::parseConditions($condition, null, $output, true);
                            }

                            break;
                        case 'then':
                            if ($this->validateConditionals($condition)) {
                                $output = self::parseConditions($condition, $m_val, null);
                            }

                            break;
                        case 'else':
                            if ($this->validateConditionals($condition)) {
                                $output = self::parseConditions($condition, $m_val, $output, true);
                            }
                            break;
                        case 'select':
                            $raw= explode("&", $m_val);
                            $map= array ();
                            for ($m= 0; $m < (count($raw)); $m++) {
                                $mi= explode("=", $raw[$m]);
                                $map[$mi[0]]= $mi[1];
                            }
                            $output = (isset($map[$output])) ? $map[$output] : '';
                            break;
                            /* #####  End of Conditional Modifiers */

                        /* #####  String Modifiers */
                        case 'cat': /* appends the options value (if not empty) to the input value */
                            if (!empty($m_val))
                                $output = $output . $m_val;
                            break;
                        case 'after':
                        case 'append': /* appends the options value to the input value (if both not empty) */
                            if (!empty($m_val) && !empty($output))
                                $output = $output.$m_val;
                            break;
                        case 'before':
                        case 'prepend': /* prepends the options value to the input value (if both not empty) */
                            if (!empty($m_val) && !empty($output))
                                $output = $m_val.$output;
                            break;
                        case 'lcase':
                        case 'lowercase':
                        case 'strtolower':
                            /* See PHP's strtolower - http://www.php.net/manual/en/function.strtolower.php */
                            $output = $usemb ? mb_strtolower($output,$encoding) : strtolower($output);
                            break;
                        case 'ucase':
                        case 'uppercase':
                        case 'strtoupper':
                            /* See PHP's strtoupper - http://www.php.net/manual/en/function.strtoupper.php */
                            $output = $usemb ? mb_strtoupper($output,$encoding) : strtoupper($output);
                            break;
                        case 'ucwords':
                            /* See PHP's ucwords - http://www.php.net/manual/en/function.ucwords.php */
                            $output = $usemb ? mb_convert_case($output,MB_CASE_TITLE,$encoding) : ucwords($output);
                            break;
                        case 'ucfirst':
                            /* See PHP's ucfirst - http://www.php.net/manual/en/function.ucfirst.php */
                            if ($usemb) {
                                $output = mb_strtoupper(mb_substr($output,0,1)) . mb_substr($output, 1);
                            } else {
                                $output = ucfirst($output);
                            }
                            break;
                        case 'htmlent':
                        case 'htmlentities':
                            /* See PHP's htmlentities - http://www.php.net/manual/en/function.htmlentities.php */
                            $output = htmlentities($output,ENT_QUOTES,$encoding,($m_val == 'true'));
                            break;
                        case 'htmlspecialchars':
                        case 'htmlspecial':
                            /* See PHP's htmlspecialchars - http://www.php.net/manual/en/function.htmlspecialchars.php */
                            $output = htmlspecialchars($output,ENT_QUOTES,$encoding);
                            break;
                        case 'esc':
                        case 'escape':
                            $output = preg_replace("/&amp;(#[0-9]+|[a-z]+);/i", "&$1;", htmlspecialchars($output));
                            $output = modX :: replaceReserved($output);
                            break;
                        case 'strip':
                            /* Replaces all linebreaks, tabs and multiple spaces with just one space */
                            $output= preg_replace("/\s+/"," ",$output);
                            break;
                        case 'stripString':
                            /* strips string of this value */
                            $output= str_replace($m_val,'',$output);
                            break;
                        case 'replace':
                            /* replaces one value with another */
                            $opt = explode('==',$m_val);
                            if (count($opt) >= 2) {
                                $output = str_replace($opt[0],$opt[1],$output);
                            }
                            break;
                        case 'notags':
                        case 'striptags':
                        case 'stripTags':
                        case 'strip_tags':
                            /* See PHP's strip_tags - http://www.php.net/manual/en/function.strip_tags.php */
                            if (!empty($m_val)) {
                                $output= strip_tags($output,$m_val);
                            } else {
                                $output= strip_tags($output);
                            }
                            break;
                        case 'stripmodxtags':
                            $output = preg_replace("/\\[\\[([^\\[\\]]++|(?R))*?\\]\\]/s", '', $output);
                            break;
                        case 'length':
                        case 'len':
                        case 'strlen':
                            /* See PHP's strlen - http://www.php.net/manual/en/function.strlen.php */
                            $output = $usemb ? mb_strlen($output,$encoding) : strlen($output);
                            break;
                        case 'reverse':
                        case 'strrev':
                            /* See PHP's strrev - http://www.php.net/manual/en/function.strrev.php */
                            if ($usemb) {
                                $ar = array();
                                preg_match_all('/(\d+)?./us', $output, $ar);
                                $output = join('',array_reverse($ar[0]));
                            } else {
                                $output = strrev($output);
                            }
                            break;
                        case 'wordwrap':
                            /* See PHP's wordwrap - http://www.php.net/manual/en/function.wordwrap.php */
                            $wrapat= intval($m_val);
                            if ($wrapat) {
                                $output= wordwrap($output, $wrapat,"<br />\n ", 0);
                            } else {
                                $output= wordwrap($output, 70,"<br />\n", 0);
                            }
                            break;
                        case 'wordwrapcut':
                            /* See PHP's wordwrap - http://www.php.net/manual/en/function.wordwrap.php */
                            $wrapat= intval($m_val);
                            if ($wrapat) {
                                $output= wordwrap($output, $wrapat,"<br />\n ", 1);
                            } else {
                                $output= wordwrap($output, 70,"<br />\n", 1);
                            }
                            break;
                        case 'limit':
                            /* default: 100 */
                            $limit= intval($m_val) ? intval($m_val) : 100;
                            /* ensure that filter correctly counts special chars */
                            $str = html_entity_decode($output,ENT_COMPAT,$encoding);
                            if ($usemb) {
                                $output= mb_substr($str,0,$limit,$encoding);
                            } else {
                                $output= substr($str,0,$limit);
                            }
                            break;
                        case 'ellipsis':
                            $limit= intval($m_val) ? intval($m_val) : 100;
                            $pad = $this->modx->getOption('ellipsis_filter_pad',null,'&#8230;');

                            /* ensure that filter correctly counts special chars */
                            $output = html_entity_decode($output,ENT_COMPAT,$encoding);
                            $len = $usemb ? mb_strlen($output,$encoding) : strlen($output);
                            if ($limit > $len) $limit = $len;
                            if ($limit < 0) $limit = 0;
                            $breakpoint = $usemb ? mb_strpos($output," ",$limit,$encoding) : strpos($output, " ", $limit);
                            if (false !== $breakpoint) {
                                if ($breakpoint < $len - 1) {
                                    $partial = $usemb ? mb_substr($output, 0, $breakpoint,$encoding) : substr($output, 0, $breakpoint);
                                    $output = $partial . $pad;
                                }
                            }

                            $opened = array();
                            if (preg_match_all("/<(\/?[a-z]+)>?/i", $output, $matches)) {
                                foreach ($matches[1] as $tag) {
                                    if (preg_match("/^[a-z]+$/i", $tag, $regs)) {
                                        $strLower = $usemb ? mb_strtolower($regs[0],$encoding) : strtolower($regs[0]);
                                        if ($strLower != 'br' && $strLower != 'hr') {
                                            $opened[] = $regs[0];
                                        }
                                    } elseif (preg_match("/^\/([a-z]+)$/i", $tag, $regs)) {
                                        $tmpArray = array_keys($opened, (string) $regs[1]);
                                        $tmpVar = array_pop($tmpArray);
                                        if ($tmpVar !== null) {
                                            unset($opened[$tmpVar]);
                                        }
                                    }
                                }
                            }
                            if ($opened) {
                                $tagstoclose = array_reverse($opened);
                                foreach ($tagstoclose as $tag) $output .= "</$tag>";
                            }
                            break;
                        /* #####  Special functions */

                        case 'tag':
                            /* Displays the raw element tag without :tag */
                            $tag = $element->_tag;
                            $tag = htmlentities($tag,ENT_QUOTES,$encoding);
                            $tag = modX :: replaceReserved($tag);
                            $tag = str_replace(":tag","",$tag);
                            $output = $tag;
                            break;

                        case 'add':
                        case 'increment':
                        case 'incr':
                            /* Returns input incremented by option (default: +1) */
                            if (empty($m_val) && $m_val !== 0 && $m_val !== '0') {
                                $m_val = 1;
                            }
                            $output = (float)$output + (float)$m_val;
                            break;

                        case 'subtract':
                        case 'decrement':
                        case 'decr':
                            /* Returns input decremented by option (default: -1) */
                            if (empty($m_val) && $m_val !== 0 && $m_val !== '0') {
                                $m_val = 1;
                            }
                            $output = (float)$output - (float)$m_val;
                            break;

                        case 'multiply':
                        case 'mpy':
                            /* Returns input multiplied by option (default: *2) */
                            if (empty($m_val) && $m_val !== 0 && $m_val !== '0') {
                                $m_val = 1;
                            }
                            $output = (float)$output * (float)$m_val;
                            break;

                        case 'divide':
                        case 'div':
                            /* Returns input divided by option (default: /2) */
                            if (empty($m_val)) {
                                $m_val = 2;
                            }
                            if (!empty($output)) {
                                $output = (float)$output / (float)$m_val;
                            } else {
                                $output = 0;
                            }
                            break;

                        case 'modulus':
                        case 'mod':
                            /* Returns the option modulus on input (default: %2, returns 0 or 1) */
                            if (empty($m_val))
                                $m_val = 2;
                            $output = (float)$output % (float)$m_val;
                            break;

                        case 'default':
                        case 'ifempty':
                        case 'isempty':
                        case 'empty':
                            /* Returns the input value if empty */
                            if (empty($output))
                                $output= $m_val;
                            break;

                        case 'ifnotempty':
                        case 'isnotempty':
                        case 'notempty':
                        case '!empty':
                            /* returns input value if not empty */
                            if (!empty($output))
                                $output= $m_val;
                            break;

                        case 'nl2br':
                            /* See PHP's nl2br - http://www.php.net/manual/en/function.nl2br.php */
                            $output= nl2br($output);
                            break;

                        case 'strftime':
                        case 'date':
                            /* See PHP's strftime - http://www.php.net/manual/en/function.strftime.php */
                            if (empty($m_val)) {
                                $m_val = "%A, %d %B %Y %H:%M:%S"; /* @todo this should be modx default date/time format? Lexicon? */
                            }
                            if (($value = filter_var($output, FILTER_VALIDATE_INT)) === false) {
                                $value = strtotime($output);
                            }
                            $output = ($value !== false) ? strftime($m_val,$value) : '';
                            break;

                        case 'strtotime':
                            /* See PHP's strtotime() function - http://www.php.net/strtotime */
                            if (!empty($output)) {
                                $output = strtotime($output);
                            } else {
                                $output = '';
                            }
                            break;
                        case 'fuzzydate':
                            /* displays a "fuzzy" date reference */
                            if (empty($this->modx->lexicon)) $this->modx->getService('lexicon','modLexicon');
                            $this->modx->lexicon->load('filters');
                            if (empty($m_val)) $m_val= '%b %e';
                            if (!empty($output)) {
                                $time = strtotime($output);
                                if ($time >= strtotime('today')) {
                                    $output = $this->modx->lexicon('today_at',array('time' => strftime('%I:%M %p',$time)));
                                } elseif ($time >= strtotime('yesterday')) {
                                    $output = $this->modx->lexicon('yesterday_at',array('time' => strftime('%I:%M %p',$time)));
                                } else {
                                    $output = strftime($m_val, $time);
                                }
                            } else {
                                $output = '&mdash;';
                            }
                            break;
                        case 'ago':
                            /* calculates relative time ago from a timestamp */
                            if (empty($output)) break;
                            if (empty($this->modx->lexicon)) $this->modx->getService('lexicon','modLexicon');
                            $this->modx->lexicon->load('filters');

                            $agoTS = array();
                            $uts['start'] = strtotime($output);
                            $uts['end'] = time();
                            if( $uts['start']!==-1 && $uts['end']!==-1 ) {
                              if( $uts['end'] >= $uts['start'] ) {
                                $diff = $uts['end'] - $uts['start'];

                                $years = intval((floor($diff/31536000)));
                                if ($years) $diff = $diff % 31536000;

                                $months = intval((floor($diff/2628000)));
                                if ($months) $diff = $diff % 2628000;

                                $weeks = intval((floor($diff/604800)));
                                if ($weeks) $diff = $diff % 604800;

                                $days = intval((floor($diff/86400)));
                                if ($days) $diff = $diff % 86400;

                                $hours = intval((floor($diff/3600)));
                                if ($hours) $diff = $diff % 3600;

                                $minutes = intval((floor($diff/60)));
                                if ($minutes) $diff = $diff % 60;

                                $diff = intval($diff);
                                $agoTS = array(
                                  'years' => $years,
                                  'months' => $months,
                                  'weeks' => $weeks,
                                  'days' => $days,
                                  'hours' => $hours,
                                  'minutes' => $minutes,
                                  'seconds' => $diff,
                                );
                              }
                            }

                            $ago = array();
                            if (!empty($agoTS['years'])) {
                              $ago[] = $this->modx->lexicon(($agoTS['years'] > 1 ? 'ago_years' : 'ago_year'),array('time' => $agoTS['years']));
                            }
                            if (!empty($agoTS['months'])) {
                              $ago[] = $this->modx->lexicon(($agoTS['months'] > 1 ? 'ago_months' : 'ago_month'),array('time' => $agoTS['months']));
                            }
                            if (!empty($agoTS['weeks']) && empty($agoTS['years'])) {
                              $ago[] = $this->modx->lexicon(($agoTS['weeks'] > 1 ? 'ago_weeks' : 'ago_week'),array('time' => $agoTS['weeks']));
                            }
                            if (!empty($agoTS['days']) && empty($agoTS['months']) && empty($agoTS['years'])) {
                              $ago[] = $this->modx->lexicon(($agoTS['days'] > 1 ? 'ago_days' : 'ago_day'),array('time' => $agoTS['days']));
                            }
                            if (!empty($agoTS['hours']) && empty($agoTS['weeks']) && empty($agoTS['months']) && empty($agoTS['years'])) {
                              $ago[] = $this->modx->lexicon(($agoTS['hours'] > 1 ? 'ago_hours' : 'ago_hour'),array('time' => $agoTS['hours']));
                            }
                            if (!empty($agoTS['minutes']) && empty($agoTS['days']) && empty($agoTS['weeks']) && empty($agoTS['months']) && empty($agoTS['years'])) {
                                $ago[] = $this->modx->lexicon($agoTS['minutes'] == 1 ? 'ago_minute' : 'ago_minutes' ,array('time' => $agoTS['minutes']));
                            }
                            if (empty($ago)) { /* handle <1 min */
                              $ago[] = $this->modx->lexicon('ago_seconds',array('time' => !empty($agoTS['seconds']) ? $agoTS['seconds'] : 0));
                            }
                            $output = implode(', ',$ago);
                            $output = $this->modx->lexicon('ago',array('time' => $output));
                            break;
                        case 'md5':
                            /* See PHP's md5 - http://www.php.net/manual/en/function.md5.php */
                            $output= md5($output);
                            break;
                        case 'cdata':
                            if ($usemb) {
                                $len = mb_strlen($output,$encoding);
                                if (mb_strpos($output,'[',0,$encoding) === 0) { $output = ' '.$output; }
                                if (mb_strpos($output,']',0,$encoding) === $len) { $output = $output.' '; }
                            } else {
                                $len = strlen($output);
                                if (strpos($output,'[') === 0) { $output = ' '.$output; }
                                if (strpos($output,']') === $len) { $output = $output.' '; }
                            }
                            $output= "<![CDATA[{$output}]]>";
                            break;

                        case 'userinfo':
                            /* Returns the requested modUser or modUserProfile data (input: user id) */
                            if (!empty($output)) {
                                $key = (!empty($m_val)) ? $m_val : 'username';
                                $userInfo= null;
                                /** @var modUser $user */
                                if ($user= $this->modx->getObjectGraph('modUser', '{"Profile":{}}', $output)) {
                                    $userData = array_merge($user->toArray(), $user->Profile->toArray());
                                    unset($userData['cachepwd'], $userData['salt'], $userData['sessionid'], $userData['password'], $userData['session_stale']);
                                    if (strpos($key, 'extended.') === 0 && isset($userData['extended'][substr($key, 9)])) {
                                        $userInfo = $userData['extended'][substr($key, 9)];
                                    } elseif (strpos($key, 'remote_data.') === 0 && isset($userData['remote_data'][substr($key, 12)])) {
                                        $userInfo = $userData['remote_data'][substr($key, 12)];
                                    } elseif (isset($userData[$key])) {
                                        $userInfo = $userData[$key];
                                    }
                                }
                                $output = $userInfo;
                            } else {
                                $output = null;
                            }
                            break;

                        case 'isloggedin':
                            /* returns true if user is logged in to the specified context or by default the current context */
                            $ctxkey = (!empty($m_val)) ? $m_val : $this->modx->context->get('key');
                            $output= $this->modx->user->isAuthenticated($ctxkey);
                            $output= $output ? true : false;
                            break;

                        case 'isnotloggedin':
                            /* returns true if user is not logged in to the specified context or by default the current context */
                            $ctxkey = (!empty($m_val)) ? $m_val : $this->modx->context->get('key');
                            $output= $this->modx->user->isAuthenticated($ctxkey);
                            $output= $output ? false : true;
                            break;

                        case 'urlencode':
                            $output = urlencode($output);
                            break;
                        case 'urldecode':
                            $output = urldecode($output);
                            break;

                        case 'toPlaceholder':
                            $this->modx->toPlaceholder($m_val,$output);
                            $output = '';
                            break;
                        case 'cssToHead':
                            $this->modx->regClientCSS($output);
                            $output = '';
                            break;
                        case 'htmlToHead':
                            $this->modx->regClientStartupHTMLBlock($output);
                            $output = '';
                            break;
                        case 'htmlToBottom':
                            $this->modx->regClientHTMLBlock($output);
                            $output = '';
                            break;
                        case 'jsToHead':
                            if (empty($m_val)) $m_val = false;
                            $this->modx->regClientStartupScript($output,$m_val);
                            $output = '';
                            break;
                        case 'jsToBottom':
                            if (empty($m_val)) $m_val = false;
                            $this->modx->regClientScript($output,$m_val);
                            $output = '';
                            break;
                        case 'in':
                        case 'IN':
                        case 'inarray':
                        case 'inArray':
                            if (empty($m_val)) $m_val = false;
                            $haystack = explode(',', $m_val);
                            $condition[]= intval(in_array($output, $haystack));
                            break;
                        case 'tvLabel':
                            $name = $element->get('name');
                            if (!empty($m_val) && strpos($name, $m_val) === 0) {
                                $name = substr($name, strlen($m_val));
                            }
                            $tv = $this->modx->getObject('modTemplateVar', array('name' => $name));
                            if (!$tv) {
                                break;
                            }
                            $o_prop = $tv->get('output_properties');
                            $options = explode('||', $tv->get('elements'));
                            $lookup = array();
                            foreach ($options as $o) {
                                list($name, $value) = explode('==', $o);
                                $lookup[$value] = $name;
                            }
                            if (isset($o_prop['delimiter'])) {
                                $delimiter = $o_prop['delimiter'];
                                $values = explode($delimiter, $output);
                            } else {
                                $delimiter = '';
                                $values = array($output);
                            }
                            $return_values = array();
                            foreach ($values as $v) {
                                $return_values[] = $lookup[$v];
                            }
                            $output = implode($delimiter, $return_values);
                            break;
                        case 'filterPathSegment':
                            $output = $this->modx->filterPathSegment($output);
                            break;

                        /* Default, custom modifier (run snippet with modifier name) */
                        default:
                            /*@todo Possibility to only look for snippet names prefixed with 'filter:' */
                            /*@todo Maybe pass whole element by reference instead of token/tag/name? */
                            $params = array (
                                'input' => $output,
                                'options' => $m_val,
                                'token' => $element->_token, /* type of parent element */
                                'name' => $element->get('name'), /* name of the parent element */
                                'tag' => $element->getTag() /* complete parent tag */
                            );
                            $this->log('This modifier is custom running as snippet.');
                            $tmp = $this->modx->runSnippet($m_cmd, $params);
                            if ($tmp!='') $output = $tmp;
                            break;
                    }
                } catch (Exception $e) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR,$e->getMessage());
                }
            }
            // convert $output to string if there were any processing
            $output = (string)$output;
        }
    }

    /**
     * Parse a set of conditions
     *
     * @param array $conditions Conditionals to parse
     * @param null|mixed $value The value to set if our parsing matches
     * @param null|mixed $default The default value to set if our conditions were false
     * @param bool $negate Negate the comparison
     * @return bool
     */
    private static function parseConditions($conditions, $value = null, $default = null, $negate = false)
    {
        $conditional = join(' ', $conditions);
        try {
            $m_con = ($conditional !== '') ? @eval("return (" . $conditional . ");") : false;
            $m_con = intval($m_con);

            // If negate is true, we want the value of $m_con to be false
            if (!$negate) {
                if ($m_con) {
                    return $value;
                }

                return $default;
            }

            if (!$m_con) {
                return $value;
            }

        } catch (Exception $e) {
        }

        return $default;
    }

    /**
     * Validate conditionals before running eval
     *
     * @param array $conditions Conditionals to validate
     * @return bool
     */
    private function validateConditionals($conditions)
    {
        // Check if we have any conditions at all
        if (count($conditions) == 0) {
            return false;
        }

        // Check that first and last element is values and not conditional operators
        $last_condition = $conditions[count($conditions) - 1];
        if (!self::isValue($conditions[0]) or !self::isValue($last_condition)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Encountered incorrect use of output conditions');
            return false;
        }

        // We now loop the conditions to make sure they have the following patter [value, condition, value, ...]
        for ($i = 0; $i < count($conditions); $i++) {
            $current_condition = $conditions[$i];
            if ($i % 2 === 0) {
                // Even elements in the array should be values
                if (!self::isValue($current_condition)) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, 'Encountered incorrect use of output conditions');
                    return false;
                }
            }
            else {
                // Odd elements in the array should be conditions
                if (!self::isCondition($current_condition)) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, 'Encountered incorrect use of output conditions');
                    return false;
                }
            }
        }

        // If we got here, it means we found no incorrect or unexpected values in the array, the conditions should be
        // legal.
        return true;
    }

    /**
     * Checks if input is integer og boolean
     *
     * @param mixed $input Value from conditions
     * @return bool
     */
    private static function isValue($input)
    {
        return is_bool($input) or is_int($input);
    }

    /**
     * Checks if input is a condition
     *
     * @param mixed $input Value from conditions
     * @return bool
     */
    private static function isCondition($input)
    {
        return $input === '&&' or $input === '||';
    }

    /**
     * Send a log message to the message logger
     * @param string $msg
     * @return void
     */
    public function log($msg) {
         if ($this->modx->getDebug() === true) {
             $this->modx->log(modX::LOG_LEVEL_DEBUG, $msg);
         }
    }
}
