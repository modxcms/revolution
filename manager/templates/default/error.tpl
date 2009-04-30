<div class="modx_error">
    <h2>Error!</h2>
    
    <p>{$_e.message}</p>
    
    {if $_e.errors|@count GT 0}
    <p></p>
    <p><strong>Errors:</strong></p>
    <ul>
    {foreach from=$_e.errors item=error}
        <li>{$error}</li>
    {/foreach}
    </ul>
    {/if}
</div>