<div class="modx-page-header">
<h2>{$_lang.refresh_title}</h2>
</div>

<div class="padding modx-page">
<p>{$published}</p>
<p>{$unpublished}</p>

<hr />

<p>{$_lang.cache_files_deleted}</p>

{if $results.deleted_files_count GT 0}
<ul>
{section name=fileIdx loop=$results.deleted_files}
    <li>{$results.deleted_files[fileIdx]}</li>
{/section}
</ul>
{/if}

</div>