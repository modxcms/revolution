<?php
/**
 * Login English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['login_language'] = 'Taal';
$_lang['login_activation_key_err'] = 'De activatie-sleutel komt niet overeen! Controleer de activatie e-mail en weet zeker dat je de juiste URL hebt geladen.';
$_lang['login_blocked_admin'] = 'Je bent geblokkeerd voor de manager door een beheerder.';
$_lang['login_blocked_error'] = 'Je bent tijdelijk geblokkeerd en kunt niet inloggen. Probeer het later nog eens.';
$_lang['login_blocked_ip'] = 'Het is niet toegestaan in te loggen vanaf dit IP adres.';
$_lang['login_blocked_time'] = 'Het is niet toegestaan in te loggen op dit moment. Probeer het later nog eens.';
$_lang['login_blocked_too_many_attempts'] = 'Je bent geblokkeerd door te veel mislukte login pogingen.';
$_lang['login_button'] = 'Aanmelden';
$_lang['login_cannot_locate_account'] = 'De gebruikersnaam of het wachtwoord zijn niet correct. Controleer de gebruikersnaam, type het wachtwoord opnieuw en probeer het nog eens.';
$_lang['login_copyright'] = '&copy; 2005-[[+current_year]] by <a href="https://modx.com/about/company" target="_blank">MODX, LLC</a>. MODX Revolution&trade; is licensed under the GPLv2 or later.';
$_lang['login_email_label'] = 'Account e-mail:';
$_lang['login_err_unknown'] = 'Er is een onbekende fout opgetreden tijdens het inloggen.';
$_lang['login_forget_your_login'] = 'Wachtwoord vergeten?';
$_lang['login_forget_your_login_note'] = 'Om een nieuw wachtwoord aan te maken, vul uw gebruikersnaam of e-mailadres hieronder in. Als u een account heeft, zal er activatie link worden verstuurd naar uw e-mailadres.';
$_lang['login_new_password'] = 'Nieuw wachtwoord';
$_lang['login_new_password_note'] = 'Bevestig uw wachtwoord door hem tweemaal in te vullen.<br><br>&bull;&nbsp;<strong>[[+length]]</strong> karakters is het minimale wachtwoord lengte.';
$_lang['login_confirm_password'] = 'Bevestig wachtwoord';
$_lang['login_back_to_login'] = 'Terug naar Inloggen';
$_lang['login_hostname_error'] = 'Je hostnaam wijst niet terug naar jouw IP adres.';
$_lang['login_message'] = 'Vul je inloggegevens in om jouw Manager sessie te starten. Je gebruikersnaam en wachtwoord zijn hoofdlettergevoelig, dus vul het met zorg in!';
$_lang['login_password'] = 'Wachtwoord';
$_lang['login_password_reset_act_sent'] = 'Als de gebruiker of het e-mailadres bestaat, ontvangt u spoedig een e-mail.';
$_lang['login_remember'] = 'Houd mij ingelogd voor [[+lifetime]]';
$_lang['login_send_activation_email'] = 'Stuur activatie e-mail';
$_lang['login_title'] = 'Aanmelden';
$_lang['login_user_err_nf_email'] = 'Als de gebruiker of het e-mailadres bestaat, ontvangt u spoedig een e-mail.';
$_lang['login_username'] = 'Gebruikersnaam';
$_lang['login_username_or_email'] = 'Gebruiker of Email';
$_lang['login_username_password_incorrect'] = 'De gebruikersnaam of het wachtwoord zijn niet correct. Controleer de gebruikersnaam, type het wachtwoord en probeer het nog eens.';
$_lang['login_user_inactive'] = 'Je gebruikersaccount is uitgeschakeld. Neem contact op met je systeembeheerder om je account in te schakelen.';
$_lang['login_email_subject'] = 'Je inloggegevens';
$_lang['login_magiclink_subject'] = 'Your one-time login link';
$_lang['login_magiclink_err'] = 'Your login link is not valid. Please request a new one.';
$_lang['login_magiclink_email'] = '<h2>One-time Login Link</h2><p>Here is your link to get logged in to the MODX manager. This link is valid for the next [[+expiration]].</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]?magiclink=[[+hash]]" class="btn">Log me in</a></p><p class="small">If you did not send this request, please ignore this email.</p>';
$_lang['login_magiclink_default_msg'] = 'If your email <i>[[+email]]</i> is registered with an account, you’ll receive an email shortly.';
$_lang['login_magiclink_error_msg'] = 'The system was not able to send a login link via email. Please contact the site administrator if this error is permanent.';
$_lang['login_forgot_email'] = '<h2>Wachtwoord vergeten?</h2><p> We hebben een verzoek ontvangen om uw MODX Revolution wachtwoord aan te passen. U kunt uw wachtwoord vernieuwen door op de onderstaande knop te klikken en de instructies te volgen.</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]?modhash=[[+hash]]" class="btn">Wachtwoord vernieuwen</a></p><p class="small">Als u dit verzoek niet heeft ingediend, dan kunt u deze e-mail negeren.</p>';
$_lang['login_signup_email'] = '<p>Hallo, [[+username]]!</p><p>Er is een account geregistreerd voor de <strong>[[++site_name]]</strong> website. Mocht uw wachtwoord niet bekend zijn, kunt u [[++allow_manager_login_forgot_password:is=`1`:then=` het vernieuwen via het inlog scherm`:else=`vragen aan de site beheerder`]].</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]" class="btn">Log in op [[++site_name]]</a></p>';
$_lang['login_greeting_morning'] = '<strong>Goedemorgen</strong>, welkom terug!';
$_lang['login_greeting_afternoon'] = '<strong>Goedemiddag</strong>, welkom terug!';
$_lang['login_greeting_evening'] = '<strong>Goedenavond</strong>, welkom terug!';
$_lang['login_greeting_night'] = '<strong>Goedenacht</strong>, welkom terug!';
$_lang['login_note'] = 'Meld je aan om toegang te krijgen tot de manager.';
$_lang['login_note_passwordless'] = 'Please enter your email address to receive a one-time login link.';
$_lang['login_magiclink_email_button'] = 'Send me a one-time login link';
$_lang['login_magiclink_email_placeholder'] = 'Your user account\'s email here';
$_lang['login_email'] = 'Email';
$_lang['login_help_button_text'] = 'Help';
$_lang['login_help_title'] = 'Vraag om hulp voor MODX';
$_lang['login_help_text'] = '<p>Heb je professionele MODX ondersteuning nodig? We hebben een lijst met MODX professionals over de hele wereld samengesteld die je graag helpen. <a href="https://modx.com/professionals/" target="_blank" rel="noopener" title="Vind MODX professionals op de website van MODX">Krijg snel hulp op modx.com</a>.</p>';
$_lang['login_return_site'] = 'Terug naar de website';

