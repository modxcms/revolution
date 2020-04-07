<?php
/**
 * Login English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['login_language'] = 'Nyelv';
$_lang['login_activation_key_err'] = 'Az aktivációs kulcs nem egyezik. Kérjük, ellenőrizze az aktivációs e-mailt, és győződjön meg arról, hogy a helyes URL-t nyitotta meg.';
$_lang['login_blocked_admin'] = 'A felhasználói fiókját egy rendszergazda letiltotta.';
$_lang['login_blocked_error'] = 'A bejelentkezése átmenetileg korlátozva van. Kérjük próbálja meg később.';
$_lang['login_blocked_ip'] = 'Önnek nem engedélyezett a belépés a jelenlegi IP címéről.';
$_lang['login_blocked_time'] = 'Önnek nem engedélyezett a belépés ebben az időpontban. Próbálja meg máskor.';
$_lang['login_blocked_too_many_attempts'] = 'Az Ön felhasználói fiókját letiltottuk a túl sok sikertelen bejelentkezési kísérlet miatt.';
$_lang['login_button'] = 'Belépés';
$_lang['login_cannot_locate_account'] = 'A felhasználónév vagy a jelszó nem megfelelő. Ellenőrizze a felhasználónevet, írja be újra a jelszót, majd próbálja újra.';
$_lang['login_copyright'] = '&copy; 2005-[[+current_year]] <a href="https://modx.com/about/company" target="_blank">MODX, LLC</a> által. MODX Revolution&trade; a GPLv2 vagy újabb által engedélyezve.';
$_lang['login_email_label'] = 'A fiókhoz tartozó e-mail cím:';
$_lang['login_err_unknown'] = 'Ismeretlen hiba történt a bejelentkezés során.';
$_lang['login_forget_your_login'] = 'Elfelejtette a jelszavát?';
$_lang['login_forget_your_login_note'] = 'Új jelszó beállításához írja be a felhasználónevét vagy email címét alább. Ha van felhasználói fiókja, egy ellenőrző hivatkozást kap az email címére.';
$_lang['login_new_password'] = 'Új jelszó';
$_lang['login_new_password_note'] = 'Kérjük, erősítse meg az új jelszót kétszeri megadásával.<br><br>&bull;&nbsp;A jelszó legalább <strong>[[+length]]</strong> karakter hosszú kell legyen.';
$_lang['login_confirm_password'] = 'Jelszó megerősítése';
$_lang['login_back_to_login'] = 'Vissza a belépéshez';
$_lang['login_hostname_error'] = 'A domainnév és az IP cím nem egyezik.';
$_lang['login_message'] = 'Kérjük adja meg a bejelentkezési hitelesítő adatait. Ügyeljen rá, hogy a felhasználónév és a jelszó is kis-nagybetű érzékeny!';
$_lang['login_password'] = 'Jelszó';
$_lang['login_password_reset_act_sent'] = 'Ha a felhasználó vagy az email cím létezik, hamarosan elektronikus üzenetet fog kapni.';
$_lang['login_remember'] = 'Maradjak bejelentkezve [[+lifetime]] hosszan';
$_lang['login_send_activation_email'] = 'Aktivációs e-mail küldése';
$_lang['login_title'] = 'Belépés';
$_lang['login_user_err_nf_email'] = 'Ha a felhasználó vagy az email cím létezik, hamarosan elektronikus üzenetet fog kapni.';
$_lang['login_username'] = 'Felhasználónév';
$_lang['login_username_or_email'] = 'Felhasználónév, vagy e-mail cím';
$_lang['login_username_password_incorrect'] = 'Hibás a megadott felhasználónév, vagy a jelszó. Ellenőrizze az adatok helyességét, majd próbálja újra!';
$_lang['login_user_inactive'] = 'Az Ön felhasználói fiókját a rendszeradminisztrátor letiltotta.';
$_lang['login_email_subject'] = 'Bejelentkezési adatai';
$_lang['login_magiclink_subject'] = 'Az egyszeri bejelentkezés hivatkozása';
$_lang['login_magiclink_err'] = 'Érvénytelen bejelentkezési hivatkozás. Kérjük, igényeljen újat.';
$_lang['login_magiclink_email'] = '<h2>Egyszeri bejelentkezés hivatkozása</h2><p>Ezzel a hivatkozással léphet be a MODX kezelőbe. Érvényesség időtartama a következő [[+expiration]].</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]?magiclink=[[+hash]]" class="btn">Belépek</a></p><p class="small">Ha nem Ön kérte, hagyja figyelmen kívül ezt az üzenetet.</p>';
$_lang['login_magiclink_default_msg'] = 'Ha az email címe <i>[[+email]]</i> egy felhasználói fiókhoz van kapcsolva, hamarosan kap egy emailt.';
$_lang['login_magiclink_error_msg'] = 'A rendszer nem tudta emailben elküldeni a bejelentkezési hivatkozás. Ha ez a hiba nem szűnik meg, keresse meg az oldal rendszergazdáját.';
$_lang['login_forgot_email'] = '<h2>Elfelejtette jelszavát?</h2><p>Kérést kaptunk a MODX Revolution jelszava megváltoztatására. Az alábbi gombra kattintva és a megjelenő útmutatásokat követve állíthatja alapállapotba.</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]?modhash=[[+hash]]" class="btn">Jelszavam visszaállítása</a></p><p class="small">Ha nem kérte a visszaállítást, hagyja figyelmen kívül ezt az üzenetet.</p>';
$_lang['login_signup_email'] = '<p>Üdv, [[+username]]!</p><p>Felhasználói fiók készült a számára a <strong>[[++site_name]]</strong> weboldalon. Ha nem tudja a jelszavát, [[++allow_manager_login_forgot_password:is=`1`:then=`kérjen újat a bejelentkező oldalon az elfelejtett jelszóra kattintva`:else=`keresse meg az oldal rendszergazdáját`]].</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]" class="btn">Belépés a(z) [[++site_name]] oldalra</a></p>';
$_lang['login_greeting_morning'] = '<strong>Jó reggelt</strong>, üdv újra nálunk!';
$_lang['login_greeting_afternoon'] = '<strong>Jó napot</strong>, üdv újra nálunk!';
$_lang['login_greeting_evening'] = '<strong>Jó estét</strong>, üdv újra nálunk!';
$_lang['login_greeting_night'] = '<strong>Jó estét</strong>, üdv újra nálunk!';
$_lang['login_note'] = 'Kérjük, jelentkezzen be a kezelő eléréséhez.';
$_lang['login_note_passwordless'] = 'Kérjük adja meg az email címét, és elküldjük Önnek az egyszeri belépési hivatkozást.';
$_lang['login_magiclink_email_button'] = 'Küldjenek egyszeri bejelentkezési hivatkozást';
$_lang['login_magiclink_email_placeholder'] = 'A felhasználói fiókjának email címe';
$_lang['login_email'] = 'E-mail';
$_lang['login_help_button_text'] = 'Súgó';
$_lang['login_help_title'] = 'Kérjen segítséget a MODX-hez';
$_lang['login_help_text'] = '<p>Szakértői MODX támogatást kér? Összeállítottunk egy címtárat a világ MODX szakértőiből, akik szívesen segítenek. <a href="https://modx.com/professionals/" target="_blank" rel="noopener" title="Keressen MODX szakértőket a MODX weboldalán">Gyors segítség a modx.com oldalról</a>.</p>';
$_lang['login_return_site'] = 'Vissza a weboldalra';

