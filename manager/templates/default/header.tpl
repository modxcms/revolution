{if $_config.manager_html5_cache EQ 1}<!DOCTYPE HTML>{else}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">{/if}

<html xmlns="http://www.w3.org/1999/xhtml" {if $_config.manager_direction EQ 'rtl'}dir="rtl"{/if} lang="{$_config.manager_lang_attribute}" xml:lang="{$_config.manager_lang_attribute}"{if $_config.manager_html5_cache EQ 1} manifest="{$_config.manager_url}cache.manifest.php"{/if}>
<head>
<title>{if $_pagetitle}{$_pagetitle} | {/if}{$_config.site_name}</title>
<meta http-equiv="Content-Type" content="text/html; charset={$_config.modx_charset}" />

{if $_config.manager_favicon_url}<link rel="shortcut icon" type="image/x-icon" href="{$_config.manager_favicon_url}" />{/if}

{if $_config.compress_css}
<link rel="stylesheet" type="text/css" href="{$_config.manager_url}assets/ext3/resources/css/ext-all-notheme-min.css" />
<link rel="stylesheet" type="text/css" href="{$_config.manager_url}min/index.php?f={$_config.manager_url}templates/default/css/xtheme-modx.css,{$_config.manager_url}templates/default/css/index.css" />
{else}
<link rel="stylesheet" type="text/css" href="{$_config.manager_url}assets/ext3/resources/css/ext-all-notheme-min.css" />
<link rel="stylesheet" type="text/css" href="{$_config.manager_url}templates/default/css/xtheme-modx.css" />
<link rel="stylesheet" type="text/css" href="{$_config.manager_url}templates/default/css/index.css" />
{/if}

<link rel="stylesheet" href="{$_config.manager_url}templates/default/css/font-awesome.min.css" />
<!--[if gte IE 9]>
  <style type="text/css">
    #modx-navbar {
       filter: none;
    }
  </style>
<![endif]-->

<script src="{$_config.manager_url}assets/ext3/adapter/ext/ext-base.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/ext3/ext-all.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/core/modx.js" type="text/javascript"></script>
<script src="{$_config.connectors_url}lang.js.php?ctx=mgr&topic=topmenu,file,resource,{$_lang_topics}&action={$smarty.get.a|strip_tags}" type="text/javascript"></script>
<script src="{$_config.connectors_url}layout/modx.config.js.php?action={$smarty.get.a|strip_tags}{if $_ctx}&wctx={$_ctx}{/if}" type="text/javascript"></script>

{if $_config.compress_js && $_config.compress_js_groups}
<script src="{$_config.manager_url}min/index.php?g=coreJs1" type="text/javascript"></script>
<script src="{$_config.manager_url}min/index.php?g=coreJs2" type="text/javascript"></script>
<script src="{$_config.manager_url}min/index.php?g=coreJs3" type="text/javascript"></script>
{/if}

<script type="text/javascript">
<!--
    function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
    }
//-->
</script>


{$maincssjs}
{foreach from=$cssjs item=scr}
{$scr}
{/foreach}
</head>
<body id="modx-body-tag">

<div id="modx-browser"></div>
<div id="modx-container">
    <div id="modx-mainpanel">
        <div id="modx-header">
            <div id="modx-navbar">
                <div id="modx-user-menu">
                    {if $canChangeProfile}
                        <a class="modx-user-profile" href="?a=security/user">{$username}</a>{else}<span class="modx-user-profile">{$username}</span>
                    {/if}
                    {if $canLogout}
                        <a class="modx-logout" href="javascript:;" onclick="MODx.logout();">{$_lang.logout}</a>
                    {/if}
                    {if $canModifySettings or $canCustomizeManager or $canManageDashboards or $canManageContexts or $canManageTopNav or $canManageACLs or $canManageProperties or $canManageLexicons or $canManageLexicons}
                        <a id="modx-settings-toggle-large" href="#" onclick="toggle_visibility('modx-settings-menu');" title="{$_lang.settings}"><i class="icon-cog icon-large"></i></a>
                        <ul id="modx-settings-menu" class="modx-subnav" style="display:none">
                        {if $canModifySettings}
                            <li>
                                <a href="?a=system/settings">{$_lang.system_settings} 
                                    <span class="description">{$_lang.system_settings_desc}</span>
                                </a>
                            </li>
                        {/if}
                        {if $canCustomizeManager}
                            <li>
                                <a href="?a=security/forms">{$_lang.bespoke_manager}
                                    <span class="description">{$_lang.bespoke_manager_desc}</span>
                                </a>
                            </li>
                        {/if}
                        {if $canManageDashboards}
                            <li>
                                <a href="?a=system/dashboards">{$_lang.dashboards}
                                    <span class="description">{$_lang.dashboards_desc}</span>
                                </a>
                            </li>
                        {/if}
                        {if $canManageContexts}
                            <li>
                                <a href="?a=context">{$_lang.contexts}
                                    <span class="description">{$_lang.contexts_desc}</span>
                                </a>
                            </li>
                        {/if}
                        {if $canManageTopNav}
                            <li>
                                <a href="?a=system/action">{$_lang.edit_menu}
                                    <span class="description">{$_lang.edit_menu_desc}</span>
                                </a>
                            </li>
                        {/if}
                        {if $canManageACLs}
                            <li>
                                <a href="?a=security/permission">{$_lang.acls}
                                    <span class="description">{$_lang.acls_desc}</span>
                                </a>
                            </li>
                        {/if}
                        {if $canManageProperties}
                            <li>
                                <a href="?a=element/propertyset">{$_lang.propertysets}
                                    <span class="description">{$_lang.propertysets_desc}</span>
                                </a>
                            </li>
                        {/if}
                        {if $canManageLexicons}
                            <li>
                                <a href="?a=workspaces/lexicon">{$_lang.lexicon_management}
                                    <span class="description">{$_lang.lexicon_management_desc}</span>
                                </a>
                            </li>
                        {/if}
                        {if $canManageNamespaces}
                            <li>
                                <a href="?a=workspaces/namespace">{$_lang.namespaces}
                                    <span class="description">{$_lang.namespaces_desc}</span>
                                </a>
                            </li>
                        {/if}
                        </ul>
                    {/if}
                    <a class="modx-help" href="?a=help" title="{$_lang.help}"><i class="icon-question-sign icon-large"></i></a>

                </div>
                <ul id="modx-topnav">
                    <li id="modx-home-dashboard">
                        <a href="?a=welcome" title="{$_lang.dashboard}">{$_lang.dashboard}</a>
                    </li>
                    {$navb}
                    <li class="cls"></li>
                </ul>
                <span id="modx-manager-search">
                    <input type="search" placeholder="Search…" onfocus="toggle_visibility('modx-manager-search-results');" onblur="toggle_visibility('modx-manager-search-results');" />
                    <div id="modx-manager-search-results" style="display:none">
                        <div class="section">
                            <h3>Content</h3>
                            <p>Welcome to MODX…</p>
                        </div >
                        <div class="section">
                            <h3>Chunks</h3>
                            <p>welcome.tpl</p> 
                        </div>
                        <div class="section">
                            <h3>Templates</h3>
                            <p>Welcome template</p>
                        </div>
                        <div class="section">
                            <h3>Snippets</h3>
                            <p>WelcomeBack—display a logged in username</p>
                        </div>
                    </div>
                    <!-- end #modx-manager-search-results -->
                </span>
            </div>
        </div>
        
        <div id="modAB"></div>
        <div id="modx-leftbar"></div>
        <div id="modx-content">