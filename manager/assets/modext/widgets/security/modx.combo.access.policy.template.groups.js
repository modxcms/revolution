/**
 * @class MODx.combo.AccessPolicyTemplateGroups
 * @extends MODx.combo.ComboBox
 * @param {Object} config An object of options.
 * @xtype modx-combo-access-policy-template-group
 */
MODx.combo.AccessPolicyTemplateGroups = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'template_group'
        ,hiddenName: 'template_group'
        ,fields: ['id','name','description']
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Security/Access/Policy/Template/Group/GetList'
        }
        ,tpl: new Ext.XTemplate('<tpl for="."><div class="x-combo-list-item"><span style="font-weight: bold">{name:htmlEncode}</span>'
            ,'<p style="margin: 0; font-size: 11px; color: gray;">{description:htmlEncode}</p></div></tpl>')
    });
    MODx.combo.AccessPolicyTemplateGroups.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.AccessPolicyTemplateGroups,MODx.combo.ComboBox);
Ext.reg('modx-combo-access-policy-template-group',MODx.combo.AccessPolicyTemplateGroups);
