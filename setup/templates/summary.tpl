<script type="text/javascript" src="assets/js/sections/summary.js"></script>
<form id="install" action="?action=summary" method="post">
    <h2>{$_lang.install_summary}</h2>
    {if $failed}
        <p>{$_lang.preinstall_failure}</p>
    {else}
        <p>{$_lang.preinstall_success}</p>
    {/if}
    <ul class="checklist test">
        {foreach from=$test item=result}
            <li class="{$result.class|default}">{$result.msg|default}</li>
        {/foreach}
    </ul>

    <br />

    <div class="setup_navbar">
        {if $failed}
            <button type="button" id="modx-next" class="button" onclick="MODx.go('summary');">{$_lang.retry}</button>
        {else}
            <input type="submit" id="modx-next" name="proceed" class="button" value="{$_lang.install}" autofocus="autofocus" />
        {/if}
        <button type="button" id="modx-back" class="button" onclick="MODx.go('{$back}');">&#xf053; {$_lang.back}</button>
    </div>
</form>
