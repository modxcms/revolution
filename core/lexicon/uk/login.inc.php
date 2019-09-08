<?php
/**
 * Login English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['login_language'] = 'Мова';
$_lang['login_activation_key_err'] = 'Ключ активації не збігається! Не вірний код активації! Будь ласка, перевірте адрес електронної пошти з кодом активації і переконайтеся, що ви вказали правильну URL.';
$_lang['login_blocked_admin'] = 'Ваш доступ до системи заблоковано! Ви були заблоковані адміністратором.';
$_lang['login_blocked_error'] = 'Ви тимчасово заблоковані і не можете увійти в систему. Спробуйте зробити це пізніше.';
$_lang['login_blocked_ip'] = 'З данної IP адреси заборонено вхід до системи.';
$_lang['login_blocked_time'] = 'На данний час ви не можете зайти в систему. Спробуйти зробити це пізніше.';
$_lang['login_blocked_too_many_attempts'] = 'Вас було заблоковано системою через надмірну кількість невдалих спроб входу.';
$_lang['login_button'] = 'Увійти';
$_lang['login_cannot_locate_account'] = 'Неправильне ім\'я користувача або пароль. Будь ласка, перевірте ім\'я користувача, введіть пароль ще раз. Спробуйте ще раз увійти знову.';
$_lang['login_copyright'] = '&copy; 2005-[[+current_year]] by <a href="http://modx.com/about/" target="_blank">MODX, LLC</a>. MODX Revolution&trade; розповсюджується за ліцензією GPLv2 або більш пізньою версією даної ліцензії.';
$_lang['login_email_label'] = 'Електронна пошта облікового запису:';
$_lang['login_err_unknown'] = 'Виникла невідома помилка при спробі авторизуватися.';
$_lang['login_forget_your_login'] = 'Забули свій пароль?';
$_lang['login_forget_your_login_note'] = 'Щоб встановити новий пароль, введіть своє ім\'я користувача або адресу електронної пошти нижче. Якщо у вас є акаунт або обліковий запис, на Вашу електронну адресу електронної пошти буде надіслане посилання для підтвердження.';
$_lang['login_new_password'] = 'Новий пароль';
$_lang['login_new_password_note'] = 'Будь ласка, підтвердіть новий пароль, ввівши його двічі.<br><br>&bull;&Nbsp;Мінімальна довжина пароля становить<strong>[[+length]]</strong>символів.';
$_lang['login_confirm_password'] = 'Підтвердити пароль';
$_lang['login_back_to_login'] = 'Повернутися на сторінку входу';
$_lang['login_hostname_error'] = 'Ваше ім\'я хоста не вказує на Вашу IP-адресу.';
$_lang['login_message'] = 'Будь-ласка введіть Ваші дані для входу до Менеджера. Ім\'я користувача та пароль чутливі до регістру, тож, будь ласка, вводьте їх уважно!';
$_lang['login_password'] = 'Пароль';
$_lang['login_password_reset_act_sent'] = 'Якщо користувач або електронна пошта існує, ви незабаром отримаєте електронного листа.';
$_lang['login_remember'] = 'Тримайте мене увійти в систему для [[+lifetime]]';
$_lang['login_send_activation_email'] = 'Відправити листа для активації';
$_lang['login_title'] = 'Увійти';
$_lang['login_user_err_nf_email'] = 'Якщо користувач або електронна пошта існує, ви незабаром отримаєте електронного листа.';
$_lang['login_username'] = 'Ім\'я користувача';
$_lang['login_username_or_email'] = 'Ім’я користувача або Email';
$_lang['login_username_password_incorrect'] = 'Ім’я користувача або пароль введені невірно. Перевірте, будь-ласка введені данні та спробуйте увійти знову.';
$_lang['login_user_inactive'] = 'Ваш обліковий запис було деактивовано. Будь ласка, зв\'яжіться з системним адміністратором, щоб активувати обліковий запис.';
$_lang['login_email_subject'] = 'Ваші дані для входу';
$_lang['login_magiclink_subject'] = 'Your one-time login link';
$_lang['login_magiclink_err'] = 'Your login link is not valid. Please request a new one.';
$_lang['login_magiclink_email'] = '<h2>One-time Login Link</h2><p>Here is your link to get logged in to the MODX manager. This link is valid for the next [[+expiration]].</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]?magiclink=[[+hash]]" class="btn">Log me in</a></p><p class="small">If you did not send this request, please ignore this email.</p>';
$_lang['login_magiclink_default_msg'] = 'If your email <i>[[+email]]</i> is registered with an account, you’ll receive an email shortly.';
$_lang['login_magiclink_error_msg'] = 'The system was not able to send a login link via email. Please contact the site administrator if this error is permanent.';
$_lang['login_forgot_email'] = '<h2>Забули пароль?</h2><p>Ми отримали запит на зміну пароля MODX Revolution. Ви можете скинути пароль, натиснувши кнопку нижче і слідуючи інструкціям на екрані.</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]?modhash=[[+hash]]" class="btn"> Скинути мій пароль</a></p><p class="small">Якщо ви не відправили цей запит, ігноруйте цей лист.</p>';
$_lang['login_signup_email'] = '<p>Привіт, [[+username]]!</p><p> На сайті <strong>[[++ site_name]]</strong>був зареєстрований ваш обліковий запис. Якщо ви не знаєте свій пароль, [[++allow_manager_login_forgot_password:is=`1`:then=` скиньте його, використовуючи посилання на забутий пароль на екрані входу в систему`:else=`запитайте вашого адміністратора сайта`]].</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]" class="btn">Увійдіть у систему [[++site_name]]</a></р>';
$_lang['login_greeting_morning'] = '<strong>Доброго ранку</strong>, ласкаво просимо!';
$_lang['login_greeting_afternoon'] = '<strong>Добрий день</strong>, ласкаво просимо!';
$_lang['login_greeting_evening'] = '<strong>Добрий вечір</strong>, ласкаво просимо!';
$_lang['login_greeting_night'] = '<strong>Доброї ночі</strong>, ласкаво просимо!';
$_lang['login_note'] = 'Будь ласка, увійдіть, щоб отримати доступ до менеджера.';
$_lang['login_note_passwordless'] = 'Please enter your email address to receive a one-time login link.';
$_lang['login_magiclink_email_button'] = 'Send me a one-time login link';
$_lang['login_magiclink_email_placeholder'] = 'Your user account\'s email here';
$_lang['login_email'] = 'Email';
$_lang['login_help_button_text'] = 'Допомога';
$_lang['login_help_title'] = 'Отримати довідку з MODX';
$_lang['login_help_text'] = '<p><a href="https://modx.com/professionals/" target="_blank" rel="noopener" title="Find MODX Professionals on the MODX website">Чи потрібна вам професійна підтримка MODX? Ми куратори каталога професіоналів MODX по всьому світу, які з радістю допоможуть.<a href="https://modx.com/professionals/" target="_blank" rel="noopener" title="Find MODX Professionals on the MODX website">швидко отримати допомогу на modx.com</a>.</p>';
$_lang['login_return_site'] = 'Повернутися на сайт';

