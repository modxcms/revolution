<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Defines the interface for the modX email service.
 *
 * @abstract Implement a derivative of this class to define an actual email service implementation.
 * @package modx
 * @subpackage mail
 */
abstract class modMail {
    /**
     * @const An option for setting the mail body
     */
    const MAIL_BODY = 'mail_body';
    /**
     * @const An option for setting the mail body text
     */
    const MAIL_BODY_TEXT = 'mail_body_text';
    /**
     * @const An option for setting the mail charset
     */
    const MAIL_CHARSET = 'mail_charset';
    /**
     * @const An option for setting the mail content type
     */
    const MAIL_CONTENT_TYPE = 'mail_content_type';
    /**
     * @const An option for setting the mail encoding
     */
    const MAIL_ENCODING = 'mail_encoding';
    /**
     * @const An option for setting the mail engine
     */
    const MAIL_ENGINE = 'mail_engine';
    /**
     * @const An option for setting the mail engine path
     */
    const MAIL_ENGINE_PATH = 'mail_engine_path';
    /**
     * @const An option for setting the mail error information
     */
    const MAIL_ERROR_INFO = 'mail_error_info';
    /**
     * @const An option for setting the mail From address
     */
    const MAIL_FROM = 'mail_from';
    /**
     * @const An option for setting the mail From name
     */
    const MAIL_FROM_NAME = 'mail_from_name';
    /**
     * @const An option for setting the mail hostname
     */
    const MAIL_HOSTNAME = 'mail_hostname';
    /**
     * @const An option for setting the mail language
     */
    const MAIL_LANGUAGE = 'mail_language';
    /**
     * @const An option for setting the mail priority header
     */
    const MAIL_PRIORITY = 'mail_priority';
    /**
     * @const An option for setting the mail read to header
     */
    const MAIL_READ_TO = 'mail_read_to';
    /**
     * @const An option for setting the mail sender
     */
    const MAIL_SENDER = 'mail_sender';
    /**
     * @const An option for setting the mail service
     */
    const MAIL_SERVICE = 'mail_service';
    /**
     * @const An option for setting the mail SMTP auth type
     */
    const MAIL_SMTP_AUTH = 'mail_smtp_auth';
    /**
     * @const An option for setting the mail SMTP HELO boolean
     */
    const MAIL_SMTP_HELO = 'mail_smtp_helo';
    /**
     * @const An option for setting the mail SMTP hosts
     */
    const MAIL_SMTP_HOSTS = 'mail_smtp_hosts';
    /**
     * @const An option for setting the mail SMTP Keep-Alive boolean
     */
    const MAIL_SMTP_KEEPALIVE = 'mail_smtp_keepalive';
    /**
     * @const An option for setting the mail SMTP password
     */
    const MAIL_SMTP_PASS = 'mail_smtp_pass';
    /**
     * @const An option for setting the mail SMTP port
     */
    const MAIL_SMTP_PORT = 'mail_smtp_port';
    /**
     * @const An option for setting the mail SMTP prefix
     */
    const MAIL_SMTP_PREFIX = 'mail_smtp_prefix';
    /**
     * @const An option for setting the mail SMTP AutoTLS option
     */
    const MAIL_SMTP_AUTOTLS = 'mail_smtp_autotls';
    /**
     * @const An option for setting the mail SMTP Single-To option
     */
    const MAIL_SMTP_SINGLE_TO = 'mail_smtp_single_to';
    /**
     * @const An option for setting the mail SMTP timeout
     */
    const MAIL_SMTP_TIMEOUT = 'mail_smtp_timeout';
    /**
     * @const An option for setting the mail SMTP username
     */
    const MAIL_SMTP_USER = 'mail_smtp_user';
    /**
     * @const An option for setting the mail subject
     */
    const MAIL_SUBJECT = 'mail_subject';
    /**
     * @const An option for setting the mail DKIM selector
     */
    const MAIL_DKIM_SELECTOR = 'mail_dkim_selector';
    /**
     * @const An option for setting the DKIM identity you're signing as - usually your From address
     */
    const MAIL_DKIM_IDENTITY = 'mail_dkim_identity';
    /**
     * @const An option for setting DKIM domain name
     */
    const MAIL_DKIM_DOMAIN = 'mail_dkim_domain';
    /**
     * @const An option for setting DKIM private key file path
     */
    const MAIL_DKIM_PRIVATEKEYFILE = 'mail_dkim_privatekeyfile';
    /**
     * @const An option for setting DKIM private key string - takes precedence over MAIL_DKIM_PRIVATEKEYFILE
     */
    const MAIL_DKIM_PRIVATEKEYSTRING = 'mail_dkim_privatekeystring';
    /**
     * @const An option for setting DKIM passphrase - used if your private key has a passphrase
     */
    const MAIL_DKIM_PASSPHRASE = 'mail_dkim_passphrase';

    /**
     * A reference to the modX instance communicating with this service instance.
     * @var modX
     */
    public $modx= null;
    /**
     * A collection of attributes defining all of the details of email communication.
     * @var array
     */
    public $attributes= array();
    /**
     * The mailer object responsible for implementing the modMail methods.
     * @var object
     */
    public $mailer= null;
    /**
     * A collection of all the current headers for the object.
     * @var array
     */
    public $headers= array();
    /**
     * An array of address types: to, cc, bcc, reply-to
     * @var array
     */
    public $addresses= array(
        'to' => array(),
        'cc' => array(),
        'bcc' => array(),
        'reply-to' => array(),
    );
    /**
     * An array of attached files
     * @var array
     */
    public $files= array();
    /**
     * An array of embedded images
     * @var array
     */
    public $images= array();
    /**
     * Error
     * @access protected
     * @var modError
     */
     protected $error = null;

    /**
     * Constructs a new instance of the modMail class.
     *
     * @param modX &$modx A reference to the modX instance
     * @param array $attributes An array of attributes to assign to the new mail instance
     */
    function __construct(modX &$modx, array $attributes= array()) {
        $this->modx= & $modx;
        if (!$this->modx->lexicon) {
            $this->modx->getService('lexicon','modLexicon');
        }
        $this->modx->lexicon->load('mail');
        $this->defaultAttributes = is_array($attributes) ? $attributes : array();
        $this->attributes= $this->getDefaultAttributes($attributes);
    }

    /**
     * Gets the default attributes for modMail based on system settings
     *
     * @param array $attributes An optional array of default attributes to override with
     * @return array An array of default attributes
     */ 
    public function getDefaultAttributes(array $attributes = array()) {
        $default = array();
        if ($this->modx->getOption('mail_use_smtp',false)) {
            $default[modMail::MAIL_ENGINE] = 'smtp';
            $default[modMail::MAIL_SMTP_AUTH] = $this->modx->getOption('mail_smtp_auth',null,false);
            $helo = $this->modx->getOption('mail_smtp_helo','');
            if (!empty($helo)) { $default[modMail::MAIL_SMTP_HELO] = $helo; }
            $default[modMail::MAIL_SMTP_HOSTS] = $this->modx->getOption('mail_smtp_hosts',null,'localhost');
            $default[modMail::MAIL_SMTP_KEEPALIVE] = $this->modx->getOption('mail_smtp_keepalive',null,false);
            $default[modMail::MAIL_SMTP_PASS] = $this->modx->getOption('mail_smtp_pass',null,'');
            $default[modMail::MAIL_SMTP_PORT] = $this->modx->getOption('mail_smtp_port',null,25);
            $default[modMail::MAIL_SMTP_PREFIX] = $this->modx->getOption('mail_smtp_prefix',null,'');
            $default[modMail::MAIL_SMTP_AUTOTLS] = $this->modx->getOption('mail_smtp_autotls',null,true);
            $default[modMail::MAIL_SMTP_SINGLE_TO] = $this->modx->getOption('mail_smtp_single_to',null,false);
            $default[modMail::MAIL_SMTP_TIMEOUT] = $this->modx->getOption('mail_smtp_timeout',null,10);
            $default[modMail::MAIL_SMTP_USER] = $this->modx->getOption('mail_smtp_user',null,'');
        }
        $default[modMail::MAIL_CHARSET] = $this->modx->getOption('mail_charset',null,'UTF-8');
        $default[modMail::MAIL_ENCODING] = $this->modx->getOption('mail_encoding',null,'8bit');

        /* first start with this method default, then constructor passed-in default, then method passed-in attributes */
        return array_merge($default,$this->defaultAttributes,$attributes);
    }

    /**
     * Gets a reference to an attribute of the mail object.
     *
     * @access public
     * @param string $key The attribute key.
     * @return mixed A reference to the attribute, or null if no attribute value is set for the key.
     */
    public function & get($key) {
        $value= null;
        if (isset($this->attributes[$key])) {
            $value = & $this->attributes[$key];
        }
        return $value;
    }

    /**
     * Sets the value of an attribute of the mail object.
     *
     * {@internal Override this method in a derivative to set the appropriate attributes of the
     * actual mailer implementation being used. Make sure to call this parent implementation first
     * and then set the value of the corresponding mailer attribute as a reference to the attribute
     * set in $this->attributes}
     *
     * @abstract
     * @access public
     * @param string $key The key of the attribute to set.
     * @param mixed $value The value of the attribute.
     */
    public function set($key, $value) {
        $this->attributes[$key]= $value;
    }

    /**
     * Add a new recipient email address to one of the valid address type buckets.
     *
     * @access public
     * @param string $type The address type to add; to, cc, bcc, or reply-to.
     * @param string $email The email address.
     * @param string $name An optional name for the addressee.
     * @return boolean Indicates if the address was set/unset successfully.
     */
    public function address($type, $email, $name= '') {
        $set= false;
        $type = strtolower($type);
        if (isset($this->addresses[$type])) {
            if ($email === null && isset($this->addresses[$type][$email])) {
                $this->addresses[$type][$email]= null;
                $set= true;
            } else {
                $this->addresses[$type][$email]= array($email, $name);
                $set= true;
            }
        }
        return $set;
    }

    /**
     * Adds a header to the mailer
     *
     * @access public
     * @param string $header The HTTP header to send.
     * @return boolean True if the header is valid and is set.
     */
    public function header($header) {
        $set= false;
        $parsed= explode(':', $header, 2);
        if ($parsed && count($parsed) == 2) {
            $this->headers[]= $parsed;
            $set= true;
        }
        return $set;
    }

    /**
     * Send an email setting any supplied attributes before sending.
     *
     * {@internal You should implement the rest of this method in a derivative class.}
     *
     * @abstract
     * @access public
     * @param array $attributes Attributes to override any existing attributes before sending.
     * @return boolean Indicates if the email was sent successfully.
     */
    public function send(array $attributes= array()) {
        if (is_array($attributes)) {
            foreach ($attributes as $attrKey => $attrVal) {
                $this->set($attrKey, $attrVal);
            }
        }
        return false;
    }

    /**
     * Reset the mail service, clearing addresses and attributes.
     *
     * @access public
     * @param array $attributes An optional array of attributes to apply after reset.
     */
    public function reset(array $attributes = array()) {
        $this->addresses= array(
            'to' => array(),
            'cc' => array(),
            'bcc' => array(),
            'reply-to' => array(),
        );
        $attributes = $this->getDefaultAttributes($attributes);
        foreach ($this->attributes as $attrKey => $attrVal) {
            if (array_key_exists($attrKey, $attributes)) {
                $this->set($attrKey, $attributes[$attrKey]);
            } else {
                $this->set($attrKey, null);
            }
        }
    }

    /**
     * Get an instance of the email class responsible for sending emails from the modEmail service.
     *
     * {@internal Implement this function in derivatives and call it in the constructor after all
     * other dependencies have been satisfied.}
     *
     * @abstract
     * @access protected
     * @return boolean Indicates if the mailer class was instantiated successfully.
     */
    abstract protected function _getMailer();

    /**
     * Attach a file to the attachments queue.
     *
     * @access public
     * @param string $file The absolute path to the file
     */
    public function attach($file) {
        array_push($this->files,$file);
    }

    /**
     * Add an embedded image.
     *
     * @access public
     * @param string $image The absolute path to the file
     * @param string $cid Id of the image by wich it will be available in html.
     *        Example: <img src="cid:<$cid>" />
     */
    public function embedImage($image, $cid) {
        array_push($this->images,array('image' => $image, 'cid' => $cid));
    }

    /**
     * Clear all embedded images.
     *
     * @access public
     */
    public function clearEmbeddedImages() {
        $this->images = array();
    }

    /**
     * Clear all existing attachments.
     *
     * @access public
     */
    public function clearAttachments() {
        $this->files = array();
    }

    /**
     * Check if there is any error.
     *
     * @access public
     * @return boolean Indicates if there is error.
     */
    public function hasError() {
        return $this->error !== null && $this->error instanceof modError && $this->error->hasError();
    }

    /**
     * Get error object
     *
     * @access public
     * @return null|modError
     */
    public function getError() {
        return $this->error;
    }
}
