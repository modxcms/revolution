<?php
/**
 * Login English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['login_language'] = 'Язык';
$_lang['login_activation_key_err'] = 'Неверный код активации! Пожалуйста, проверьте письмо с кодом активации и убедитесь, что вы перешли по правильной ссылке.';
$_lang['login_blocked_admin'] = 'Ваш доступ к системе управления заблокирован администратором.';
$_lang['login_blocked_error'] = 'Вы временно заблокированы. Пожалуйста, свяжитесь с администратором сайта для отмены блокировки.';
$_lang['login_blocked_ip'] = 'Аутентификация с этого IP-адреса не разрешена.';
$_lang['login_blocked_time'] = 'Вам временно запрещена аутентификация. Пожалуйста, попробуйте позже.';
$_lang['login_blocked_too_many_attempts'] = 'Вы были заблокированы из-за большого количества неудачных попыток аутентификации.';
$_lang['login_button'] = 'Войти';
$_lang['login_cannot_locate_account'] = 'Неправильное имя пользователя или пароль. Проверьте введённые данные и попытайтесь снова.';
$_lang['login_copyright'] = '&copy; 2005-[[+current_year]] <a href="https://modx.com/about/company" target="_blank">MODX, LLC</a>. MODX Revolution&trade; распространяется под лицензией GPLv2 или более поздней версии.';
$_lang['login_email_label'] = 'Электронная почта учетной записи:';
$_lang['login_err_unknown'] = 'При попытке аутентификации произошла неизвестная ошибка.';
$_lang['login_forget_your_login'] = 'Забыли пароль?';
$_lang['login_forget_your_login_note'] = 'Чтобы указать новый пароль, введите имя пользователя или адрес электронной почты. Если такой аккаунт существует, на вашу электронную почту будет отправлена ссылка для подтверждения смены пароля.';
$_lang['login_new_password'] = 'Новый пароль';
$_lang['login_new_password_note'] = 'Пожалуйста, подтвердите новый пароль, введя его дважды.<br><br>&bull;&nbsp;минимальная длина пароля — <strong>[[+length]]</strong> символов.';
$_lang['login_confirm_password'] = 'Подтвердить пароль';
$_lang['login_back_to_login'] = 'Назад к форме входа';
$_lang['login_hostname_error'] = 'Имя вашего хоста не указывает обратно на ваш IP-адрес.';
$_lang['login_message'] = 'Пожалуйста, введите ваши учетные данные для аутентификации. Обратите внимание, ваши имя пользователя и пароль чувствительны к регистру!';
$_lang['login_password'] = 'Пароль';
$_lang['login_password_reset_act_sent'] = 'Если пользователь или адрес электронной почты существуют, вы скоро получите письмо.';
$_lang['login_remember'] = 'Запомнить меня на [[+lifetime]]';
$_lang['login_send_activation_email'] = 'Выслать письмо для активации';
$_lang['login_title'] = 'Войти';
$_lang['login_user_err_nf_email'] = 'Если пользователь или адрес электронной почты существуют, вы скоро получите письмо.';
$_lang['login_username'] = 'Имя пользователя';
$_lang['login_username_or_email'] = 'Имя пользователя или электронная почта';
$_lang['login_username_password_incorrect'] = 'Неправильное имя пользователя или пароль. Пожалуйста, проверьте введённые данные и попытайтесь снова.';
$_lang['login_user_inactive'] = 'Ваша учётная запись неактивна. Пожалуйста, свяжитесь с администратором сайта для её активации.';
$_lang['login_email_subject'] = 'Данные для входа';
$_lang['login_magiclink_subject'] = 'Ваша одноразовая ссылка для входа';
$_lang['login_magiclink_err'] = 'Ваша ссылка для входа недействительна. Пожалуйста, запросите новую.';
$_lang['login_magiclink_email'] = '<h2>Одноразовая ссылка для входа</h2><p>Ваша ссылка для входа в систему управления MODX. Эта ссылка действительна для [[+expiration]].</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]?magiclink=[[+hash]]" class="btn">Войти</a></p><p class="small">Если вы не отправляли этот запрос, пожалуйста, проигнорируйте это письмо.</p>';
$_lang['login_magiclink_default_msg'] = 'Если ваш электронный ящик <i>[[+email]]</i> связан с учетной записью, вы получите письмо в ближайшее время.';
$_lang['login_magiclink_error_msg'] = 'Система не смогла отправить ссылку для входа по электронной почте. Пожалуйста, свяжитесь с администратором сайта.';
$_lang['login_forgot_email'] = '<h2>Забыли пароль?</h2><p>Мы получили запрос на изменение пароля вашего аккаунта. Вы можете сбросить пароль, нажав кнопку ниже и следуя инструкциям на экране.</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]?modhash=[[+hash]]" class="btn">Сбросить пароль</a></p><p class="small">Если вы не отправляли такого запроса, просто проигнорируйте это письмо.</p>';
$_lang['login_signup_email'] = '<p>Здравствуйте, [[+username]]!</p><p>Ваш аккаунт был зарегистрирован на сайте <strong>[[++site_name]]</strong>. Если вы не знаете свой пароль, [[++allow_manager_login_forgot_password:is=`1`:then=`сбросьте его, нажав ссылку «Забыли пароль» на странице входа`:else=`свяжитесь с администрацией сайта`]].</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]" class="btn">Войти на сайт [[++site_name]]</a></p>';
$_lang['login_greeting_morning'] = '<strong>Доброе утро</strong>, рады вас видеть!';
$_lang['login_greeting_afternoon'] = '<strong>Добрый день</strong>, рады вас видеть!';
$_lang['login_greeting_evening'] = '<strong>Добрый вечер</strong>, рады вас видеть!';
$_lang['login_greeting_night'] = '<strong>Доброй ночи</strong>, рады вас видеть!';
$_lang['login_note'] = 'Пожалуйста, войдите, чтобы получить доступ к панели управления.';
$_lang['login_note_passwordless'] = 'Пожалуйста, введите ваш адрес электронной почты, чтобы получить одноразовую ссылку для входа.';
$_lang['login_magiclink_email_button'] = 'Отправить мне одноразовую ссылку для входа';
$_lang['login_magiclink_email_placeholder'] = 'Электронная почта вашей учетной записи';
$_lang['login_email'] = 'Электронная почта';
$_lang['login_help_button_text'] = 'Помощь';
$_lang['login_help_title'] = 'Нужна помощь с MODX';
$_lang['login_help_text'] = '<p>Требуется поддержка профессионалов MODX? Мы создали справочник профессионалов MODX со всего мира, которые будут счастливы помочь вам. <a href="https://modx.com/professionals/" target="_blank" rel="noopener" title="Найти профессионалов MODX">Получить помощь на modx.com</a>.</p>';
$_lang['login_return_site'] = 'Вернуться на сайт';

