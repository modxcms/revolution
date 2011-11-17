
<div class="x-panel modx-form x-panel-noborder x-form-label-left">
<div class="x-panel-bwrap">
    <form class="x-panel-body x-panel-body-noheader x-panel-body-noborder x-form" method="POST" style="padding: 10px; height: auto;">

    <div class="x-panel modx-page-header x-panel-noborder">
        <h2>{$_lang.warning}</h2>
    </div>

    <div class="x-panel x-form-label-left" style="display: block;">
    <div class="x-panel-bwrap">
    <div class="x-panel-body x-panel-body-noheader" style="padding: 10px; color: #111;">
        <br />
        {foreach from=$errors item=error name=errors}
            <p>{$error}</p>{if NOT $smarty.foreach.errors.last}<hr />{/if}
        {/foreach}
    </div>
    </div>
    </div>

    </form>
</div>
</div>
