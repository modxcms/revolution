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
        ,fieldLabel: _('width')
        ,name: 'prop_w'
        ,id: 'prop_w{/literal}{$tv}{literal}'
        ,value: params['w'] || '100%'
    },{
        xtype: 'textfield'
        ,fieldLabel: _('height')
        ,name: 'prop_h'
        ,id: 'prop_h{/literal}{$tv}{literal}'
        ,value: params['h'] || '300px'
    }]
    ,renderTo: 'tv-wprops-form{/literal}{$tv}{literal}'
});
</script>
{/literal}