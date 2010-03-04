<?php
/**
 * Read from the registry to console
 *
 * @param string $register The register to read from
 * @param string $topic The topic in the register to read from
 * @param string $format (optional) The format to output as. Defaults to json.
 * @param string $register_class (optional) If set, will load a custom registry
 * class.
 * @param integer $poll_limit (optional) The number of polls to limit to.
 * Defaults to 1.
 * @param integer $poll_interval (optional) The interval of polls to grab from.
 * Defaults to 1.
 * @param integer $time_limit (optional) The time limit to sort by. Defaults to
 * 10.
 * @param integer $message_limit (optional) The max amount of messages to grab.
 * Defaults to 200.
 * @param boolean $remove_read (optional) If false, will not remove the message
 * when read. Defaults to true.
 * @param boolean $show_filename (optional) If true, will show the filename in
 * the message. Defaults to false.
 *
 * @package modx
 * @subpackage processors.system.registry.register
 */


if (!isset($scriptProperties['register']) || empty($scriptProperties['register']) || !preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $scriptProperties['register'])) return $modx->error->failure($modx->lexicon('error'));
if (!isset($scriptProperties['topic']) || empty($scriptProperties['topic'])) return $modx->error->failure($modx->lexicon('error'));

$register = trim($scriptProperties['register']);
$register_class = isset($scriptProperties['register_class']) ? trim($scriptProperties['register_class']) : 'registry.modFileRegister';

$topic = trim($scriptProperties['topic']);
$format = isset($scriptProperties['format']) ? trim($scriptProperties['format']) : 'json';

$options = array(
    'poll_limit' => $modx->getOption('poll_limit',$scriptProperties,1),
    'poll_interval' => $modx->getOption('poll_interval',$scriptProperties,1),
    'time_limit' => $modx->getOption('time_limit',$scriptProperties,10),
    'msg_limit' => $modx->getOption('message_limit',$scriptProperties,200),
    'show_filename' => $modx->getOption('show_filename',$scriptProperties,true),
    'remove_read' => true,
);

$modx->getService('registry', 'registry.modRegistry');
$modx->registry->addRegister($register, $register_class, array('directory' => $register));
if (!$modx->registry->$register->connect()) return $modx->error->failure($modx->lexicon('error'));

$modx->registry->$register->subscribe($topic);

$msgs = $modx->registry->$register->read($options);

$message = '';

if (!empty($msgs)) {
    $message = array(
        'type' => 'event',
        'name' => 'message',
        'data' => '',
    );
    foreach ($msgs as $msgKey => $msg) {
        if ($msg['msg'] == 'COMPLETED') {
            $message['data'] = 'COMPLETED';
            continue;
        }
        if (!empty ($msg['def'])) $msg['def']= $msg['def'].' ';
        if (!empty ($msg['file'])) $msg['file']= '@ '.$msg['file'].' ';
        if (!empty ($msg['line'])) $msg['line']= 'line '.$msg['line'].' ';
        $message['data'] .= '<span class="' . strtolower($msg['level']) . '">';
        if ($options['show_filename']) {
            $message['data'] .= '<small>(' . trim($msg['def'] . $msg['file'] . $msg['line']) . ')</small>';
        }
        $message['data'] .= $msg['msg']."</span><br />\n";
    }
    if (!empty($message)) {
        return $modx->toJSON($message);
    }
}
return $modx->toJSON(array());
