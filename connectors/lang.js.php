<?php
/**
 * Loads the lexicon into a JS-compatible function _()
 *
 * @var modX $modx
 * @package modx
 * @subpackage lexicon
 *
 */

function esc($s) {
    return strtr($s, array('\\' => '\\\\', "'" => "\\'", '"' => '\\"', "\r" => '\\r', "\n" => '\\n', '</' => '<\/'));
}

$time = microtime(true);

define('MODX_CONNECTOR_INCLUDED', 1);

require_once dirname(__FILE__).'/index.php';

/* we compute the whole dictionary for the used language at once and use the cache */
/* can be kept infinite until cache is refreshed */
$cacheOptions = array(
    xPDO::OPT_CACHE_KEY => 'lexicon-js',
    xPDO::OPT_CACHE_EXPIRES => 0
);
$lang = $modx->getOption('manager_language','en');
$cachedOutput = $modx->cacheManager->get('lang.js_'.$lang, $cacheOptions);

/* create output if nothing is cached */
if (is_null($cachedOutput)) {

    /* get the prefixed topic names for each namespace */
    $tocs = array();
    foreach ($modx->getCollection('modNamespace') as $n) {
        $tocs = array_merge($tocs,
            preg_filter(
                '/^/', $n->name.':',
                $modx->lexicon->getTopicList( $lang, $n->name )
            )
        );
    }

    /* load all topics from all namespaces and fetch the entries */
    call_user_func_array( array(&$modx->lexicon,'load'), $tocs);
    $entries = $modx->lexicon->fetch();

    $buf = 'MODx.lang = {';
    $s = '';
    while (list($k, $v) = each($entries)) {
        $s .= "'$k': " . '"' . esc($v) . '",';
    }
    $s = trim($s, ',');
    $buf .= $s;

    // use compressed js here - saves some bytes :-)
    //$buf .= '};var _=function(r,n){if(null!=n&&"object"==typeof n){var a=""+MODx.lang[r];for(var e in n)a=a.replace("[[+"+e+"]]",n[e]);return a}return MODx.lang[r]}';
    $buf .= '};
    var _ = function(s,v) {
        if (v != null && typeof(v) == "object") {
            var t = ""+MODx.lang[s];
            for (var k in v) {
                t = t.replace("[[+"+k+"]]",v[k]);
            }
            return t;
        } else return MODx.lang[s];
    }';

    $output = $buf;
    $modx->cacheManager->set('lang.js_'.$lang, $output, null, $cacheOptions);

} else {
    $output = $cachedOutput;
}
//$modx->log( modX::LOG_LEVEL_DEBUG, "[lang.js] retrieving ".$lang." dictionary: " . round((microtime(true) - $time),4));

ob_start();
ob_clean();

/* if turned on, will cache lexicon entries in JS based upon http headers */
if ($modx->getOption('cache_lang_js',null,false)) {
    /* adler32 hash only 8 Bytes and faster than md5 or crc32 */
    $hash = hash('adler32',$output);
    $headers = $modx->request->getHeaders();

    /* if Browser sent ID, check if they match */
    if (isset($headers['If-None-Match']) && strpos($headers['If-None-Match'], $hash)!==false ) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
        header("ETag: \"{$hash}\"");
        header('Cache-control: max-age=60 public');
        header_remove('Pragma');
        header_remove('Expires');
        header_remove('X-Powered-By');
        header_remove('Set-Cookie');
    } else {
        header("ETag: \"{$hash}\"");
        header('Accept-Ranges: bytes');
        header('Content-Type: application/x-javascript');
        header('Cache-control: max-age=60 public');
        header_remove('Pragma');
        header_remove('Expires');
        header_remove('X-Powered-By');
        header_remove('Server');
        header_remove('Set-Cookie');
        echo $output;
    }

} else {
    /* just output JS with no server caching */
    //header('Content-Length: '.strlen($output));
    header('Content-Type: application/x-javascript');
    echo $output;
}
@session_write_close();

exit();
