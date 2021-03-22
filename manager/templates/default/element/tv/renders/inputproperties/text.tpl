<div id="tv-input-properties-form{$tv|default}"></div>
{literal}

<script>
// <![CDATA[
var params = {
{/literal}{foreach from=$params key=k item=v name='p'}
 '{$k}': '{$v|default|escape:"javascript"}'{if NOT $smarty.foreach.p.last},{/if}
{/foreach}{literal}
};
var oc = {'change':{fn:function(){Ext.getCmp('modx-panel-tv').markDirty();},scope:this}};

var element = Ext.getCmp('modx-tv-elements');
if (element) {
  element.hide();
}

MODx.load({
    xtype: 'panel'
    ,layout: 'form'
    ,cls: 'form-with-labels'
    ,autoHeight: true
    ,border: false
    ,labelAlign: 'top'
    ,labelSeparator: ''
    ,items: [{
        xtype: 'combo-boolean'
        ,fieldLabel: _('required')
        ,description: MODx.expandHelp ? '' : _('required_desc')
        ,name: 'inopt_allowBlank'
        ,hiddenName: 'inopt_allowBlank'
        ,id: 'inopt_allowBlank{/literal}{$tv|default}{literal}'
        ,width: 200
        ,value: (params['allowBlank']) ? !(params['allowBlank'] === 0 || params['allowBlank'] === 'false') : true
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_allowBlank{/literal}{$tv|default}{literal}'
        ,html: _('required_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'numberfield'
        ,fieldLabel: _('min_length')
        ,name: 'inopt_minLength'
        ,id: 'inopt_minLength{/literal}{$tv|default}{literal}'
        ,value: params['minLength'] || ''
        ,msgTarget: 'under'
        ,validator: function (v) {
            var max = Ext.getCmp('inopt_maxLength{/literal}{$tv|default}{literal}').getValue();
            if (parseInt(v) > parseInt(max)) {
                return _('ext_minlenmaxfield');
            }
            return true;
        }
        ,width: 200
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_minLength{/literal}{$tv|default}{literal}'
        ,html: _('min_length_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'numberfield'
        ,fieldLabel: _('max_length')
        ,name: 'inopt_maxLength'
        ,id: 'inopt_maxLength{/literal}{$tv|default}{literal}'
        ,value: params['maxLength'] || ''
        ,msgTarget: 'under'
        ,validator: function(v) {
            var min = Ext.getCmp('inopt_minLength{/literal}{$tv|default}{literal}').getValue();
            if (parseInt(v) < parseInt(min)) {
                return _('ext_maxlenminfield');
            }
            return true;
        }
        ,width: 200
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_maxLength{/literal}{$tv|default}{literal}'
        ,html: _('max_length_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'textfield'
        ,fieldLabel: _('regex')
        ,name: 'inopt_regex'
        ,id: 'inopt_regex{/literal}{$tv|default}{literal}'
        ,value: params['regex'] || ''
        ,width: 200
        ,listeners: oc
    },{
        xtype: 'textfield'
        ,fieldLabel: _('regex_text')
        ,name: 'inopt_regexText'
        ,id: 'inopt_regexText{/literal}{$tv|default}{literal}'
        ,value: params['regexText'] || ''
        ,width: 200
        ,listeners: oc
    }]
    ,renderTo: 'tv-input-properties-form{/literal}{$tv|default}{literal}'
});
// ]]>
</script>
{/literal}
