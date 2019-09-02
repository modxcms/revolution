<?php
/**
 * Login English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['login_language'] = 'Språk';
$_lang['login_activation_key_err'] = 'Aktiveringsnyckeln stämmer inte! Kontrollera ditt aktiveringsmeddelande och försäkra dig om att du klickade på rätt länk.';
$_lang['login_blocked_admin'] = 'Du har blivit blockerad från hanteraren av en administratör.';
$_lang['login_blocked_error'] = 'Du är tillfälligt blockerad och kan inte logga in. Försök senare.';
$_lang['login_blocked_ip'] = 'Du får inte logga in från din nuvarande IP-adress.';
$_lang['login_blocked_time'] = 'Du får inte logga in just nu. Försök senare.';
$_lang['login_blocked_too_many_attempts'] = 'Du har blivit blockerad på grund av för många felaktiga inloggningsförsök.';
$_lang['login_button'] = 'Inloggning';
$_lang['login_cannot_locate_account'] = 'Det användarnamn eller lösenord du angav är inte korrekt. Kontrollera användarnamnet, skriv om lösenordet och gör ett nytt försök.';
$_lang['login_copyright'] = '&copy; 2005-[[+current_year]] av <a href="http://modx.com/about/" target="_blank">MODX, LLC</a>. MODX Revolution&trade; är licensierad under GPLv2 eller senare.';
$_lang['login_email_label'] = 'Kontots e-postadress:';
$_lang['login_err_unknown'] = 'Ett okänt fel inträffade när du försökte logga in.';
$_lang['login_forget_your_login'] = 'Glömt lösenordet?';
$_lang['login_forget_your_login_note'] = 'Om du vill ange ett nytt lösenord, ange ditt användarnamn eller e-postadress nedan. Om du har ett konto kommer en verifieringslänk att skickas till din e-postadress.';
$_lang['login_new_password'] = 'Nytt lösenord';
$_lang['login_new_password_note'] = 'Bekräfta ditt nya lösenord genom att ange det två gånger.<br><br>&bull;&nbsp;Lösenordet måste vara minst <strong>[[+length]]</strong> tecken långt.';
$_lang['login_confirm_password'] = 'Bekräfta lösenord';
$_lang['login_back_to_login'] = 'Tillbaka till inloggningen';
$_lang['login_hostname_error'] = 'Ditt värddatornamn pekar inte tillbaka till din IP-adress.';
$_lang['login_message'] = 'Fyll i dina inloggningsuppgifter för att starta din session. Det är skillnad mellan stora och små bokstäver i ditt användarnamn och lösenord.';
$_lang['login_password'] = 'Lösenord';
$_lang['login_password_reset_act_sent'] = 'Om användaren eller e-postadressen existerar kommer du inom kort att få ett e-postmeddelande.';
$_lang['login_remember'] = 'Håll mig inloggad i [[+lifetime]]';
$_lang['login_send_activation_email'] = 'Skicka aktiveringsmeddelande';
$_lang['login_title'] = 'Inloggning';
$_lang['login_user_err_nf_email'] = 'Om användaren eller e-postadressen existerar kommer du inom kort att få ett e-postmeddelande.';
$_lang['login_username'] = 'Användarnamn';
$_lang['login_username_or_email'] = 'Användarnamn eller e-post';
$_lang['login_username_password_incorrect'] = 'Det användarnamn eller lösenord du angav är inte korrekt. Kontrollera användarnamnet, skriv om lösenordet och gör ett nytt försök.';
$_lang['login_user_inactive'] = 'Ditt användarkonto har inaktiverats. Kontakt din systemadministratör för att aktivera kontot.';
$_lang['login_email_subject'] = 'Dina inloggningsuppgifter';
$_lang['login_magiclink_subject'] = 'Your one-time login link';
$_lang['login_magiclink_err'] = 'Your login link is not valid. Please request a new one.';
$_lang['login_magiclink_email'] = '<h2>One-time Login Link</h2><p>Here is your link to get logged in to the MODX manager. This link is valid for the next [[+expiration]].</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]?magiclink=[[+hash]]" class="btn">Log me in</a></p><p class="small">If you did not send this request, please ignore this email.</p>';
$_lang['login_magiclink_default_msg'] = 'If your email <i>[[+email]]</i> is registered with an account, you’ll receive an email shortly.';
$_lang['login_magiclink_error_msg'] = 'The system was not able to send a login link via email. Please contact the site administrator if this error is permanent.';
$_lang['login_forgot_email'] = '<h2>Har du glömt ditt lösenord?</h2><p>Vi har mottagit en begäran att ändra ditt lösenord till MODX Revolution. Du kan återställa ditt lösenord genom att klicka på knappen nedan och sedan följa instruktionerna på skärmen.</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]?modhash=[[+hash]]" class="btn">Återställ mitt lösenord</a></p><p class="small">Om du inte skickat denna begäran så kan du bortse från detta mail.</p>';
$_lang['login_signup_email'] = '<p>Hej [[+username]]!</p><p>Ett konto har registrerats för dig på webbplatsen <strong>[[++site_name]]</strong>. Om du inte kan ditt lösenord [[++allow_manager_login_forgot_password:is=`1`:then=`så kan du återställa det via länken på inloggningssidan`:else=`så behöver du kontakta webbplatsens administratör`]].</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]" class="btn">Logga in på [[++site_name]]</a></p>';
$_lang['login_greeting_morning'] = '<strong>God morgon</strong> och välkommen tillbaka!';
$_lang['login_greeting_afternoon'] = '<strong>God eftermiddag</strong> och välkommen tillbaka!';
$_lang['login_greeting_evening'] = '<strong>God kväll</strong> och välkommen tillbaka!';
$_lang['login_greeting_night'] = '<strong>God natt</strong> och välkommen tillbaka!';
$_lang['login_note'] = 'Logga in för att komma till hanteraren.';
$_lang['login_note_passwordless'] = 'Please enter your email address to receive a one-time login link.';
$_lang['login_magiclink_email_button'] = 'Send me a one-time login link';
$_lang['login_magiclink_email_placeholder'] = 'Your user account\'s email here';
$_lang['login_email'] = 'Email';
$_lang['login_help_button_text'] = 'Hjälp';
$_lang['login_help_title'] = 'Få hjälp med MODX';
$_lang['login_help_text'] = '<p>Behöver du professionell support för MODX? Vi har valt ut ett antal proffs på MODX över hela världen som gärna hjälper till. <a href="https://modx.com/professionals/" target="_blank" rel="noopener" title="Hitta MODX-proffs på MODX webbplats">Få snabb hjälp på modx.com</a>.</p>';
$_lang['login_return_site'] = 'Tillbaka till webbplatsen';

