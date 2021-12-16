<input id="tv{$tv->id}" name="tv{$tv->id}"
	type="text" class="textfield"
	value="{$tv->get('value')|escape}"
	{$style}
	tvtype="{$tv->type}"
/>
<div id="tv-tags-{$tv->id}"></div>

<script>
// <![CDATA[
{literal}
Ext.onReady(function() {
    var fld = MODx.load({
    {/literal}
        xtype: 'textfield'
        ,id: 'tv{$tv->id}'
        ,itemId: 'tv{$tv->id}'
        ,applyTo: 'tv{$tv->id}'
        ,width: '99%'
        ,enableKeyEvents: true
        ,msgTarget: 'under'
        ,allowBlank: {if $params.allowBlank == 1 || $params.allowBlank == 'true'}true{else}false{/if}
    {literal}
        ,listeners: { 'keydown': { fn:MODx.fireResourceFormChange, scope:this}}
    });
    MODx.makeDroppable(fld, function(v) {
        var tf = fld{/literal}{$tv->id}{literal};
        if (tf) {
            var ov = tf.getValue();
            if (ov != '') {
                v = ','+v;
            }
        }
        return v;
    });
    Ext.getCmp('modx-panel-resource').getForm().add(fld);
});
{/literal}
// ]]>
</script>

<ul class="modx-tag-list" id="tv-{$tv->id}-tag-list">
{foreach from=$options item=item key=k name=cbs}
    <li class="modx-tag-opt{if $item.checked} modx-tag-checked{/if}" title="{$item.value}">{$item.text}</li>
{/foreach}
</ul>

<script>
// <![CDATA[
{literal}
Ext.onReady(function() {
    Ext.select('#tv-{/literal}{$tv->id}{literal}-tag-list li', true).on('click', function(e, i) {
        const li = Ext.get(i);
        if (!li) {
            return;
        }
        const tf = Ext.getCmp('tv{/literal}{$tv->id}{literal}');
        let v = tf.getValue();
        if (li.hasClass('modx-tag-checked')) {
            tf.setValue(Ext.util.Format.trimCommas(v.replace(li.dom.title, '')));
            li.removeClass('modx-tag-checked');
        } else {
            v = v + (v != '' ? ',' : '');
            tf.setValue(Ext.util.Format.trimCommas(v + li.dom.title));
            li.addClass('modx-tag-checked');
        }
        MODx.fireResourceFormChange();
    });
    const p = Ext.getCmp('modx-panel-resource');
    if (p) {
        p.on('tv-reset', function(o) {
            if (o.id != '{/literal}{$tv->id}{literal}') {
                return;
            }
            const df = Ext.get(`tvdef${o.id}`).dom.value.split(',');
            Ext.select(`#tv-${o.id}-tag-list li`, true).forEach(li => {
                if (df.indexOf(li.dom.title) != -1) {
                    li.addClass('modx-tag-checked');
                } else {
                    li.removeClass('modx-tag-checked');
                }
            });
        });
    }
});
{/literal}
// ]]>
</script>
