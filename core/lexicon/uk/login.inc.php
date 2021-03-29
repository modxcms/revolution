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
$_lang['login_blocked_error'] = 'Ви тимчасово заблоковані. Будь ласка, зв\'яжіться з адміністратором сайту для розблокування';
$_lang['login_blocked_ip'] = 'Аутентифікація з цього IP-адреси не дозволена.';
$_lang['login_blocked_time'] = 'Вам тимчасово заборонено аутентифікація. Будь ласка, спробуйте пізніше. ';
$_lang['login_blocked_too_many_attempts'] = 'Ви були заблоковані через велику кількість невдалих спроб аутентифікації. ';
$_lang['login_button'] = 'Увійти';
$_lang['login_cannot_locate_account'] = 'Неправильне ім\'я користувача або пароль. Будь ласка, перевірте ім\'я користувача, введіть пароль ще раз. Спробуйте ще раз увійти знову.';
$_lang['login_copyright'] = '&copy; 2005-[[+current_year]] від <a href="https://modx.com/about/company" target="_blank">MODX, LLC</a>. MODX Revolution&trade; ліцензований під GPLv2 або більш пізньою версією даної ліцензії.';
$_lang['login_email_label'] = 'Електронна пошта облікового запису:';
$_lang['login_err_unknown'] = 'При спробі аутентифікації сталася невідома помилка. ';
$_lang['login_forget_your_login'] = 'Забули свій пароль?';
$_lang['login_forget_your_login_note'] = 'Щоб встановити новий пароль, введіть своє ім\'я користувача або адресу електронної пошти нижче. Якщо у вас є акаунт або обліковий запис, на Вашу електронну адресу електронної пошти буде надіслане посилання для підтвердження.';
$_lang['login_new_password'] = 'Новий пароль';
$_lang['login_new_password_note'] = 'Будь ласка, підтвердіть новий пароль, ввівши його двічі.<br><br>&bull;&Nbsp;Мінімальна довжина пароля становить<strong>[[+length]]</strong>символів.';
$_lang['login_confirm_password'] = 'Підтвердити пароль';
$_lang['login_back_to_login'] = 'Повернутися на сторінку входу';
$_lang['login_hostname_error'] = 'Ваше ім\'я хоста не вказує на Вашу IP-адресу.';
$_lang['login_message'] = 'Будь ласка, введіть ваші облікові дані для аутентифікації. Зверніть увагу, ваші ім\'я користувача та пароль чутливі до регістру! ';
$_lang['login_password'] = 'Пароль';
$_lang['login_password_reset_act_sent'] = 'Якщо користувач або електронна пошта існує, ви незабаром отримаєте електронного листа.';
$_lang['login_remember'] = 'Тримайте мене увійти в систему для [[+lifetime]]';
$_lang['login_send_activation_email'] = 'Відправити листа для активації';
$_lang['login_title'] = 'Увійти';
$_lang['login_user_err_nf_email'] = 'Якщо користувач або електронна пошта існує, ви незабаром отримаєте електронного листа.';
$_lang['login_username'] = 'Ім\'я користувача';
$_lang['login_username_or_email'] = 'Ім’я користувача або Email';
$_lang['login_username_password_incorrect'] = 'Ім’я користувача або пароль введені невірно. Перевірте, будь-ласка введені данні та спробуйте увійти знову.';
$_lang['login_user_inactive'] = 'Ваш обліковий запис неактивна. Будь ласка, зв\'яжіться з адміністратором сайту для її активації. ';
$_lang['login_email_subject'] = 'Ваші дані для входу';
$_lang['login_magiclink_subject'] = 'Ваш одноразовий лінк для входу ';
$_lang['login_magiclink_err'] = 'Ваш лIнк для входу недійсний. Будь ласка, запитайте новий.';
$_lang['login_magiclink_email'] = '<h2>Одноразове посилання для входу</h2><p>Ось Ваше посилання для входу в систему MODX Менеджера. Це посилання дійсне для наступного [[+expiration]].</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]?magiclink=[[+hash]]" class="btn">Вхiд</a></p><p class="small">Якщо ви не відправляли цей запит, проігноруйте це повiдомлення.</p>';
$_lang['login_magiclink_default_msg'] = 'Якщо ваша електронна пошта <i>[[+email]]</i> зареєстрована з аккаунтом, ви незабаром отримаєте електронне повiдомлення.';
$_lang['login_magiclink_error_msg'] = 'Системі не вдалося відправити посилання для входу на електронну пошту. Будь ласка, зверніться до адміністратора сайту, якщо ця помилка є постійною.';
$_lang['login_forgot_email'] = '<h2>Забули пароль?</h2><p>Ми отримали запит на зміну пароля MODX Revolution. Ви можете скинути пароль, натиснувши кнопку нижче і слідуючи інструкціям на екрані.</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]?modhash=[[+hash]]" class="btn"> Скинути мій пароль</a></p><p class="small">Якщо ви не відправили цей запит, ігноруйте цей лист.</p>';
$_lang['login_signup_email'] = '<p>Привіт, [[+username]]!</p><p> На сайті <strong>[[++ site_name]]</strong>був зареєстрований ваш обліковий запис. Якщо ви не знаєте свій пароль, [[++allow_manager_login_forgot_password:is=`1`:then=` скиньте його, використовуючи посилання на забутий пароль на екрані входу в систему`:else=`запитайте вашого адміністратора сайта`]].</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]" class="btn">Увійдіть у систему [[++site_name]]</a></р>';
$_lang['login_greeting_morning'] = '<strong>Доброго ранку</strong>, ласкаво просимо!';
$_lang['login_greeting_afternoon'] = '<strong>Добрий день</strong>, ласкаво просимо!';
$_lang['login_greeting_evening'] = '<strong>Добрий вечір</strong>, ласкаво просимо!';
$_lang['login_greeting_night'] = '<strong>Доброї ночі</strong>, ласкаво просимо!';
$_lang['login_note'] = 'Будь ласка, увійдіть, щоб отримати доступ до менеджера.';
$_lang['login_note_passwordless'] = 'Будь ласка, введіть вашу адресу електронної пошти, щоб отримати одноразове посилання для входу.';
$_lang['login_magiclink_email_button'] = 'Надсилайте мені одноразовий лiнк для входу';
$_lang['login_magiclink_email_placeholder'] = 'Електронна пошта вашого профілю ';
$_lang['login_email'] = 'Email';
$_lang['login_help_button_text'] = 'Допомога';
$_lang['login_help_title'] = 'Отримати довідку з MODX';
$_lang['login_help_text'] = '<p><a href="https://modx.com/professionals/" target="_blank" rel="noopener" title="Find MODX Professionals on the MODX website">Чи потрібна вам професійна підтримка MODX? Ми куратори каталога професіоналів MODX по всьому світу, які з радістю допоможуть.<a href="https://modx.com/professionals/" target="_blank" rel="noopener" title="Find MODX Professionals on the MODX website">швидко отримати допомогу на modx.com</a>.</p>';
$_lang['login_return_site'] = 'Повернутися на сайт';

