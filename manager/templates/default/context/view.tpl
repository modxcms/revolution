<form id="context_data" action="{$_config.connectors_url}context/index.php" onsubmit="return false;">
<input type="hidden" class="hidden" id="id" name="id" value="{$context->key}" />

<div class="padding">

<h2>{$_lang.context}: {$context->key}</h2>

<p>{$context->description}</p>

<h3>{$_lang.context_settings}</h3>

<table class="classy">
<thead>
<tr>
    <th style="width: 10em;">{$_lang.context_key}</th>
    <td>{$_lang.value}</td>
</tr>
</thead>
<tbody>
{foreach from=$context->modContextSetting item=setting}
<tr>
    <th>{$setting->key}</th>
    <td>
        <small>{$setting->value}</small>
    </td>
</tr>
{/foreach}
</tbody>
</table>
</div>


</div>
<script src="assets/modext/sections/context/view.js"></script>
