<?php
/**
 * Loads the lexicon into a JS-compatible function _()
 *
 * @var modX $modx
 * @package modx
 * @subpackage lexicon
 */
define('MODX_CONNECTOR_INCLUDED', 1);

ob_start();
require_once dirname(__FILE__).'/index.php';
ob_clean();

if (!empty($_REQUEST['topic'])) {
    $topics = explode(',',$_REQUEST['topic']);
    foreach($topics as $topic) $modx->lexicon->load($topic);
}

$entries = $modx->lexicon->fetch();
echo '
MODx.lang = {';
$s = '';
while (list($k,$v) = each ($entries)) {
    $s .= "'$k': ".'"'.esc($v).'",';
}
$s = trim($s,',');
echo $s.'
};
var _ = function(s,v) {
    if (v != null && typeof(v) == "object") {
        var t = ""+MODx.lang[s];
        for (var k in v) {
            t = t.replace("[[+"+k+"]]",v[k]);
        }
        return t;
    } else return MODx.lang[s];
}';

function esc($s) {
    return strtr($s,array('\\'=>'\\\\',"'"=>"\\'",'"'=>'\\"',"\r"=>'\\r',"\n"=>'\\n','</'=>'<\/'));
}

/* gather output from buffer */
$output = ob_get_contents();
ob_end_clean();


/* if turned on, will cache lexicon entries in JS based upon http headers */
if ($modx->getOption('cache_lang_js',null,false)) {
    $hash = md5($output);
    $headers = $modx->request->getHeaders();

    /* if Browser sent ID, check if they match */
    if (isset($headers['If-None-Match']) && @preg_match($hash, $headers['If-None-Match'])) {
        header('HTTP/1.1 304 Not Modified');
    } else {
        header("ETag: \"{$hash}\"");
        header('Accept-Ranges: bytes');
        //header('Content-Length: '.strlen($output));
        header('Content-Type: application/x-javascript');

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
