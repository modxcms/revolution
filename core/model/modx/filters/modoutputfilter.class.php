<?php
/*
 * MODx Revolution
 *
 * Copyright 2006, 2007, 2008 by the MODx Team.
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 */

/**
 * Provides the default output filter implementation for modElement processing.
 * @package modx
 * @subpackage filters
 */

/**
 * Base output filter implementation for modElement processing, based on phX.
 *
 * @todo implement error handling (try/catch) to prevent modifiers from breaking the output
 *
 * @package modx
 * @subpackage filters
 */
class modOutputFilter {
    var $modx= null;

    function modOutputFilter(& $modx) {
        $this->modx= & $modx;
    }

    function filter(& $element) {

        $output= & $element->_output;
        if (isset ($element->_properties['filter_commands'])) {
            $modifier_cmd = & $element->_properties['filter_commands'];
            $modifier_value = & $element->_properties['filter_modifiers'];
            $count = count($modifier_cmd);
            $condition = array();

            for ($i= 0; $i < $count; $i++) {

                $m_cmd = $modifier_cmd[$i];
                $m_val = $modifier_value[$i];

                $this->log('Processing Modifier: ' . $m_cmd . ' (parameters: ' . $m_val . ')');

                $output = trim($output);

                switch ($m_cmd) {
                    /* conditional operators */
                    /* @todo these conditionals should be removed because there are cleaner ways to do this now */
                    case "input" :
                    case "if" :
                        $output= $m_val;
                        break;
                    case "equals" :
                    case "is" :
                    case "eq" :
                        $condition[]= intval(($output == $m_val));
                        break;
                    case "notequals" :
                    case "isnot" :
                    case "isnt" :
                    case "ne" :
                        $condition[]= intval(($output != $m_val));
                        break;
                    case "isgreaterthan" :
                    case "isgt" :
                    case "eg" :
                        $condition[]= intval(($output >= $m_val));
                        break;
                    case "islowerthan" :
                    case "islt" :
                    case "el" :
                        $condition[]= intval(($output <= $m_val));
                        break;
                    case "greaterthan" :
                    case "gt" :
                        $condition[]= intval(($output > $m_val));
                        break;
                    case "lowerthan" :
                    case "lt" :
                        $condition[]= intval(($output < $m_val));
                        break;
                    case "isinrole" :
                    case "memberof" :
                    case "mo" : /* Is Member Of  (same as inrole but this one can be stringed as a conditional) */
                        /* @todo: fix or possibly remove */
                        if ($output == "&_PHX_INTERNAL_&")
                            $output= $this->user["id"];
                        $grps= (strlen($m_val) > 0) ? explode(",", $m_val) : array ();
                        $condition[]= intval($this->isMemberOfWebGroupByUserId($output, $grps));
                        break;
                    case "or" :
                        $condition[]= "||";
                        break;
                    case "and" :
                        $condition[]= "&&";
                        break;
                    case "show" :
                        $m_con = intval(eval("return (" . join(' ', $condition) . ");"));
                        if (!$m_con) {
                            $output= NULL;
                        }
                        break;
                    case "then" :
                        $m_con = intval(eval("return (" . join(' ', $condition) . ");"));
                        if ($m_con) {
                            $output= $m_val;
                        } else {
                            $output= NULL;
                        }
                        break;
                    case "else" :
                        $m_con = intval(eval("return (" . join(' ', $condition) . ");"));
                        if (!$m_con) {
                            $output= $m_val;
                        }
                        break;
                    case "select" :
                        $raw= explode("&", $m_val);
                        $map= array ();
                        for ($m= 0; $m < (count($raw)); $m++) {
                            $mi= explode("=", $raw[$m]);
                            $map[$mi[0]]= $mi[1];
                        }
                        $output= $map[$output];
                        break;
                        /* #####  End of Conditional Modifiers */

                    /* #####  String Modifiers */
                    case "cat": /* appends the options value (if not empty) to the input value */
                        if (!empty($m_val))
                            $output = $output . $m_val;
                    case "lcase" : /* See PHP's strtolower - http://www.php.net/manual/en/function.strtolower.php */
                        $output= strtolower($output);
                        break;
                    case "ucase" : /* See PHP's strtoupper - http://www.php.net/manual/en/function.strtoupper.php */
                        $output= strtoupper($output);
                        break;
                    case "ucfirst" : /* See PHP's ucfirst - http://www.php.net/manual/en/function.ucfirst.php */
                        if (empty($m_val))
                            $m_val = false;
                        $output= ucfirst($output, $m_val);
                        break;
                    case "htmlent" : /* See PHP's htmlentities - http://www.php.net/manual/en/function.htmlentities.php */
                        $output= htmlentities($output, ENT_QUOTES, $this->modx->getOption('modx_charset'));
                        break;
                    case "esc" :
                    case "escape" :
                        $output= preg_replace("/&amp;(#[0-9]+|[a-z]+);/i", "&$1;", htmlspecialchars($output));
                        $output= str_replace(array ("[", "]", "`"), array ("&#91;", "&#93;", "&#96;"), $output);
                        break;
                    case "strip" : /* Replaces all linebreaks, tabs and multiple spaces with just one space */
                        $output= preg_replace("~([\n|\r|\t|\s]+)~", " ", $output);
                        break;
                    case "notags" : /* See PHP's strip_tags - http://www.php.net/manual/en/function.strip_tags.php */
                        $output= strip_tags($output);
                        break;
                    case "length" : /* See PHP's strlen - http://www.php.net/manual/en/function.strlen.php */
                    case "len" :
                        $output= strlen($output);
                        break;
                    case "reverse" : /* See PHP's strrev - http://www.php.net/manual/en/function.strrev.php */
                        $output= strrev($output);
                        break;
                    case "wordwrap" : /* See PHP's wordwrap - http://www.php.net/manual/en/function.wordwrap.php */
                        $wrapat= intval($m_val);
                        if ($wrapat) {
                            $output= wordwrap($output, $wrapat, " ", 1);
                        } else {
                            $output= wordwrap($output, 70, " ", 1);
                        }
                        break;
                    case "limit" : /* default: 100 */
                        $limit= intval($m_val) ? intval($m_val) : 100;
                        $output= substr($output, 0, $limit);
                        break;

                    /* #####  Special functions */

                    case "tag": /* Displays the raw element tag without :tag */
                        $tag = $element->_tag;
                        $tag = htmlentities($tag, ENT_QUOTES, $this->modx->getOption('modx_charset'));
                        $tag = str_replace(array ("[", "]", "`"), array ("&#91;", "&#93;", "&#96;"), $tag);
                        $tag = str_replace(":tag","",$tag);
                        $output = $tag;
                        break;

                    case "math": /* Returns the result of an advanced calculation (expensive) */
                    	$filter= preg_replace("~([a-zA-Z\n\r\t\s])~", "", $m_val);
                        $filter= str_replace('?', $output, $filter);
                        $output= eval("return " . $filter . ";");
                        break;

                    case "add": /* Returns input incremented by option (default: +1) */
                    case "increment":
                    case "incr":
                    	if (empty($m_val))
                            $m_val = 1;
                    	$output = intval($output) + intval($m_val);
                    	break;

                    case "substract":
                    case "decrement": /* Returns input decremented by option (default: -1) */
                    case "decr":
                        if (empty($m_val))
                            $m_val = 1;
                        $output = intval($output) - intval($m_val);
                        break;

                    case "multiply": /* Returns input multiplied by option (default: *2) */
                    case "mpy":
                        if (empty($m_val))
                            $m_val = 1;
                        $output = intval($output) * intval($m_val);
                        break;

                    case "divide": /* Returns input divided by option (default: /2) */
                    case "div":
                        if (empty($m_val))
                            $m_val = 2;
                        $output = intval($output) / intval($m_val);
                        break;

                    case "modulus": /* Returns the option modulus on input (default: %2, returns 0 or 1) */
                    case "mod":
                        if (empty($m_val))
                            $m_val = 2;
                        $output = intval($output) % intval($m_val);
                        break;

                    case "default" : /* Returns the input value if empty */
                    case "ifempty" :
                        if (empty($output))
                            $output= $m_val;
                        break;

                    case "nl2br" : /* See PHP's nl2br - http://www.php.net/manual/en/function.nl2br.php */
                        $output= nl2br($output);
                        break;

                    case "date": /* See PHP's strftime - http://www.php.net/manual/en/function.strftime.php */
                        if (empty($m_val))
                            $m_val = "%A, %d %B %Y %H:%M:%S"; /* @todo this should be modx default date/time format? Lexicon? */
                        $value = 0 + $output;
                        if ($value != 0 && $value != -1) {
                            $output= strftime($m_val, 0 + $output);
                        } else {
                            $output= '';
                        }
                        break;

                    case "strtotime": /* See PHP's strtotime() function - http://www.php.net/strtotime */
                        if (!empty($output)) {
                            $output = strtotime($output);
                        } else {
                            $output = '';
                        }
                        break;

                    case "md5" : /* See PHP's md5 - http://www.php.net/manual/en/function.md5.php */
                        $output= md5($output);
                        break;

                    case "userinfo" : /* Returns the requested user data (input: userid) */
                        $key = (!empty($m_val)) ? $m_val : 'username';
                    	$user = $this->modx->getUserInfo($output);
                        $output = $user ? $user[$key] : null;
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
            }
        }
    }

    function log( $msg ) {
         if ($this->modx->getDebug() === true)
             $this->modx->log(MODX_LOG_LEVEL_DEBUG, $msg);
    }
}