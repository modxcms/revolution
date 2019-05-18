<form id="install" action="?action=install" method="post">
    <div class="setup_body">
        <h2>{$_lang.install_summary}</h2>
        <p>{if $failed}{$_lang.errors_occurred}{else}{$_lang.install_success}{/if}</p>
        <ul class="checklist">
        {foreach from=$results item=result}
            <li class="{$result.class} finalsuccess"> {$result.msg} </li>
        {/foreach}
        </ul>
    </div>
    <div class="setup_navbar">
        {if $failed}
            <input type="button" onclick="MODx.go('install');" value="{$_lang.retry} &#xf021;" id="modx-next" class="button" />
            <input type="button" onclick="MODx.go('summary');" value="&#xf053; {$_lang.back}" id="modx-back" class="button" />
        {else}
            <input type="submit" id="modx-next" class="button" name="proceed" value="{$_lang.next} &#xf054;" autofocus="autofocus" />
        {/if}
    </div>
</form>
