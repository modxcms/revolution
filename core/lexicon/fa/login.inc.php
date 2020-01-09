<?php
/**
 * Login English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['login_language'] = 'Language';
$_lang['login_activation_key_err'] = 'The activation key does not match! Please check your activation email and make sure you followed the correct URL.';
$_lang['login_blocked_admin'] = 'You have been blocked from the Manager by an administrator.';
$_lang['login_blocked_error'] = 'دسترسی شما موقتا مسدود شده است و نميتوانيد وارد شويد. لطفا بعدا دوباره تلاش کنید.';
$_lang['login_blocked_ip'] = 'شما مجاز به ورود با آدرس IP فعلیتان نیستید.';
$_lang['login_blocked_time'] = 'شما در حال حاضر مجاز به ورود نیستید. لطفا بعدا دوباره تلاش کنید.';
$_lang['login_blocked_too_many_attempts'] = 'دسترسی شما به دلیل تعداد بالای تلاش ناموفقِ ورود، مسدود شده است.';
$_lang['login_button'] = 'ورود';
$_lang['login_cannot_locate_account'] = 'The username or password you entered is incorrect. Please check the username, re-type the password, and try again.';
$_lang['login_copyright'] = '&copy; 2005-[[+current_year]] by <a href="https://modx.com/about/company" target="_blank">MODX, LLC</a>. MODX Revolution&trade; is licensed under the GPLv2 or later.';
$_lang['login_email_label'] = 'آدرس ایمیلِ حساب:';
$_lang['login_err_unknown'] = 'خطایی نامشخصی به هنگام ورود رخ داده است.';
$_lang['login_forget_your_login'] = 'Forgot your password?';
$_lang['login_forget_your_login_note'] = 'To set a new password, enter your username or email below. If you have an account, a verification link will be sent to your email address.';
$_lang['login_new_password'] = 'گذرواژه‌ی جدید';
$_lang['login_new_password_note'] = 'Please confirm your new password by entering it twice.<br><br>&bull;&nbsp;The minimum password length is <strong>[[+length]]</strong> characters.';
$_lang['login_confirm_password'] = 'بازنویسی گذرواژه';
$_lang['login_back_to_login'] = 'Back to login';
$_lang['login_hostname_error'] = 'آدرس IP شما با اطلاعات Host درخواستتان مطابقت ندارد.';
$_lang['login_message'] = 'لطفا اطلاعات شناسایی خود را برای ورود به بخش مدیریت وارد نمایید. همچنین لطفا دقت نمایید که نام کاربری و گذر واژه به بزرگی و کوچکی حروف حساس هستند!';
$_lang['login_password'] = 'گذر واژه';
$_lang['login_password_reset_act_sent'] = 'If the user or email exists, you’ll receive an email shortly.';
$_lang['login_remember'] = 'Keep me logged in for [[+lifetime]]';
$_lang['login_send_activation_email'] = 'ارسال ایمیل فعال سازی';
$_lang['login_title'] = 'ورود';
$_lang['login_user_err_nf_email'] = 'If the user or email exists, you’ll receive an email shortly.';
$_lang['login_username'] = 'Username';
$_lang['login_username_or_email'] = 'نام کاربری یا ایمیل';
$_lang['login_username_password_incorrect'] = 'نام کاربری یا گذر واژه‌ای که وارد کردید صحیح نمی باشد. لطفا درستی اطلاعات وارد شده را بررسی کنید.';
$_lang['login_user_inactive'] = 'دسترسی شما غیرفعال شده است. لطفا برای فعالسازی مجدد آن با مدیران سیستم تماس حاصل نمایید.';
$_lang['login_email_subject'] = 'Your login details';
$_lang['login_magiclink_subject'] = 'Your one-time login link';
$_lang['login_magiclink_err'] = 'Your login link is not valid. Please request a new one.';
$_lang['login_magiclink_email'] = '<h2>One-time Login Link</h2><p>Here is your link to get logged in to the MODX manager. This link is valid for the next [[+expiration]].</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]?magiclink=[[+hash]]" class="btn">Log me in</a></p><p class="small">If you did not send this request, please ignore this email.</p>';
$_lang['login_magiclink_default_msg'] = 'If your email <i>[[+email]]</i> is registered with an account, you’ll receive an email shortly.';
$_lang['login_magiclink_error_msg'] = 'The system was not able to send a login link via email. Please contact the site administrator if this error is permanent.';
$_lang['login_forgot_email'] = '<h2>Forgot your password?</h2><p>We received a request to change your MODX Revolution password. You can reset your password by clicking the button below and following the instructions on screen.</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]?modhash=[[+hash]]" class="btn">Reset my password</a></p><p class="small">If you did not send this request, please ignore this email.</p>';
$_lang['login_signup_email'] = '<p>Hello, [[+username]]!</p><p>An account was registered for you on the <strong>[[++site_name]]</strong> website. If you do not know your password, [[++allow_manager_login_forgot_password:is=`1`:then=`reset it using the forgot password link on login screen`:else=`ask your Site Administrator`]].</p><p class="center"><a href="[[+url_scheme]][[+http_host]][[+manager_url]]" class="btn">Log into [[++site_name]]</a></p>';
$_lang['login_greeting_morning'] = '<strong>Good morning</strong>, welcome back!';
$_lang['login_greeting_afternoon'] = '<strong>Good afternoon</strong>, welcome back!';
$_lang['login_greeting_evening'] = '<strong>Good evening</strong>, welcome back!';
$_lang['login_greeting_night'] = '<strong>Good evening</strong>, welcome back!';
$_lang['login_note'] = 'Please log in to access the Manager.';
$_lang['login_note_passwordless'] = 'Please enter your email address to receive a one-time login link.';
$_lang['login_magiclink_email_button'] = 'Send me a one-time login link';
$_lang['login_magiclink_email_placeholder'] = 'Your user account\'s email here';
$_lang['login_email'] = 'Email';
$_lang['login_help_button_text'] = 'راهنما';
$_lang['login_help_title'] = 'Get help with MODX';
$_lang['login_help_text'] = '<p>Do you need professional MODX support? We’ve curated a directory of MODX Professionals around the world who are happy to help. <a href="https://modx.com/professionals/" target="_blank" rel="noopener" title="Find MODX Professionals on the MODX website">Get help quickly at modx.com</a>.</p>';
$_lang['login_return_site'] = 'Return to website';

