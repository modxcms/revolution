<script type="text/javascript" src="assets/js/sections/summary.js"></script>
<form id="install" action="?action=summary" method="post">
<h2>{$_lang.install_summary}</h2>
{if $failed}
<p>{$_lang.preinstall_failure}</p>
{else}
<p>{$_lang.preinstall_success}</p>
{/if}
<ul class="checklist">
{foreach from=$test item=result}
<li class="{$result.class}">{$result.msg}</li>
{/foreach}
</ul>

<br />

<div class="setup_navbar">
{if $failed}
    <button type="button" id="modx-next" onclick="MODx.go('summary');">{$_lang.retry}</button>
{else}
    <input type="submit" id="modx-next" name="proceed" value="{$_lang.install}" />
{/if}
    <button type="button" id="modx-back" onclick="MODx.go('{$back}');">{$_lang.back}</button>
</div>
</form>