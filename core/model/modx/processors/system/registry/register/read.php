<?php
/**
 * Read from the registry
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

$options = array();
$options['poll_limit'] = (isset($scriptProperties['poll_limit']) && intval($scriptProperties['poll_limit'])) ? intval($scriptProperties['poll_limit']) : 1;
$options['poll_interval'] = (isset($scriptProperties['poll_interval']) && intval($scriptProperties['poll_interval'])) ? intval($scriptProperties['poll_interval']) : 1;
$options['time_limit'] = (isset($scriptProperties['time_limit']) && intval($scriptProperties['time_limit'])) ? intval($scriptProperties['time_limit']) : 10;
$options['msg_limit'] = (isset($scriptProperties['message_limit']) && intval($scriptProperties['message_limit'])) ? intval($scriptProperties['message_limit']) : 200;
$options['remove_read'] = isset($scriptProperties['remove_read']) ? (boolean) $scriptProperties['remove_read'] : true;
$options['include_keys'] = isset($scriptProperties['include_keys']) ? (boolean) $scriptProperties['include_keys'] : false;
$options['show_filename'] = (isset($scriptProperties['show_filename']) && !empty($scriptProperties['show_filename'])) ? true : false;

$modx->getService('registry', 'registry.modRegistry');
$modx->registry->addRegister($register, $register_class, array('directory' => $register));
if (!$modx->registry->$register->connect()) return $modx->error->failure($modx->lexicon('error'));

$modx->registry->$register->subscribe($topic);
$msgs = $modx->registry->$register->read($options);
if (!empty($msgs)) {
    switch ($format) {
        case 'html_log':
            $message = '';
            foreach ($msgs as $msgKey => $msg) {
                if (!empty ($msg['def'])) $msg['def']= $msg['def'].' ';
                if (!empty ($msg['file'])) $msg['file']= '@ '.$msg['file'].' ';
                if (!empty ($msg['line'])) $msg['line']= 'line '.$msg['line'].' ';
                $message .= '<span class="' . strtolower($msg['level']) . '">';
                if ($options['show_filename']) {
                    $message .= '<small>(' . trim($msg['def'] . $msg['file'] . $msg['line']) . ')</small>';
                }
                $message .= $msg['msg']."</span><br />\n";
            }
            if (!empty($message)) {
                return $modx->error->success($message);
            }
            break;
        case 'json':
            return $modx->error->success($modx->toJSON($msgs));
            break;
        default:
            return $modx->error->success($msgs);
            break;
    }
}
return $modx->error->success('');
