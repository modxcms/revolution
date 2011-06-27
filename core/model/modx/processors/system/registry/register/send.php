<?php
/**
 * Send a message to the registry.
 *
 * @param string $register The register to read from.
 * @param string $register_class (optional) If set, will load a custom registry class.
 * @param string $topic The topic in the register to read from.
 * @param string $message The message to send.
 * @param string $message_key (optional) If set, will create the message with this as the key.
 * @param string $message_format (optional) The format of the message. Defaults to string.
 * Also supports json for storing an array of data.
 * @param integer $delay (optional) The delay in seconds to send by. Defaults to 0.
 * @param integer $ttl (optional) The time to live of the message. Defaults to 0, or forever.
 * @param integer $kill (optional) Defaults to false.
 *
 * @package modx
 * @subpackage processors.system.registry.register
 */

if (!isset($scriptProperties['register']) || empty($scriptProperties['register']) || !preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $scriptProperties['register'])) return $modx->error->failure($modx->lexicon('error'));
if (!isset($scriptProperties['topic']) || empty($scriptProperties['topic'])) return $modx->error->failure($modx->lexicon('error'));

$register = trim($scriptProperties['register']);
$register_class = isset($scriptProperties['register_class']) ? trim($scriptProperties['register_class']) : 'registry.modFileRegister';

$topic = trim($scriptProperties['topic']);

$message = isset($scriptProperties['message']) ? trim($scriptProperties['message']) : '';
$message_key = isset($scriptProperties['message_key']) ? $scriptProperties['message_key'] : '';
$message_format = isset($scriptProperties['message_format']) ? trim($scriptProperties['message_format']) : 'string';

if (!empty($message)) {
    $options = array();
    $options['delay'] = isset($scriptProperties['delay']) ? intval($scriptProperties['delay']) : 0;
    $options['ttl'] = isset($scriptProperties['ttl']) ? intval($scriptProperties['ttl']) : 0;
    $options['kill'] = (isset($scriptProperties['kill']) && !empty($scriptProperties['kill'])) ? true : false;

    $modx->getService('registry', 'registry.modRegistry');
    $modx->registry->addRegister($register, $register_class, array('directory' => $register));
    if (!$modx->registry->$register->connect()) return $modx->error->failure($modx->lexicon('error'));

    $modx->registry->$register->subscribe($topic);

    switch ($message_format) {
        case 'json':
            $message = $modx->fromJSON($message);
            break;
        case 'string':
        default:
            break;
    }

    if (empty($message_key)) {
        if (is_scalar($message)) {
            $message = array($message);
        }
    } else {
        $message = array($message_key => $message);
    }

    if (!$modx->registry->$register->send($topic, $message, $options)) {
        return $modx->error->failure($modx->lexicon('error'));
    }

    return $modx->error->success('');
}
return $modx->error->failure($modx->lexicon('error'));
