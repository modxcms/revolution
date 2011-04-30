<div id="tv-input-properties-form{$tv}"></div>
{literal}

<script type="text/javascript">
// <![CDATA[
var params = {
{/literal}{foreach from=$params key=k item=v name='p'}
 '{$k}': '{$v|escape:"javascript"}'{if NOT $smarty.foreach.p.last},{/if}
{/foreach}{literal}
};
var oc = {'change':{fn:function(){Ext.getCmp('modx-panel-tv').markDirty();},scope:this}};
MODx.load({
    xtype: 'panel'
    ,layout: 'form'
    ,autoHeight: true
    ,labelWidth: 150
    ,border: false
    ,items: [{
        xtype: 'textfield'
        ,fieldLabel: _('image_basepath')
        ,description: _('image_basepath_desc')
        ,name: 'inopt_basePath'
        ,id: 'inopt_basePath{/literal}{$tv}{literal}'
        ,value: params['basePath'] || ''
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'combo-boolean'
        ,fieldLabel: _('image_basepath_relative')
        ,name: 'inopt_basePathRelative'
        ,hiddenName: 'inopt_basePathRelative'
        ,id: 'inopt_basePathRelative{/literal}{$tv}{literal}'
        ,value: params['basePathRelative'] == 0 || params['basePathRelative'] == 'false' ? false : true
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'textfield'
        ,fieldLabel: _('image_baseurl')
        ,description: _('image_baseurl_desc')
        ,name: 'inopt_baseUrl'
        ,id: 'inopt_baseUrl{/literal}{$tv}{literal}'
        ,value: params['baseUrl'] || ''
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'combo-boolean'
        ,fieldLabel: _('image_baseurl_relative')
        ,name: 'inopt_baseUrlRelative'
        ,hiddenName: 'inopt_baseUrlRelative'
        ,id: 'inopt_baseUrlRelative{/literal}{$tv}{literal}'
        ,value: params['baseUrlRelative'] == 0 || params['baseUrlRelative'] == 'false' ? false : true
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'combo-boolean'
        ,fieldLabel: _('image_baseurl_prepend_check_slash')
        ,description: _('image_baseurl_prepend_check_slash_desc')
        ,name: 'inopt_baseUrlPrependCheckSlash'
        ,hiddenName: 'inopt_baseUrlPrependCheckSlash'
        ,id: 'inopt_baseUrlPrependCheckSlash{/literal}{$tv}{literal}'
        ,value: params['baseUrlPrependCheckSlash'] == 0 || params['baseUrlPrependCheckSlash'] == 'false' ? false : true
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'textfield'
        ,fieldLabel: _('image_allowedfiletypes')
        ,description: _('image_allowedfiletypes_desc')
        ,name: 'inopt_allowedFileTypes'
        ,id: 'inopt_allowedFileTypes{/literal}{$tv}{literal}'
        ,value: params['allowedFileTypes'] || ''
        ,width: 300
        ,listeners: oc
    }]
    ,renderTo: 'tv-input-properties-form{/literal}{$tv}{literal}'
});
// ]]>
</script>
{/literal}