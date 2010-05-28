<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" {if $_config.manager_direction EQ 'rtl'}dir="rtl"{/if} lang="{$_config.manager_lang_attribute}" xml:lang="{$_config.manager_lang_attribute}">
<head>
	<title>{$_lang.login_title}</title>
	<meta http-equiv="Content-Type" content="text/html; charset={$_config.modx_charset}" />
    <link rel="stylesheet" type="text/css" href="{$_config.manager_url}assets/ext3/resources/css/ext-all-notheme-min.css" />
	{if $_config.compress_css}
	<link rel="stylesheet" type="text/css" href="{$_config.manager_url}templates/{$_config.manager_theme}/css/modx-min.css" />
	{else}
	<link rel="stylesheet" type="text/css" href="{$_config.manager_url}templates/{$_config.manager_theme}/css/xtheme-modx.css" />
	<link rel="stylesheet" type="text/css" href="{$_config.manager_url}templates/{$_config.manager_theme}/css/index.css" />
	{/if}
    <link rel="stylesheet" type="text/css" href="templates/{$_config.manager_theme}/css/login{if $_config.compress_css}-min{/if}.css" />
    
    <script src="assets/ext3/adapter/ext/ext-base.js" type="text/javascript"></script>
    <script src="assets/ext3/ext-all.js" type="text/javascript"></script>
    <script src="assets/modext/{if $_config.compress_js}build/core/modx-min{else}core/modx{/if}.js" type="text/javascript"></script>
	<script src="{$_config.connectors_url}lang.js.php?topic=login" type="text/javascript"></script>

	{if $_config.compress_js}
    <script src="assets/modext/build/core/modx.component-min.js" type="text/javascript"></script>
    <script src="assets/modext/build/util/utilities-min.js" type="text/javascript"></script>
    <script src="assets/modext/build/widgets/core/modx.panel-min.js" type="text/javascript"></script>
    <script src="assets/modext/build/widgets/core/modx.window-min.js" type="text/javascript"></script>
    <script src="assets/modext/build/sections/login-min.js" type="text/javascript"></script>
	{else}
    <script src="assets/modext/core/modx.component.js" type="text/javascript"></script>
    <script src="assets/modext/util/utilities.js" type="text/javascript"></script>
	<script src="assets/modext/widgets/core/modx.panel.js" type="text/javascript"></script>
    <script src="assets/modext/widgets/core/modx.window.js" type="text/javascript"></script>
    <script src="assets/modext/sections/login.js" type="text/javascript"></script>
    {/if}
    
    <meta name="robots" content="noindex, nofollow" />
</head>
<body id="login">
{$onManagerLoginFormPrerender}
<br />
<div id="modx-panel-login-div" class="x-panel modx-form x-form-label-right" style="border: 1px solid #e0e0e0;">
 
 <div class="x-panel-tl">
  <div class="x-panel-tr">
   <div class="x-panel-tc">
    <div class="x-panel-header x-unselectable">
     <span class="x-panel-header-text">{$_lang.login_button}</span>
    </div>
   </div>
  </div>
 </div>
 <div class="x-panel-bwrap">
  <div class="x-panel-ml">
   <div class="x-panel-mr">
    <div class="x-panel-mc">
    
<form id="modx-login-form" action="" method="post">
    <input type="hidden" name="login_context" value="mgr" />
    <input type="hidden" name="modahsh" value="{$modahsh}" />
    <input type="hidden" name="returnUrl" value="{$returnUrl}" />
    	    
    <div class="x-panel x-panel-noborder"><div class="x-panel-bwrap"><div class="x-panel-body x-panel-body-noheader">
    <h2>{$_config.site_name}</h2>
    <p>{$_lang.login_message}</p>
    
    {if $error_message}<p class="error">{$error_message}</p>{/if}
    </div></div></div>
    
    <div class="x-form-item">
      <label for="modx-login-username" class="x-form-item-label">{$_lang.login_username}</label>
      <div class="x-form-element">
        <input type="text" id="modx-login-username" name="username" tabindex="1" autocomplete="on" value="{$_post.username}" class="x-form-text x-form-field" />
      </div>
      <div class="x-form-clear-left"></div>
    </div>
    
    <div class="x-form-item">
      <label for="modx-login-password" class="x-form-item-label">{$_lang.login_password}</label>
      <div class="x-form-element">
        <input type="password" id="modx-login-password" name="password" tabindex="2" autocomplete="on" class="x-form-text x-form-field" />
      </div>
      <div class="x-form-clear-left"></div>
    </div>
    
    <div class="x-form-item">
      <div class="x-form-element">
          <div class="x-form-check-wrap">
              <input type="checkbox" id="modx-login-rememberme" name="rememberme" tabindex="3" autocomplete="on" {if $_post.rememberme}checked="checked"{/if} class="x-form-checkbox x-form-field" value="1" />
              <label for="modx-login-rememberme" class="x-form-cb-label">{$_lang.login_remember}</label>
          </div>
      </div>
      <div class="x-form-clear-left"></div>
    </div>
            
    {$onManagerLoginFormRender}
    
    <br class="clear" />

   <table cellspacing="0" class="x-btn x-btn-noicon" style="float: right; width: 71px;" id="modx-login-btn-ct">
   <tbody class="x-btn-small x-btn-icon-small-left">
    <tr>
        <td class="x-btn-tl"><em></em></td>
        <td class="x-btn-tc"></td>
        <td class="x-btn-tr"><em></em></td>
    </tr>
    <tr>
        <td class="x-btn-ml"><em></em></td>
        <td class="x-btn-mc"><em>
            <button class="x-btn-text" name="login" type="submit" value="1" id="modx-login-btn" tabindex="4">{$_lang.login_button}</button>
        </em></td>
        <td class="x-btn-mr"><em></em></td>
    </tr>
    <tr>
        <td class="x-btn-bl"><em></em></td>
        <td class="x-btn-bc"></td>
        <td class="x-btn-br"><em></em></td>
    </tr>
   </tbody>
   </table>
</form>

    <div class="modx-forgot-login">
    <form id="modx-fl-form" action="" method="post">
       <a href="javascript:void(0);" id="modx-fl-link" style="{if $_post.email}display:none;{/if}">{$_lang.login_forget_your_login}</a>
       <div id="modx-forgot-login-form" style="{if NOT $_post.email}display: none;{/if}">
                      
           <div class="x-form-item">
              <label for="modx-login-email" class="x-form-item-label">{$_lang.login_email_label}</label>
              <div class="x-form-element">
                <input type="text" id="modx-login-email" name="email" class="x-form-text x-form-field" value="{$_post.email}" />
              </div>
              <div class="x-form-clear-left"></div>
           </div>
           
           
            <table cellspacing="0" class="x-btn x-btn-noicon" style="float: right; width: 71px;" id="modx-fl-btn-ct">
               <tbody class="x-btn-small x-btn-icon-small-left">
                <tr>
                    <td class="x-btn-tl"><em></em></td>
                    <td class="x-btn-tc"></td>
                    <td class="x-btn-tr"><em></em></td>
                </tr>
                <tr>
                    <td class="x-btn-ml"><em></em></td>
                    <td class="x-btn-mc"><em>
                        <button class="x-btn-text" name="forgotlogin" type="submit" value="1" id="modx-fl-btn">{$_lang.login_send_activation_email}</button>
                    </em></td>
                    <td class="x-btn-mr"><em></em></td>
                </tr>
                <tr>
                    <td class="x-btn-bl"><em></em></td>
                    <td class="x-btn-bc"></td>
                    <td class="x-btn-br"><em></em></td>
                </tr>
               </tbody>
           </table>
           
           <br class="clear" />
           <br class="clear" />
           
       </div>
    </form>
    </div>
    
    <br class="clear" />

    </div>
   </div>
  </div>      
 </div>
</div>

<p class="loginLicense">
{$_lang.login_copyright}
</p>

</body>
</html>
