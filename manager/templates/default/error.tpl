<div class="modx_error">
    <h2><i class="icon icon-exclamation-triangle"></i> An error occurred...</h2>

    <div class="error_container{if $_e.errors|@count GT 0} multiple{/if}">
        <p>{$_e.message}</p>

        {if $_e.errors|@count GT 0}
        <p>&nbsp;</p>
        <p><strong>Errors:</strong></p>
        <ul>
        {foreach from=$_e.errors item=error}
            <li><i class="icon icon-angle-right"></i> {$error}</li>
        {/foreach}
        </ul>
        {/if}
    </div>
</div>