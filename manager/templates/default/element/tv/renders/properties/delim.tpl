{literal}
<div id="tv-wprops-form"></div>

<script type="text/javascript">
var params = {
{/literal}{foreach from=$params key=k item=v name='p'}
 '{$k}': '{$v}'{if NOT $smarty.foreach.p.last},{/if}
{/foreach}{literal}
};
MODx.load({
    xtype: 'panel'
    ,layout: 'form'
    ,autoHeight: true
    ,labelWidth: 150
    ,border: false
    ,items: [{
        xtype: 'textfield'
        ,fieldLabel: _('delimiter')
        ,name: 'prop_delimiter'
        ,value: params.delimiter || ''
    }]
    ,renderTo: 'tv-wprops-form'
});
</script>
{/literal}