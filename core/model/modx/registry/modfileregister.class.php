<?php
/**
 * This file contains a simple file-based implementation of modRegister.
 *
 * @package modx
 * @subpackage registry
*/

/** Make sure the modRegister class is included. */
require_once(dirname(__FILE__) . '/modregister.class.php');

/**
 * A simple, file-based implementation of modRegister.
 *
 * This implementation does not address transactional conflicts and should be
 * used in non-critical processes that are easily recoverable.
 *
 * @package modx
 * @subpackage registry
 */
class modFileRegister extends modRegister {
    /**
     * A physical directory where the register stores topics and messages.
     * @var string
     */
    protected $directory = null;

    /**
     * Construct a new modFileRegister instance.
     *
     * @param modX &$modx A reference to a modX instance.
     * @param string $key A valid PHP variable which will be set on the modRegistry instance.
     * @param array $options Optional array of registry options.
     */
    function __construct(& $modx, $key, $options = array())
    {
        parent::__construct($modx, $key, $options);

        $modx->getCacheManager();
        $this->directory = $modx->getCachePath() . 'registry/';
        $this->directory .= isset($options['directory'])
            ? $options['directory']
            : $key;

        $this->directory = rtrim($this->directory, '/') . '/';
    }

    /**
     * Make sure the register can write to the specified $directory.
     *
     * {@inheritdoc}
     */
    public function connect(array $attributes = array()) {
        $connected = false;
        if (is_string($this->directory) && strlen($this->directory)) {
            $connected = $this->modx->cacheManager->writeTree($this->directory);
        }
        return $connected;
    }

    /**
     * Clear the register messages.
     *
     * {@inheritdoc}
     */
    public function clear($topic)
    {
        $topicDirectory = $this->directory . ltrim($this->sanitizePath($topic), '/');

        return $this->modx->cacheManager->deleteTree(
            realpath($topicDirectory),
            array(
                'extensions' => array('.msg.php')
            )
        );
    }

    /**
     * {@inheritdoc}
     *
     * This implementation supports the following options and default behavior:
     * <ul>
     * <li>msg_limit: Only poll until the specified limit of messages has
     * been digested. Default is 5 messages.</li>
     * <li>time_limit: Poll for new messages for a specified number of
     * seconds. Default is the result of the php time_limit system variable.</li>
     * <li>poll_limit: Only poll for new subscriptions a specified number
     * of times. Default is unlimited.</li>
     * <li>poll_interval: Wait a specified number of seconds between each
     * additional polling iteration, after the initial one. Default is no
     * interval.</li>
     * <li>remove_read: Remove the message immediately upon digesting it.
     * Default is true.</li>
     * <li>include_keys: Include the message keys in the array of messages returned.
     * Default is false.</li>
     * </ul>
     */
    public function read(array $options = array()) {
        $this->__kill = false;
        $messages = array();
        $topicMessages = array();
        $msgLimit = isset($options['msg_limit']) ? intval($options['msg_limit']) : 5;
        $timeLimit = isset($options['time_limit']) ? intval($options['time_limit']) : ini_get('time_limit');
        $pollLimit = isset($options['poll_limit']) ? intval($options['poll_limit']) : 0;
        $pollInterval = isset($options['poll_interval']) ? intval($options['poll_interval']) : 0;
        $removeRead = isset($options['remove_read']) ? (boolean) $options['remove_read'] : true;
        $includeKeys = isset($options['include_keys']) ? (boolean) $options['include_keys'] : false;
        $startTime = microtime(true);
        $time = $timeLimit <= 0 ? -1 : $startTime;
        $expires = $startTime + $timeLimit;
        $msgCount = 0;
        $iteration = 0;
        while ($time < $expires && $msgCount < $msgLimit && !$this->__kill) {
            if ($iteration > 0) {
                if ($pollLimit > 0 && $iteration >= $pollLimit) {
                    break;
                }
                if ($pollInterval > 0) sleep($pollInterval);
            }
            $iteration++;
            foreach ($this->subscriptions as $subIdx => $topic) {
                $topicMessages = array();
                $topicDirectory = $this->directory;
                $topicDirectory.= $topic[0] == '/' ? substr($topic, 1) : $topic ;
                if (is_dir($topicDirectory)) {
                    $dirListing = $this->getSortedDirectoryListing($topicDirectory);
                    if (!empty($dirListing)) {
                        foreach ($dirListing as $idx => $entry) {
                            if ($msgCount >= $msgLimit || $this->__kill) break;
                            if ($newMsg = $this->_readMessage($topicDirectory . $entry, $removeRead)) {
                                if (!$includeKeys) {
                                    $topicMessages[] = $newMsg;
                                } else {
                                    $msgKey = substr($entry, 0, strpos($entry, '.msg.php'));
                                    $topicMessages[$msgKey] = $newMsg;
                                }
                                $msgCount++;
                            }
                        }
                    }
                }
                elseif ($newMsg = $this->_readMessage($topicDirectory . '.msg.php', $removeRead)) {
                    if (!$includeKeys) {
                        $topicMessages[] = $newMsg;
                    } else {
                        $topicMessages[$topicDirectory] = $newMsg;
                    }
                    $msgCount++;
                }
            }
            if (!empty($topicMessages)) {
                if (!$includeKeys) {
                    $messages = $messages + $topicMessages;
                } else {
                    $messages = array_merge($messages, $topicMessages);
                }
            }
            $time = microtime(true);
        }
        return $messages;
    }

    /**
     * Get list of topic messages from a directory sorted by modified date.
     *
     * @param string $dir A valid directory path.
     * @return array An array of topic messages sorted by modified date.
     */
    private function getSortedDirectoryListing($dir) {
        $listing = array();
        $d = new DirectoryIterator($dir);
        $idx = 0;
        foreach ($d as $f) {
            $filename = $f->getFilename();
            if ($f->isFile() && strpos($filename, '.msg.php')) {
                $listing[] = $filename;
                $idx++;
            }
        }
        if (!empty($listing)) sort($listing);
        return $listing;
    }

    /**
     * Read a message file from the queue.
     *
     * @todo Implement support for reading various message types, other than
     * executable PHP format.
     * @access private
     * @param string $filename An absolute path to a message file to read.
     * @param boolean $remove Indicates if the message file should be deleted
     * once the message is read from it.
     */
    private function _readMessage($filename, $remove = true) {
        $message = null;
        if (file_exists($filename)) {
            $message = @ include($filename);
            if ($remove) {
                @ unlink($filename);
            }
        }
        return $message;
    }

    /**
     * {@inheritdoc}
     *
     * This implementation provides support for sending messages using either
     * time-based indexes so they are consumed in the order they are produced,
     * or named indexes typically used when consumers want to subscribe to a
     * specific, unique message. Individual messages or message collections
     * passed in numerically indexed arrays are treated as time-based messages
     * and message collections passed in associative arrays are treated as named
     * messages. e.g., to send a single message as named, wrap it in an array
     * with the intended message name as the key.
     *
     * This implementation also supports a message_type option to indicate the
     * format of the message being sent to the register. Currently only supports
     * executable PHP format.
     *
     * Other implementation specific options include:
     * <ul>
     * <li>delay: Number of seconds to delay the message. This option is only
     * supported for time-based messages.</li>
     * <li>ttl: Number of seconds the message is valid in the queue.
     * Default is forever or 0.</li>
     * <li>kill: Tells a message consumer to stop consuming any more
     * messages after reading any message sent with this option.</li>
     * </ul>
     *
     * @todo Implement support for sending various message types, other than
     * executable PHP format.
     */
    public function send($topic, $message, array $options = array()) {
        $sent = false;
        if (empty($topic) || $topic[0] != '/') $topic = $this->_currentTopic . $topic;
        $topicIdx = array_search($topic, $this->subscriptions);
        $topic = substr($topic, 1);
        if ($topicIdx !== false) {
            $messageType = isset($options['message_type']) ? $options['message_type'] : 'php';
            $topicDirectory = $this->directory . $topic;
            if ($topicDirectory[strlen($topicDirectory) - 1] != '/') $topicDirectory .= '/';
            if (!is_array($message)) {
                $message = array($message);
            }
            foreach ($message as $msgIdx => $msg) {
                if (is_scalar($msg) || is_array($msg) || is_object($msg)) {
                    switch ($messageType) {
                        //TODO: implement more message types
                        case 'php' :
                        default :
                            $timestamp = isset($options['delay']) ? time() + intval($options['delay']) : time();
                            $expires = isset($options['ttl']) && !empty($options['ttl']) ? time() + intval($options['ttl']) : 0;
                            $kill = isset($options['kill']) ? (boolean) $options['kill'] : false;
                            if (!is_int($msgIdx)) {
                                if (strpos($msgIdx, '../') !== false) {
                                    $this->modx->log(modX::LOG_LEVEL_ERROR, "Directory traversal attempt in register message key; message skipped with key {$msgIdx}");
                                    break;
                                }
                                $msgKey = $msgIdx;
                            } else {
                                $msgKey = strftime('%Y%m%dT%H%M%S', $timestamp) . '-' . sprintf("%03d", $msgIdx);
                            }
                            $filename = $topicDirectory . $msgKey . '.msg.php';
                            $content = "<?php\n";
                            if ($expires > 0) $content.= "if (time() > {$expires}) return null;\n";
                            if ($kill) $content.= "\$this->__kill = true;\n";
                            $content.= 'return ' . var_export($msg, true) . ";\n";
                            $sent = $this->modx->cacheManager->writeFile($filename, $content);
                    }
                }
            }
        }
        return $sent;
    }

    public function close() {
        return true;
    }

    /**
     * Sanitize the specified path
     *
     * @param string $path The path to clean
     * @return string The sanitized path
     */
    protected function sanitizePath($path) {
        return preg_replace(array("/\.*[\/|\\\]/i", "/[\/|\\\]+/i"), array('/', '/'), $path);
    }
}
