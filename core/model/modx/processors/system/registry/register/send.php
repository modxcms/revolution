<?php
/**
 * Send a message to the registry.
 *
 * @param string $register The register to read from
 * @param string $register_class (optional) If set, will load a custom registry
 * class.
 * @param string $topic The topic in the register to read from
 * @param string $message The message to send
 * @param string $message_format (optional) The format of the message. Defaults
 * to string.
 * @param integer $delay (optional) The delay in seconds to send by. Defaults to
 * 0.
 * @param integer $ttl (optional) The time to live of the message. Defaults to
 * 0, or forever.
 * @param integer $kill (optional) Defaults to false.
 *
 * @package modx
 * @subpackage processors.system.registry.register
 */

if (!isset($_POST['register']) || empty($_POST['register']) || !preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $_POST['register'])) return $modx->error->failure($modx->lexicon('error'));
if (!isset($_POST['topic']) || empty($_POST['topic'])) return $modx->error->failure($modx->lexicon('error'));

$register = trim($_POST['register']);
$register_class = isset($_POST['register_class']) ? trim($_POST['register_class']) : 'registry.modFileRegister';

$topic = trim($_POST['topic']);

$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$message_format = isset($_POST['message_format']) ? trim($_POST['message_format']) : 'string';

$options = array();
$options['delay'] = isset($_POST['delay']) ? intval($_POST['delay']) : 0;
$options['ttl'] = isset($_POST['ttl']) ? intval($_POST['ttl']) : 0;
$options['kill'] = (isset($_POST['kill']) && !empty($_POST['kill'])) ? true : false;

$modx->getService('registry', 'registry.modRegistry');
$modx->registry->addRegister($register, $register_class, array('directory' => $register));
if (!$modx->registry->$register->connect()) return $modx->error->failure($modx->lexicon('error'));

$modx->registry->$register->subscribe($topic);

switch ($message_format) {
    case 'string':
    default:
        $message = array($message);
        break;
}

if (!$modx->registry->$register->send($topic, $message, $options)) {
    return $modx->error->failure($modx->lexicon('error'));
}

return $modx->error->success('');
