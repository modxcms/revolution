<div id="tv-wprops-form{$tv}"></div>
{literal}

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
        ,value: params['delimiter'] || ''
        ,width: 300
    }]
    ,renderTo: 'tv-wprops-form{/literal}{$tv}{literal}'
});
</script>
{/literal}