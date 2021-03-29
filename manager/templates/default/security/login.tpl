<!doctype html>
<html {if $_config.manager_direction EQ 'rtl'}dir="rtl"{/if} lang="{$_config.manager_lang_attribute}" xml:lang="{$_config.manager_lang_attribute}">
<head>
    <title>{$_lang.login_title} | {$_config.site_name|strip_tags|escape}</title>
    <meta http-equiv="Content-Type" content="text/html; charset={$_config.modx_charset}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    {if $_config.manager_favicon_url}<link rel="shortcut icon" type="image/x-icon" href="{$_config.manager_favicon_url}" />{/if}

    <link rel="stylesheet" type="text/css" href="{$_config.manager_url}assets/ext3/resources/css/ext-all-notheme-min.css" />
	<link rel="stylesheet" type="text/css" href="{$_config.manager_url}templates/default/css/index{if $_config.compress_css}-min{/if}.css" />
    <link rel="stylesheet" type="text/css" href="{$_config.manager_url}templates/default/css/login{if $_config.compress_css}-min{/if}.css" />

{if isset($_config.ext_debug) && $_config.ext_debug}
    <script src="{$_config.manager_url}assets/ext3/adapter/ext/ext-base-debug.js"></script>
    <script src="{$_config.manager_url}assets/ext3/ext-all-debug.js"></script>
{else}
    <script src="{$_config.manager_url}assets/ext3/adapter/ext/ext-base.js"></script>
    <script src="{$_config.manager_url}assets/ext3/ext-all.js"></script>
{/if}
    <script src="assets/modext/core/modx.js"></script>

    <script src="assets/modext/core/modx.component.js"></script>
    <script src="assets/modext/util/utilities.js"></script>
    <script src="assets/modext/widgets/core/modx.panel.js"></script>
    <script src="assets/modext/widgets/core/modx.window.js"></script>
    <script src="assets/modext/sections/login.js"></script>

    <meta name="robots" content="noindex, nofollow" />
</head>

<body id="login">
{$onManagerLoginFormPrerender}
<br />

<div id="container">
    <div id="modx-login-logo">
        <img alt="MODX CMS/CMF" src="{$_config.manager_url}templates/default/images/modx-logo-color.svg" />
    </div>

    <div id="modx-panel-login-div" class="x-panel modx-form x-form-label-right">
        <form id="modx-login-form" action="" method="post">
            <input type="hidden" name="login_context" value="mgr" />
            <input type="hidden" name="modahsh" value="{$modahsh|default}" />
            <input type="hidden" name="returnUrl" value="{$returnUrl}" />

            <div class="x-panel x-panel-noborder">
                <div class="x-panel-bwrap">
                    <div class="x-panel-body x-panel-body-noheader">
                        <h2>{$_config.site_name|strip_tags|escape}</h2>
                        <br class="clear" />
                        {if isset($error_message) && $error_message}
                            <p class="error">{$error_message|default}</p>
                        {elseif isset($success_message) && $success_message}
                            <p class="success">{$success_message|default}</p>
                        {/if}
                    </div>
                </div>
            </div>

            <div class="x-form-item login-form-item login-form-item-first">
                <label for="modx-login-username">{$_lang.login_username}</label>
                <div class="x-form-element login-form-element">
                    <input type="text" id="modx-login-username" name="username" autocomplete="on" autofocus value="{$_post.username|default}" class="x-form-text x-form-field" aria-required="true" required />
                </div>
            </div>

            <div class="x-form-item login-form-item">
                <label for="modx-login-password">{$_lang.login_password}</label>
                <div class="x-form-element login-form-element">
                    <input type="password" id="modx-login-password" name="password" autocomplete="on" class="x-form-text x-form-field" aria-required="true" required />
                </div>
            </div>

            <div class="login-cb-row">
                <div class="login-cb-col one">
                    <div class="modx-login-fl-link">
{if $allow_forgot_password|default}
                        <a href="javascript:void(0);" id="modx-fl-link" style="{if $_post.username_reset|default}display:none;{/if}">{$_lang.login_forget_your_login}</a>
{/if}
                    </div>
                </div>
                <div class="login-cb-col two">
                    <div class="x-form-check-wrap modx-login-rm-cb">
                        <input type="checkbox" id="modx-login-rememberme" name="rememberme" autocomplete="on" {if $_post.rememberme|default}checked="checked"{/if} class="x-form-checkbox x-form-field" value="1" />
                        <label for="modx-login-rememberme" class="x-form-cb-label">{$_lang.login_remember}</label>
                    </div>
                    <button class="x-btn x-btn-small x-btn-icon-small-left primary-button x-btn-noicon login-form-btn" name="login" type="submit" value="1" id="modx-login-btn">{$_lang.login_button}</button>
                </div>
            </div>

            {$onManagerLoginFormRender}
        </form>

{if $allow_forgot_password|default}
        <div class="modx-forgot-login">
            <form id="modx-fl-form" action="" method="post">
                <div id="modx-forgot-login-form" style="{if NOT $_post.username_reset|default}display: none;{/if}">
                    <div class="x-form-item login-form-item">
                        <div class="x-form-element login-form-element">
                            <input type="text" id="modx-login-username-reset" name="username_reset" class="x-form-text x-form-field" value="{$_post.username_reset|default}" placeholder="{$_lang.login_username_or_email}" />
                        </div>
                        <div class="x-form-clear-left"></div>
                    </div>

                    <button class="x-btn x-btn-small x-btn-icon-small-left primary-button x-btn-noicon login-form-btn" name="forgotlogin" type="submit" value="1" id="modx-fl-btn">{$_lang.login_send_activation_email}</button>
                </div>
            </form>
        </div>
{/if}
        <br class="clear" />
    </div>

    <p class="loginLicense">{$_lang.login_copyright|replace:'[[+current_year]]':{'Y'|date}}</p>
</div>

<div id="modx-login-language-select-div">
    <label id="modx-login-language-select-label">{$language_str}:
        <select name="cultureKey" id="modx-login-language-select" aria-labeled-by="modx-login-language-select-label">
{$languages|indent:12}
        </select>
    </label>
</div>
</body>
</html>
