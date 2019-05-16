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
            <button type="button" id="modx-next" class="button" onclick="MODx.go('install');">{$_lang.retry} &#xf054;</button>
            <button type="button" id="modx-back" class="button" onclick="MODx.go('summary');">&#xf053; {$_lang.back}</button>
        {else}
            <input type="submit" id="modx-next" class="button" name="proceed" value="{$_lang.next} &#xf054;" autofocus="autofocus" />
        {/if}
    </div>
</form>
