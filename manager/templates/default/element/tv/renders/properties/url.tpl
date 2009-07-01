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
        ,fieldLabel: _('url_display_text')
        ,name: 'prop_text'
        ,id: 'prop_text{/literal}{$tv}{literal}'
        ,value: params['text'] || ''
    },{
        xtype: 'textfield' 
        ,fieldLabel: _('title')
        ,name: 'prop_title'
        ,id: 'prop_title{/literal}{$tv}{literal}'
        ,value: params['title'] || ''
    },{
        xtype: 'textfield' 
        ,fieldLabel: _('class')
        ,name: 'prop_class'
        ,id: 'prop_class{/literal}{$tv}{literal}'
        ,value: params['class'] || ''
    },{
        xtype: 'textfield' 
        ,fieldLabel: _('style')
        ,name: 'prop_style'
        ,id: 'prop_style{/literal}{$tv}{literal}'
        ,value: params['style'] || ''
    },{
        xtype: 'textfield' 
        ,fieldLabel: _('target')
        ,name: 'prop_target'
        ,id: 'prop_target{/literal}{$tv}{literal}'
        ,value: params['target'] || ''
    },{
        xtype: 'textfield' 
        ,fieldLabel: _('attributes')
        ,name: 'prop_attrib'
        ,id: 'prop_attrib{/literal}{$tv}{literal}'
        ,value: params['attrib'] || ''
    }]
    ,renderTo: 'tv-wprops-form{/literal}{$tv}{literal}'
});
</script>
{/literal}