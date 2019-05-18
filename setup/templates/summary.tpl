<script type="text/javascript" src="assets/js/sections/summary.js"></script>
<form id="install" action="?action=summary" method="post">
    <h2>{$_lang.install_summary}</h2>
    {if $failed}
        <p>{$_lang.preinstall_failure}</p>
    {else}
        <p>{$_lang.preinstall_success}</p>
    {/if}
    <ul class="checklist {if $failed}failed{else}success{/if}">
        {foreach from=$test item=result}
            <li class="{$result.class|default}">{$result.msg|default}</li>
        {/foreach}
    </ul>

    <div class="setup_navbar">
        {if $failed}
            <input type="button" onclick="MODx.go('summary');" value="&#xf021; {$_lang.retry}" id="modx-next" class="button" />
        {else}
            <input type="submit" id="modx-next" class="button" name="proceed" value="{$_lang.install} &#xf019;" autofocus="autofocus" />
        {/if}
        <input type="button" onclick="MODx.go('{$back}');" value="&#xf053; {$_lang.back}" id="modx-back" class="button" />
    </div>
</form>
