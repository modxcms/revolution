<?php
$invdirs = array('.','..','.svn','country');

$d = MODX_CORE_PATH.'lexicon/';

$i = 0;
/* loop through cultures */
$dir = dir($d);
while (false !== ($culture = $dir->read())) {
    if (in_array($culture,$invdirs)) continue;
    if (!is_dir($d.$culture)) continue;

    $languages[$culture]= $xpdo->newObject('modLexiconLanguage');
    $languages[$culture]->fromArray(array(
        'name' => $culture,
    ),'',true,true);

    /* loop through topics */
    $fdir = $d.$culture.'/';
    $fd = dir($fdir);
    $topcount = 1;
    while (false !== ($topicFile = $fd->read())) {
        if (in_array($topicFile,$invdirs)) continue;
        if (is_dir($fdir.$topicFile)) continue;

        $topicname = str_replace('.inc.php','',$topicFile);

        $topic = $xpdo->getObject('modLexiconTopic');
        if ($topic == null) {
            $topic= $xpdo->newObject('modLexiconTopic');
            $topic->fromArray(array (
              'id' => $topcount,
              'name' => $topicname,
              'namespace' => 'core',
            ), '', true, true);
        }

        $f = $fdir.$topicFile;
        if (file_exists($f)) {
            $topics[$topic->get('id')] = $topic;
            $topcount++;
        }
    }
}
$dir->close();
