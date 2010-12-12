<textarea id="tv{$tv->id}" name="tv{$tv->id}" class="modx-richtext" {literal}onchange="MODx.fireResourceFormChange();"{/literal}>{$tv->get('value')|escape}</textarea>

<script type="text/javascript">
MODx.makeDroppable(Ext.get('tv{$tv->id}'));
</script>