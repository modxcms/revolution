<!doctype html>
<html {if $_config.manager_direction EQ 'rtl'}dir="rtl"{/if} lang="{$_config.cultureKey}" xml:lang="{$_config.cultureKey}">
    <head>
        <meta charset="{$_config.modx_charset}">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>{$_lang.login_title} | {$_config.site_name|strip_tags|escape}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="robots" content="noindex, nofollow">

        {if $_config.manager_favicon_url}
            <link rel="shortcut icon" type="image/x-icon" href="{$_config.manager_favicon_url}" />
            <link rel="apple-touch-icon" href="{$_config.manager_favicon_url}">
        {/if}

        <link rel="stylesheet" type="text/css" href="{$_config.manager_url}templates/default/css/login{if $_config.compress_css}-min{/if}.css" />
    </head>
    <body id="login">
        {$onManagerLoginFormPrerender}

        <nav class="c-nav">
            {if $show_help}
                <a href="#help" class="c-nav__item c-helplink">{$_lang.login_help_button_text}</a>
            {/if}
            <div class="c-nav__item c-nav__item--nopadding">
                <form method="get" class="c-languageselect">
                    <span class="c-languageselect__arrow"></span>
                    <select name="manager_language" id="modx-login-language-select" class="c-languageselect__select" aria-label="{$_config.cultureKey}">
                        {foreach from=$languages item=language}
                            {assign var="native_language" value="language_native_{$language}"}
                            <option lang="{$language}" value="{$language}"{if $language == $_config.cultureKey} selected{/if}>{$_lang[$native_language]|capitalize}</option>
                        {/foreach}
                    </select>
                </form>
            </div>
        </nav>

        <div class="l-content">
            <header class="l-header">
                <img alt="MODX CMS/CMF" src="{$logo}" class="c-logo">
            </header>

            <main class="l-main">
                {if $show_help}
                    <div id="help" class="c-help">
                        <h2>{$_lang.login_help_title}</h2>
                        {$_lang.login_help_text}
                    </div>
                {/if}

                <h1>{$greeting}</h1>

                {if !$_post.modhash}
                    <form id="modx-login-form" class="c-form can-toggle {if $_post.username_reset|default}is-hidden{/if}" action="" method="post">
                        <input type="hidden" name="login_context" value="mgr">
                        <input type="hidden" name="modhash" value="{$modhash|default}">
                        <input type="hidden" name="returnUrl" value="{$returnUrl}">

                        <p class="lead">{$_lang.login_note}</p>

                        {if $error_message|default}
                            <p class="is-error">{$error_message}</p>
                        {/if}

                        <label>
                            {$_lang.login_username}
                            <input type="text" id="modx-login-username" name="username" autocomplete="on" autofocus value="{$_post.username|default}" required>
                        </label>

                        <label>
                            {$_lang.login_password}
                            <input type="password" id="modx-login-password" name="password" autocomplete="on" required>
                        </label>

                        <label>
                            <input type="checkbox" id="modx-login-rememberme" name="rememberme" autocomplete="on" {if $_post.rememberme|default}checked="checked"{/if} value="1">
                            {$rememberme}
                        </label>

                        {$onManagerLoginFormRender}

                        <button class="c-button" id="modx-login-btn" name="login" type="submit" value="1">{$_lang.login_button}</button>

                        {if $allow_forgot_password|default}
                            <button class="c-button c-button--ghost" id="modx-fl-link" name="forgotpassword">{$_lang.login_forget_your_login}</button>
                        {/if}
                    </form>

                    {if $allow_forgot_password|default}
                        <form action="" method="post" id="modx-forgot-login-form" class="c-form can-toggle {if NOT $_post.username_reset|default}is-hidden{/if}">
                            <p class="lead">{$_lang.login_forget_your_login_note}</p>

                                {if $error_message|default}
                                    <p class="is-error">{$error_message}</p>
                                {/if}

                                <label>
                                    {$_lang.login_username_or_email}
                                    <input type="text" id="modx-login-username-reset" name="username_reset" value="{$_post.username_reset|default}">
                                </label>

                                <button class="c-button" name="forgotlogin" type="submit" value="1" id="modx-fl-btn">{$_lang.login_send_activation_email}</button>

                            {if $allow_forgot_password|default}
                                <button name="modx-fl-back-to-login-link" id="modx-fl-back-to-login-link" class="c-button c-button--ghost">{$_lang.login_back_to_login}</button>
                            {/if}
                        </form>
                    {/if}
                {else}
                    <form action="" method="post" id="modx-new-password-form" class="c-form">
                        <input type="hidden" name="modhash" value="{$_post.modhash|default}">
                        <p class="lead">{$_lang.login_new_password_note}</p>

                        {if $error_message|default}
                            <p class="is-error">{$error_message}</p>
                        {/if}

                        <label>
                            {$_lang.login_new_password}
                            <input type="password" id="modx-login-specified-password" name="specifiedpassword" value="{$_post.specifiedpassword|default}" autofocus>
                        </label>
                        <label>
                            {$_lang.login_confirm_password}
                            <input type="password" id="modx-login-confirm-password" name="confirmpassword" value="{$_post.confirmpassword|default}">
                        </label>

                        <button class="c-button" name="login" type="submit" value="1">{$_lang.login_button}</button>
                    </form>
                {/if}

            </main>
            <footer class="l-footer">
                <p>
                    <a href="{$_config.site_url}">
                        <svg class="c-arrow c-arrow--left" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                            <g fill="none" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10">
                                <path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path>
                            </g>
                        </svg>
                        {$_lang.login_return_site}
                    </a>
                </p>
            </footer>
        </div>
        <div class="l-background" style="background-image:url({$background})"></div>

        <script src="{$_config.manager_url}assets/modext/sections/login.js" type="text/javascript"></script>
    </body>
</html>
