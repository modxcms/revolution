<?php
/**
 * This file contains the PHPMailer implementation of the modMail email service.
 * @package modx
 * @subpackage mail
 */

require_once MODX_CORE_PATH . 'model/modx/mail/modmail.class.php';

/**
 * PHPMailer implementation of the modMail service.
 *
 * {@inheritdoc}
 */
class modPHPMailer extends modMail {
    /**#@+
     * Constructs a new instance of the modPHPMailer class.
     *
     * {@inheritdoc}
     */
    function modPHPMailer(& $modx, $attributes= array()) {
        $this->_construct($modx, $attributes);
    }
    /** @ignore */
    function __construct(& $modx, $attributes= array()) {
        parent :: __construct($modx, $attributes);
        require_once MODX_CORE_PATH . 'model/modx/mail/phpmailer/class.phpmailer.php';
        $this->_getMailer();
    }
    /**#@-*/

    /**
     * Sets a PHPMailer attribute corresponding to the MODX_EMAIL_* constants or a custom key.
     *
     * {@inheritdoc}
     */
    function set($key, $value) {
        parent :: set($key, $value);
        switch ($key) {
            case MODX_MAIL_BODY :
                $this->mailer->Body= $this->attributes[$key];
                break;
            case MODX_MAIL_BODY_TEXT :
                $this->mailer->AltBody= $this->attributes[$key];
                break;
            case MODX_MAIL_CHARSET :
                $this->mailer->CharSet= $this->attributes[$key];
                break;
            case MODX_MAIL_CONTENT_TYPE :
                $this->mailer->ContentType= $this->attributes[$key];
                break;
            case MODX_MAIL_ENCODING :
                $this->mailer->Encoding= $this->attributes[$key];
                break;
            case MODX_MAIL_ENGINE :
                $this->mailer->Mailer= $this->attributes[$key];
                break;
            case MODX_MAIL_ENGINE_PATH :
                $this->mailer->Sendmail= $this->attributes[$key];
                break;
            case MODX_MAIL_FROM :
                $this->mailer->From= $this->attributes[$key];
                break;
            case MODX_MAIL_FROM_NAME :
                $this->mailer->FromName= $this->attributes[$key];
                break;
            case MODX_MAIL_HOSTNAME :
                $this->mailer->Hostname= $this->attributes[$key];
                break;
            case MODX_MAIL_LANGUAGE :
                $this->mailer->SetLanguage($this->attributes[$key]);
                break;
            case MODX_MAIL_PRIORITY :
                $this->mailer->Priority= $this->attributes[$key];
                break;
            case MODX_MAIL_READ_TO :
                $this->mailer->ConfirmReadingTo= $this->attributes[$key];
                break;
            case MODX_MAIL_SENDER :
                $this->mailer->Sender= $this->attributes[$key];
                break;
            case MODX_MAIL_SMTP_AUTH :
                $this->mailer->SMTPAuth= $this->attributes[$key];
                break;
            case MODX_MAIL_SMTP_HELO :
                $this->mailer->Helo= $this->attributes[$key];
                break;
            case MODX_MAIL_SMTP_HOSTS :
                $this->mailer->Host= $this->attributes[$key];
                break;
            case MODX_MAIL_SMTP_KEEPALIVE :
                $this->mailer->SMTPKeepAlive= $this->attributes[$key];
                break;
            case MODX_MAIL_SMTP_PASS :
                $this->mailer->Password= $this->attributes[$key];
                break;
            case MODX_MAIL_SMTP_PORT :
                $this->mailer->Port= $this->attributes[$key];
                break;
            case MODX_MAIL_SMTP_PREFIX :
                $this->mailer->SMTPSecure= $this->attributes[$key];
                break;
            case MODX_MAIL_SMTP_SINGLE_TO :
                $this->mailer->SingleTo= $this->attributes[$key];
                break;
            case MODX_MAIL_SMTP_TIMEOUT :
                $this->mailer->Timeout= $this->attributes[$key];
                break;
            case MODX_MAIL_SMTP_USER :
                $this->mailer->Username= $this->attributes[$key];
                break;
            case MODX_MAIL_SUBJECT :
                $this->mailer->Subject= $this->attributes[$key];
                break;
            default :
                $this->modx->log(MODX_LOG_LEVEL_WARN, $this->modx->lexicon('mail_err_attr_nv',array('attr' => $key)));
                break;
        }
    }

    /**
     * Adds an address to the mailer
     *
     * {@inheritDoc}
     */
    function address($type, $email, $name= '') {
        $set= false;
        if ($email) {
            $set= parent :: address($type, $email, $name);
            if ($set) {
                $type= strtolower($type);
                switch ($type) {
                    case 'to' :
                        $this->mailer->AddAddress($email, $name);
                        break;
                    case 'cc' :
                        $this->mailer->AddCC($email, $name);
                        break;
                    case 'bcc' :
                        $this->mailer->AddBCC($email, $name);
                        break;
                    case 'reply-to' :
                        $this->mailer->AddReplyTo($email, $name);
                        break;
                }
            }
        } elseif ($email === null) {
            $this->modx->log(MODX_LOG_LEVEL_ERROR, $this->modx->lexicon('mail_err_unset_spec'));
        } else {
            $this->modx->log(MODX_LOG_LEVEL_ERROR, $this->modx->lexicon('mail_err_address_ns'));
        }
        return $set;
    }

    /**
     * Adds a custom header to the mailer
     *
     * {@inheritDoc}
     */
    function header($header) {
        $set= parent :: header($header);
        if ($set) {
            $this->mailer->AddCustomHeader($header);
        }
        return $set;
    }

    /**
     * Send the email, applying any attributes to the mailer before sending.
     *
     * {@inheritdoc}
     */
    function send($attributes= array()) {
        $sent = parent :: send($attributes);
        $sent = $this->mailer->Send();
        return $sent;
    }

    /**
     * Resets all PHPMailer attributes, including recipients and attachments.
     *
     * {@inheritdoc}
     */
    function reset($attributes= array()) {
        parent :: reset($attributes);
        $this->mailer->ClearAllRecipients();
        $this->mailer->ClearAttachments();
        $this->mailer->IsHTML(false);
    }

    /**
     * Loads the PHPMailer object used to send the emails in this implementation.
     *
     * {@inheritdoc}
     */
    function _getMailer() {
        $success= false;
        if (!$this->mailer || !is_a($this->mailer, 'PHPMailer')) {
            if ($this->mailer= new PHPMailer()) {
                if (!empty($this->attributes)) {
                    foreach ($this->attributes as $attrKey => $attrVal) {
                        $this->set($attrKey, $attrVal);
                    }
                }
                if (!isset($this->attributes[MODX_MAIL_LANGUAGE])) {
                    $this->set(MODX_MAIL_LANGUAGE, $this->modx->config['manager_language']);
                }
                $success= true;
            }
        }
        return $success;
    }

    /**
     * Attaches a file to the mailer.
     *
     * {@inheritDoc}
     */
    function attach($file) {
        parent :: attach($file);
        $this->mailer->AddAttachment($file);
    }

    /**
     * Clears all existing attachments.
     *
     * {@inheritDoc}
     */
    function clearAttachments() {
        parent :: clearAttachments();
        $this->mailer->ClearAttachments();
    }

    /**
     * Sets email to HTML or text-only.
     *
     * @access public
     * @param boolean $toggle True to set to HTML.
     */
    function setHTML($toggle) {
        $this->mailer->IsHTML($toggle);
    }
}