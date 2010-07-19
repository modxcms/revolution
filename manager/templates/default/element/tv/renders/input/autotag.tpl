<input id="tv{$tv->id}" name="tv{$tv->id}"
	type="text" class="textfield"
	value="{$tv->get('value')|escape}"
	{$style}
	tvtype="{$tv->type}"
/>
<div id="tv-tags-{$tv->id}"></div>

<script type="text/javascript">
// <![CDATA[
{literal}
MODx.load({
{/literal}
    xtype: 'textfield'
    ,applyTo: 'tv{$tv->id}'
    ,width: '97%'
    ,enableKeyEvents: true
{literal}
    ,listeners: { 'keydown': { fn:MODx.fireResourceFormChange, scope:this}}
});
{/literal}
// ]]>
</script>

<ul class="modx-tag-list" id="tv-{$tv->id}-tag-list">
{foreach from=$opts item=item key=k name=cbs}
    <li class="modx-tag-opt{if $item.checked} modx-tag-checked{/if}" title="{$item.value}">{$item.value}</li>
{/foreach}
</ul>

<script type="text/javascript">
// <![CDATA[
{literal}
Ext.select('#tv-{/literal}{$tv->id}{literal}-tag-list li',true).on('click',function(e,i) {
    var li = Ext.get(i);
    if (!li) { return; }
    var tf = Ext.get('tv{/literal}{$tv->id}{literal}');
    if (li.hasClass('modx-tag-checked')) {
        tf.dom.value = Ext.util.Format.trimCommas(tf.dom.value.replace(li.dom.title,''));
        li.removeClass('modx-tag-checked');
    } else {
        var v = tf.dom.value+(tf.dom.value != '' ? ',' : '');
        tf.dom.value = Ext.util.Format.trimCommas(v+li.dom.title);
        li.addClass('modx-tag-checked');
    }
    MODx.fireResourceFormChange();
});

var rs = Ext.get('modx-reset-tv-{/literal}{$tv->id}{literal}');
if (rs) {
    rs.on('click',function(e,o) {
        var df = Ext.get('tvdef{/literal}{$tv->id}{literal}').dom.value;
        df = df.split(',');
        Ext.select('#tv-{/literal}{$tv->id}{literal}-tag-list li',true).each(function(li,c,idx) {
            if (df.indexOf(li.dom.title) != -1) {
                li.addClass('modx-tag-checked');
            } else {
                li.removeClass('modx-tag-checked');
            }
        });
    });
}
{/literal}
// ]]>
</script>