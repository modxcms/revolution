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
 * @package modx
 * @subpackage mail
 */
class modPHPMailer extends modMail {
    /**
     * Constructs a new instance of the modPHPMailer class.
     *
     * @param modX $modx A reference to the modX instance
     * @param array $attributes An array of attributes for the instance
     * @return modPHPMailer
     */
    function __construct(modX &$modx, array $attributes= array()) {
        parent :: __construct($modx, $attributes);
        require_once $modx->getOption('core_path') . 'model/modx/mail/phpmailer/class.phpmailer.php';
        $this->_getMailer();
    }

    /**
     * Sets a PHPMailer attribute corresponding to the modX::MAIL_* constants or
     * a custom key.
     *
     * @param string $key The attribute key to set
     * @param mixed $value The value to set
     */
    public function set($key, $value) {
        parent :: set($key, $value);
        switch ($key) {
            case modMail::MAIL_BODY :
                $this->mailer->Body= $this->attributes[$key];
                break;
            case modMail::MAIL_BODY_TEXT :
                $this->mailer->AltBody= $this->attributes[$key];
                break;
            case modMail::MAIL_CHARSET :
                $this->mailer->CharSet= $this->attributes[$key];
                break;
            case modMail::MAIL_CONTENT_TYPE :
                $this->mailer->ContentType= $this->attributes[$key];
                break;
            case modMail::MAIL_ENCODING :
                $this->mailer->Encoding= $this->attributes[$key];
                break;
            case modMail::MAIL_ENGINE :
                $this->mailer->Mailer= $this->attributes[$key];
                break;
            case modMail::MAIL_ENGINE_PATH :
                $this->mailer->Sendmail= $this->attributes[$key];
                break;
            case modMail::MAIL_FROM :
                $this->mailer->From= $this->attributes[$key];
                $this->mailer->Sender= $this->attributes[$key];
                break;
            case modMail::MAIL_FROM_NAME :
                $this->mailer->FromName= $this->attributes[$key];
                break;
            case modMail::MAIL_HOSTNAME :
                $this->mailer->Hostname= $this->attributes[$key];
                break;
            case modMail::MAIL_LANGUAGE :
                $this->mailer->SetLanguage($this->attributes[$key]);
                break;
            case modMail::MAIL_PRIORITY :
                $this->mailer->Priority= $this->attributes[$key];
                break;
            case modMail::MAIL_READ_TO :
                $this->mailer->ConfirmReadingTo= $this->attributes[$key];
                break;
            case modMail::MAIL_SENDER :
                $this->mailer->Sender= $this->attributes[$key];
                break;
            case modMail::MAIL_SMTP_AUTH :
                $this->mailer->SMTPAuth= $this->attributes[$key];
                break;
            case modMail::MAIL_SMTP_HELO :
                $this->mailer->Helo= $this->attributes[$key];
                break;
            case modMail::MAIL_SMTP_HOSTS :
                $this->mailer->Host= $this->attributes[$key];
                break;
            case modMail::MAIL_SMTP_KEEPALIVE :
                $this->mailer->SMTPKeepAlive= $this->attributes[$key];
                break;
            case modMail::MAIL_SMTP_PASS :
                $this->mailer->Password= $this->attributes[$key];
                break;
            case modMail::MAIL_SMTP_PORT :
                $this->mailer->Port= $this->attributes[$key];
                break;
            case modMail::MAIL_SMTP_PREFIX :
                $this->mailer->SMTPSecure= $this->attributes[$key];
                break;
            case modMail::MAIL_SMTP_SINGLE_TO :
                $this->mailer->SingleTo= $this->attributes[$key];
                break;
            case modMail::MAIL_SMTP_TIMEOUT :
                $this->mailer->Timeout= $this->attributes[$key];
                break;
            case modMail::MAIL_SMTP_USER :
                $this->mailer->Username= $this->attributes[$key];
                break;
            case modMail::MAIL_SUBJECT :
                $this->mailer->Subject= $this->attributes[$key];
                break;
            default :
                $this->modx->log(modX::LOG_LEVEL_WARN, $this->modx->lexicon('mail_err_attr_nv',array('attr' => $key)));
                break;
        }
    }

    /**
     * Adds an address to the mailer
     *
     * @param string $type The type of address (to, reply-to, bcc, cc)
     * @param string $email The email address to address to
     * @param string $name The name of the email address
     * @return boolean True if was addressed
     */
    public function address($type, $email, $name= '') {
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
            $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('mail_err_unset_spec'));
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('mail_err_address_ns'));
        }
        return $set;
    }

    /**
     * Adds a custom header to the mailer
     *
     * @param string $header The header to set
     * @return boolean True if the header was successfully set
     */
    public function header($header) {
        $set= parent :: header($header);
        if ($set) {
            $this->mailer->AddCustomHeader($header);
        }
        return $set;
    }

    /**
     * Send the email, applying any attributes to the mailer before sending.
     *
     * @param array $attributes An array of attributes to pass when sending
     * @return boolean True if the email was successfully sent
     */
    public function send(array $attributes= array()) {
        $sent = parent :: send($attributes);
        $sent = $this->mailer->Send();
        return $sent;
    }

    /**
     * Resets all PHPMailer attributes, including recipients and attachments.
     *
     * @param array $attributes An array of attributes to pass when resetting
     */
    public function reset(array $attributes= array()) {
        parent :: reset($attributes);
        $this->mailer->ClearAllRecipients();
        $this->mailer->ClearReplyTos();
        $this->mailer->ClearAttachments();
        $this->mailer->ClearCustomHeaders();
        $this->mailer->IsHTML(false);
    }

    /**
     * Loads the PHPMailer object used to send the emails in this implementation.
     *
     * @return boolean True if the mailer class was successfully loaded
     */
    protected function _getMailer() {
        $success= false;
        if (!$this->mailer || !($this->mailer instanceof PHPMailer)) {
            if ($this->mailer= new PHPMailer()) {
                if (!empty($this->attributes)) {
                    foreach ($this->attributes as $attrKey => $attrVal) {
                        $this->set($attrKey, $attrVal);
                    }
                }
                if (!isset($this->attributes[modMail::MAIL_LANGUAGE])) {
                    $this->set(modMail::MAIL_LANGUAGE, $this->modx->config['manager_language']);
                }
                $success= true;
            }
        }
        return $success;
    }

    /**
     * Attaches a file to the mailer.
     *
     * @param mixed $file The file to attach
     * @param string $name The name of the file to attach as
     * @param string $encoding The encoding of the attachment
     * @param string $type The header type of the attachment
     */
    public function attach($file,$name = '',$encoding = 'base64',$type = 'application/octet-stream') {
        parent :: attach($file);
        $this->mailer->AddAttachment($file,$name,$encoding,$type);
    }

    /**
     * Clears all existing attachments.
     */
    public function clearAttachments() {
        parent :: clearAttachments();
        $this->mailer->ClearAttachments();
    }

    /**
     * Sets email to HTML or text-only.
     *
     * @access public
     * @param boolean $toggle True to set to HTML.
     */
    public function setHTML($toggle) {
        $this->mailer->IsHTML($toggle);
    }
}