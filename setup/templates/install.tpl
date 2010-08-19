<script type="text/javascript" src="assets/js/sections/install.js"></script>
<form id="install" action="?action=install" method="post">
<h2>{$_lang.install_summary}</h2>
{if $failed}
<p>{$_lang.errors_occurred}</p>
{else}
<p>
    {$_lang.install_success}
    <br />(<a style="font-size: .9em" href="#continuebtn">{$_lang.skip_to_bottom}</a>)
    <br /><br />
    <a href="javascript:void(0);" class="modx-toggle-success">{$_lang.toggle_success}</a> | 
    <a href="javascript:void(0);" class="modx-toggle-warning">{$_lang.toggle_warnings}</a>
    
</p>
{/if}
<ul class="checklist">
{foreach from=$results item=result}
<li class="{$result.class} finalsuccess" {if NOT $failed}style="display: none;"{/if}>{$result.msg}</li>
{/foreach}
</ul>

<br />

<a id="continuebtn"></a>

<div class="setup_navbar">
{if $failed}
    <button type="button" id="modx-next" onclick="MODx.go('install');">{$_lang.retry}</button>
    <button type="button" id="modx-back" onclick="MODx.go('summary');">{$_lang.back}</button>
{else}
    <input type="submit" id="modx-next" name="proceed" value="{$_lang.next}" />
{/if}
</div>
</form>