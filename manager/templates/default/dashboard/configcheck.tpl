{if count($warnings)}
    <h4>{$_lang.configcheck_notok}</h4>
    <ul class="configcheck">
        {foreach $warnings as $key => $value}
            <li>
                <h5 class="warn">{$key}</h5>
                <p><i class="icon icon-info-circle"></i> {$value}</p>
            </li>
        {/foreach}
    </ul>
{/if}
