<div id="tv-input-properties-form{$tv|default}"></div>

{literal}
<script type="text/javascript">
// <![CDATA[
var params = {
{/literal}
{foreach from=$params key=k item=v name='p'}
    '{$k}': '{$v|escape:"javascript"}'{if NOT $smarty.foreach.p.last},{/if}
{/foreach}
{literal}
};
var oc = {'change':{fn:function(){Ext.getCmp('modx-panel-tv').markDirty();},scope:this}};

MODx.load({
    xtype: 'panel'
    ,layout: 'form'
    ,cls: 'form-with-labels'
    ,labelAlign: 'top'
    ,autoHeight: true
    ,border: false
    ,items: [{
        xtype: 'combo-boolean'
        ,fieldLabel: _('required')
        ,description: MODx.expandHelp ? '' : _('required_desc')
        ,name: 'inopt_allowBlank'
        ,hiddenName: 'inopt_allowBlank'
        ,id: 'inopt_allowBlank{/literal}{$tv|default}{literal}'
        ,anchor: '100%'
        ,value: (params['allowBlank']) ? !(params['allowBlank'] === 0 || params['allowBlank'] === 'false') : true
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_allowBlank{/literal}{$tv|default}{literal}'
        ,html: _('required_desc')
        ,cls: 'desc-under'
    },{
        layout: 'column'
        ,border: false
        ,defaults: {
            layout: 'form'
            ,labelAlign: 'top'
            ,labelSeparator: ''
            ,anchor: '100%'
            ,border: false
        }
        ,items: [{
            columnWidth: .5
            ,items: [{
                xtype: 'textfield'
                ,fieldLabel: _('min_length')
                ,name: 'inopt_minLength'
                ,id: 'inopt_minLength{/literal}{$tv|default}{literal}'
                ,value: params['minLength'] || ''
                ,anchor: '100%'
                ,listeners: oc
            }]
        },{
            columnWidth: .5
            ,items: [{
                xtype: 'textfield'
                ,fieldLabel: _('max_length')
                ,name: 'inopt_maxLength'
                ,id: 'inopt_maxLength{/literal}{$tv|default}{literal}'
                ,value: params['maxLength'] || ''
                ,anchor: '100%'
                ,listeners: oc
            }]
        }]
    }]
    ,renderTo: 'tv-input-properties-form{/literal}{$tv|default}{literal}'
});
// ]]>
</script>
{/literal}
