<?php
/**
 * Login English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['login_language'] = 'Sprache';
$_lang['login_activation_key_err'] = 'Der Aktivierungsschlüssel stimmt nicht überein! Bitte überprüfen Sie Ihre Aktivierungs-E-Mail und stellen Sie sicher, dass Sie der richtigen URL gefolgt sind.';
$_lang['login_blocked_admin'] = 'Sie wurden von einem Administrator für den Manager gesperrt.';
$_lang['login_blocked_error'] = 'Sie wurden temporär geblockt und können sich nicht einloggen. Bitte versuchen Sie es später noch einmal.';
$_lang['login_blocked_ip'] = 'Sie dürfen sich von Ihrer aktuellen IP-Adresse aus nicht einloggen.';
$_lang['login_blocked_time'] = 'Sie dürfen sich momentan nicht einloggen. Bitte versuchen Sie es später noch einmal.';
$_lang['login_blocked_too_many_attempts'] = 'Sie wurden geblockt wegen zu vieler fehlgeschlagener Login-Versuche.';
$_lang['login_button'] = 'Login';
$_lang['login_cannot_locate_account'] = 'Der von Ihnen eingegebene Benutzername oder das Passwort ist falsch. Bitte überprüfen Sie den Benutzernamen, geben Sie das Passwort erneut ein und versuchen Sie es erneut.';
$_lang['login_copyright'] = '&copy; 2005-[[+current_year]] <a href="https://modx.com/about/company" target="_blank">MODX, LLC</a>. MODX Revolution&trade; ist lizenziert unter der GPLv2 oder später.';
$_lang['login_email_label'] = 'Account-E-Mail:';
$_lang['login_err_unknown'] = 'Beim Versuch, sich einzuloggen, ist ein unbekannter Fehler aufgetreten.';
$_lang['login_forget_your_login'] = 'Passwort vergessen?';
$_lang['login_forget_your_login_note'] = 'Um ein neues Passwort festzulegen, geben Sie unten bitte Ihren Benutzernamen oder Ihre E-Mail-Adresse ein. Wenn Sie ein Konto haben, wird ein Verifizierungs-Link an Ihre E-Mail-Adresse gesendet.';
$_lang['login_new_password'] = 'Neues Passwort';
$_lang['login_new_password_note'] = 'Bitte bestätigen Sie Ihr neues Passwort durch zweimalige Eingabe.<br><br>&bull;&nbsp;Die minimale Passwortlänge beträgt <strong>[[+length]]</strong> Zeichen.';
$_lang['login_confirm_password'] = 'Passwort bestätigen';
$_lang['login_back_to_login'] = 'Zurück zum Login';
$_lang['login_hostname_error'] = 'Ihr Hostname zeigt nicht zurück auf Ihre IP-Adresse.';
$_lang['login_message'] = 'Bitte geben Sie Ihre Zugangsdaten ein, um Ihre Manager-Sitzung zu starten. Achten Sie bei der Eingabe des Benutzernamens und des Passworts bitte auch auf Groß- und Kleinschreibung!';
$_lang['login_password'] = 'Passwort';
$_lang['login_password_reset_act_sent'] = 'Wenn der Benutzer bzw. die E-Mail-Adresse existiert, erhalten Sie in Kürze eine E-Mail.';
$_lang['login_remember'] = 'Eingeloggt bleiben für [[+lifetime]]';
$_lang['login_send_activation_email'] = 'Aktivierungs-E-Mail versenden';
$_lang['login_title'] = 'Login';
$_lang['login_user_err_nf_email'] = 'Wenn der Benutzer bzw. die E-Mail-Adresse existiert, erhalten Sie in Kürze eine E-Mail.';
$_lang['login_username'] = 'Benutzername';
$_lang['login_username_or_email'] = 'Benutzername oder E-Mail-Adresse';
$_lang['login_username_password_incorrect'] = 'Der von Ihnen eingegebene Benutzername oder das Passwort ist falsch. Bitte überprüfen Sie den Benutzernamen, geben Sie das Passwort noch einmal ein und versuchen Sie es erneut.';
$_lang['login_user_inactive'] = 'Ihr Benutzeraccount wurde deaktiviert. Bitte kontaktieren Sie Ihren Systemadministrator und bitten Sie ihn, den Account zu aktivieren.';
$_lang['login_email_subject'] = 'Ihre Login-Daten';
$_lang['login_magiclink_subject'] = 'Ihr Einmal-Login-Link';
$_lang['login_magiclink_err'] = 'Ihr Login-Link ist ungültig. Bitte fordern Sie einen neuen an.';
$_lang['login_magiclink_email'] = '<h2>Einmal-Login-Link</h2><p>Hier ist Ihr Link, um sich beim MODX-Manager anzumelden. Dieser Link ist gültig für die nächsten [[+expiration]].</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]?magiclink=[[+hash]]" class="btn">Login</a></p><p class="small">Wenn Sie diesen Link nicht angefordert haben, ignorieren Sie diese E-Mail.</p>';
$_lang['login_magiclink_default_msg'] = 'Wenn auf Ihre E-Mail Adresse <i>[[+email]]</i> ein Konto registriert ist, erhalten Sie in Kürze eine E-Mail.';
$_lang['login_magiclink_error_msg'] = 'Das System konnte keinen Login-Link per E-Mail senden. Bitte kontaktieren Sie den Administrator, wenn dieser Fehler dauerhaft auftritt.';
$_lang['login_forgot_email'] = '<h2>Passwort vergessen?</h2><p>Wir haben eine Anfrage zur Änderung Ihres MODX Revolution Passwortes erhalten. Sie können Ihr Passwort zurücksetzen, indem Sie auf die Schaltfläche unten klicken und den Anweisungen auf dem Bildschirm folgen.</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]?modhash=[[+hash]]" class="btn">Mein Passwort zurücksetzen</a></p><p class="small">Wenn Sie diese Anfrage nicht gesendet haben, ignorieren Sie bitte diese E-Mail.</p>';
$_lang['login_signup_email'] = '<p>Hallo, [[+username]]!</p><p>Ein Konto wurde für Sie auf der <strong>[[++site_name]]</strong> Website registriert. Wenn Sie Ihr Passwort nicht kennen, [[++allow_manager_login_forgot_password:is=`1`:then=`setzen Sie es über den Link "Passwort vergessen" auf dem Anmeldebildschirm zurück.`:else=`fragen Sie Ihren Site Administrator.`]].</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]" class="btn">Anmelden bei [[++site_name]]</a></p>';
$_lang['login_greeting_morning'] = '<strong>Guten Morgen</strong>, willkommen zurück!';
$_lang['login_greeting_afternoon'] = '<strong>Schönen Nachmittag</strong>, willkommen zurück!';
$_lang['login_greeting_evening'] = '<strong>Guten Abend</strong>, willkommen zurück!';
$_lang['login_greeting_night'] = '<strong>Gute Nacht</strong>, willkommen zurück!';
$_lang['login_note'] = 'Bitte loggen Sie sich ein, um auf den Manager zuzugreifen.';
$_lang['login_note_passwordless'] = 'Bitte geben Sie Ihre E-Mail-Adresse ein, um einen Einmal-Login-Link zu erhalten.';
$_lang['login_magiclink_email_button'] = 'Sende mir einen Einmal-Login-Link';
$_lang['login_magiclink_email_placeholder'] = 'E-Mail Ihres Benutzerkontos';
$_lang['login_email'] = 'E-Mail';
$_lang['login_help_button_text'] = 'Hilfe';
$_lang['login_help_title'] = 'Holen Sie sich Hilfe zu MODX';
$_lang['login_help_text'] = '<p>Benötigen Sie professionelle MODX-Unterstützung? Wir haben ein Verzeichnis von MODX-Profis auf der ganzen Welt zusammengestellt, die Ihnen gerne helfen. <a href="https://modx.com/professionals/" target="_blank" rel="noopener" title="Finden Sie MODX-Profis auf der MODX-Website">Schnelle Hilfe erhalten auf modx.com</a>.</p>';
$_lang['login_return_site'] = 'Zurück zur Website';

