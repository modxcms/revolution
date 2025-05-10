<?php
/**
 * Login English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['login_language'] = 'Kieli';
$_lang['login_activation_key_err'] = 'Aktivointiavain ei täsmää! Tarkista aktivointisähköpostisi ja varmista, että seurasit oikeaa URL-osoitetta.';
$_lang['login_blocked_admin'] = 'Järjestelmänvalvoja on estänyt kirjautumisesi.';
$_lang['login_blocked_error'] = 'Tilapäisesti sisäänkirjautuminen ei onnistu. Yritä myöhemmin uudelleen.';
$_lang['login_blocked_ip'] = 'Kirjautumista nykyisestä IP-osoiteestasi ei sallita.';
$_lang['login_blocked_time'] = 'Et voi kirjautua sisään tähän aikaan. Yritä myöhemmin uudelleen.';
$_lang['login_blocked_too_many_attempts'] = 'Sinut on estetty, koska liian monta epäonnistunutta kirjautumisyritystä.';
$_lang['login_button'] = 'Kirjaudu sisään';
$_lang['login_cannot_locate_account'] = 'Antamasi käyttäjätunnus tai salasana on virheellinen. Tarkista käyttäjätunnus, kirjoita salasana uudelleen ja yritä uudelleen.';
$_lang['login_copyright'] = '© 2005-[[+current_year]] <a href="http://modx.com/about/" target="_blank"> MODX</a>, LLC. MODX Revolution ™ on lisensoitu GPLv2 tai uudempaan.';
$_lang['login_email_label'] = 'Tilin sähköpostiosoite:';
$_lang['login_err_unknown'] = 'Tuntematon virhe yritettäessä kirjautua sisään.';
$_lang['login_forget_your_login'] = 'Unohditko salasanasi?';
$_lang['login_forget_your_login_note'] = 'Asettaaksesi uuden salasanan, anna käyttäjätunnuksesi tai sähköpostiosoitteesi alla. Jos sinulla on tili, vahvistuslinkki lähetetään sähköpostiosoitteeseesi.';
$_lang['login_new_password'] = 'Uusi salasana';
$_lang['login_new_password_note'] = 'Vahvista uusi salasanasi kirjoittamalla se kahdesti.<br><br>&bull;&nbsp;Salasanan vähimmäispituus on <strong>[[+length]]</strong> merkkiä.';
$_lang['login_confirm_password'] = 'Vahvista salasana';
$_lang['login_back_to_login'] = 'Takaisin sisäänkirjautumiseen';
$_lang['login_hostname_error'] = 'Verkkotunnuksen nimi ei osoita takaisin sinun IP-osoiteeseen.';
$_lang['login_message'] = 'Anna kirjautumistietosi istunnon käynnistämiseksi. Käyttäjätunnuksessa ja salasanassa ovat isot ja pienet kirjaimet merkityksellisiä, joten kirjoita ne huolellisesti!';
$_lang['login_password'] = 'Salasana';
$_lang['login_password_reset_act_sent'] = 'Jos käyttäjä tai sähköposti on olemassa, saat sähköpostin pian.';
$_lang['login_remember'] = 'Pidä minut kirjautuneena sisään [[+lifetime]]';
$_lang['login_send_activation_email'] = 'Lähetä aktivointiviesti';
$_lang['login_title'] = 'Kirjaudu sisään';
$_lang['login_user_err_nf_email'] = 'Jos käyttäjä tai sähköposti on olemassa, saat sähköpostin pian.';
$_lang['login_username'] = 'Käyttäjätunnus';
$_lang['login_username_or_email'] = 'Käyttäjänimi tai sähköpostiosoite';
$_lang['login_username_password_incorrect'] = 'Käyttäjänimi tai salasana on virheellinen.  Tarkista käyttäjänimi, salasana ja yritä uudelleen.';
$_lang['login_user_inactive'] = 'Tilisi on poistettu käytöstä. Ota yhteyttä järjestelmänvalvojaan, jotta tili voidaan avata.';
$_lang['login_email_subject'] = 'Kirjautumistietosi';
$_lang['login_magiclink_subject'] = 'Kertakirjautumislinkkisi';
$_lang['login_magiclink_err'] = 'Kirjautumislinkki ei ole kelvollinen. Ole hyvä ja pyydä uusi.';
$_lang['login_magiclink_email'] = '<h2>Kertakirjautumislinkki</h2><p>Tässä on sinun linkkisi kirjautuaksesi MODX manageriin. Tämä linkki on voimassa [[+expiration]]:lle.</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]?magiclink=[[+hash]]" class="btn">Kirjaudu sisään</a></p><p class="small">Jos et lähettänyt tätä pyyntöä, jätä tämä viesti huomioimatta.</p>';
$_lang['login_magiclink_default_msg'] = 'Jos sähköpostiosoitteesi <i>[[+email]]</i> on rekisteröity tilille, saat sähköpostin pian.';
$_lang['login_magiclink_error_msg'] = 'Järjestelmä ei pystynyt lähettämään kirjautumislinkkiä sähköpostitse. Ota yhteyttä sivuston ylläpitoon, jos tämä virhe on pysyvä.';
$_lang['login_forgot_email'] = '<h2>Unohditko salasanasi?</h2><p>Saimme pyynnön MODX Revolution -salasanan vaihtamiseksi. Voit nollata salasanasi klikkaamalla alla olevaa painiketta ja seuraamalla näytöllä olevia ohjeita.</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]?modhash=[[+hash]]" class="btn">Nollaa salasanani</a></p><p class="small">Jos et lähettänyt tätä pyyntöä, jätä tämä sähköposti huomiotta.</p>';
$_lang['login_signup_email'] = '<p>Hei, [[+username]]!</p><p>Sinulle rekisteröitiin tili <strong>[++site_name]]</strong> verkkosivuilla. Jos et tiedä salasanaasi, [[++allow_manager_login_forgot_password:is=`1`:then=`reset it using the forgot password link on login screen`:else=`ask your Site Administrator`]].</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]" class="btn">Kirjaudu sisään [[++site_name]]</a></p>';
$_lang['login_greeting_morning'] = '<strong>Hyvää huomenta</strong>, tervetuloa takaisin!';
$_lang['login_greeting_afternoon'] = '<strong>Hyvää iltapäivää</strong>, tervetuloa takaisin!';
$_lang['login_greeting_evening'] = '<strong>Hyvää iltaa</strong>, tervetuloa takaisin!';
$_lang['login_greeting_night'] = '<strong>Nukkua pitäisi mutta</strong>, tervetuloa takaisin!';
$_lang['login_note'] = 'Ole hyvä ja kirjaudu sisään päästäksesi hallintaan.';
$_lang['login_note_passwordless'] = 'Ole hyvä ja kirjoita sähköpostiosoitteesi saadaksesi kertakirjautumislinkin.';
$_lang['login_magiclink_email_button'] = 'Lähetä kertakirjautumislinkki';
$_lang['login_magiclink_email_placeholder'] = 'Käyttäjätilisi sähköposti tähän';
$_lang['login_email'] = 'Sähköposti';
$_lang['login_help_button_text'] = 'Ohje';
$_lang['login_help_title'] = 'Hanki MODX tukea';
$_lang['login_help_text'] = '<p>Tarvitsetko ammattimaista MODX tukea? Olemme keränneet MODX ammattilaisia ympäri maailmaa, jotka auttavat mielellään. <a href="https://modx.com/professionals/" target="_blank" rel="noopener" title="Find MODX Professionals on the MODX website">Saat apua nopeasti modx sivuilta</a>.</p>';
$_lang['login_return_site'] = 'Palaa sivustolle';

