<?php
/**
 * @package modx
 */
/* Include the base modParser class */
include_once (strtr(realpath(dirname(__FILE__)) . '/modparser.class.php', '\\', '/'));
/**
 * An extension of the MODX parser to support legacy MODX tags.
 *
 * Use of this class is only necessary if you have a site that contains legacy
 * MODX tags.  It provides facilities for translating the legacy tags, as well
 * as for supporting legacy behavior of the onParseDocument event used in many
 * legacy MODX plugins.
 *
 * @package modx
 */
class modParser095 extends modParser {
    /**
     * An array of translation strings from migrating from Evolution
     * @var array $tagTranslation
     */
    public $tagTranslation= array (
        '[[++' => array ('[(', ')]', '++'),
        '[[$' => array ('{{', '}}', '$'),
        '[[*' => array ('[*', '*]', '*'),
        '[[~' => array ('[~', '~]', '~'),
        '[[+' => array ('[+', '+]', '+'),
        '[[!' => array ('[!', '!]', '!'),
    );

    /**
     * Collects MODX legacy tags and translates them to the new tag format.
     *
     * @param string &$content The content in which legacy tags are to be
     * replaced.
     * @param array $tokens An optional array of tag tokens on which to exclude
     * translation of the tags.
     * @param boolean $echo
     * @return void The content is operated on by reference.
     */
    public function translate(& $content, $tokens= array (), $echo= false) {
        $newSuffix= ']]';
        $matchCount= 0;
        foreach ($this->tagTranslation as $newPrefix => $legacyTags) {
            $tagMap= array ();
            $oldPrefix= $legacyTags[0];
            $oldSuffix= $legacyTags[1];
            $token= $legacyTags[2];
            if (!empty ($tokens) && is_array($tokens)) {
                if (in_array($token, $tokens)) {
                    continue;
                }
            }
            $tags= array ();
            if ($matches= $this->collectElementTags($content, $tags, $oldPrefix, $oldSuffix)) {
//                $this->modx->cacheManager->writeFile(MODX_BASE_PATH . 'parser095.translate.log', 'Translating ' . $oldPrefix . $oldSuffix . ' to ' . $newPrefix . ']] ('.$matches.' matches): ' . print_r($tags, 1) . "\n", 'a');
                foreach ($tags as $tag) {
                    $tagMap[$tag[0]]= $newPrefix . $tag[1] . $newSuffix;
                }
            }
            if (!empty ($tagMap)) {
                $matchCount+= count($tagMap);
                $content= str_replace(array_keys($tagMap), array_values($tagMap), $content);
                if ($echo) {
                    echo "[TRANSLATED TAGS] " . print_r($tagMap, 1) . "\n";
                }
            }
        }
        return $matchCount;
    }

    /**
     * Adds the legacy tag translation and legacy OnParseDocument event support.
     * @param string $parentTag
     * @param $content
     * @param bool $processUncacheable
     * @param bool $removeUnprocessed
     * @param string $prefix
     * @param string $suffix
     * @param array $tokens
     * @param bool $echo
     * @return string
     *
     */
    public function processElementTags($parentTag, & $content, $processUncacheable= false, $removeUnprocessed= false, $prefix= "[[", $suffix= "]]", $tokens= array (), $echo= false) {
        // invoke OnParseDocument event
        $this->modx->documentOutput = $content;      // store source code so plugins can
        $this->modx->invokeEvent('OnParseDocument');    // work on it via $modx->documentOutput
        $content = $this->modx->documentOutput;
        $ignoretokens= array ();
//        if (!$processUncacheable) {
//            $ignoretokens[]= '+';
//            $ignoretokens[]= '++';
//        }
//        if (!$processUncacheable || ($processUncacheable && !$removeUnprocessed)) {
//            $ignoretokens[]= '!';
//        }
        while ($this->translate($content, $ignoretokens, $echo)) {}
        return parent :: processElementTags($parentTag, $content, $processUncacheable, $removeUnprocessed, $prefix, $suffix, $tokens);
    }
}
