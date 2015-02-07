<div id="tvpanel{$tv->id}"></div>

{if $disabled}
<script type="text/javascript">
// <![CDATA[
{literal}
Ext.onReady(function() {
    var fld{/literal}{$tv->id}{literal} = MODx.load({
    {/literal}
        xtype: 'displayfield'
        ,tv: '{$tv->id}'
        ,renderTo: 'tvpanel{$tv->id}'
        ,value: '{$tv->value|escape}'
        ,width: '100%'
        ,msgTarget: 'under'
    {literal}
    });
});
{/literal}
// ]]>
</script>
{else}
<script type="text/javascript">
// <![CDATA[
{literal}
Ext.onReady(function() {
    var fld{/literal}{$tv->id}{literal} = MODx.load({
    {/literal}
        xtype: 'modx-panel-tv-file'
        ,renderTo: 'tvpanel{$tv->id}'
        ,tv: '{$tv->id}'
        ,value: '{$tv->value|escape}'
        ,relativeValue: '{$tv->value|escape}'
        ,width: '100%'
        ,msgTarget: 'under'
        ,allowBlank: {if $params.allowBlank == 1 || $params.allowBlank == 'true'}true{else}false{/if}
        ,source: '{$source}'

        {if $params.allowedFileTypes},allowedFileTypes: '{$params.allowedFileTypes}'{/if}
        ,wctx: '{if $params.wctx}{$params.wctx}{else}web{/if}'
        {if $params.openTo},openTo: '{$params.openTo|replace:"'":"\\'"}'{/if}

    {literal}
        ,listeners: { 'select': { fn:MODx.fireResourceFormChange, scope:this}
                    ,afterrender: {
	            fn: function(data) {
            		data.doMagic();
	            	Ext.getCmp('modx-content').on('resize', function() { data.doMagic(); }, data);
	            }
	            ,scope: this
	        }
        }
        ,doMagic: function() {
        
        	Ext.defer(function() {
	    		var desiredWidth = this.container.getWidth();
	        	this.el.setWidth(desiredWidth);
	        	this.doLayout();
        	}, 250, this);
        }

    });
    MODx.makeDroppable(Ext.get('tvpanel{/literal}{$tv->id}{literal}'),function(v) {
        var cb = Ext.getCmp('tvbrowser{/literal}{$tv->id}{literal}');
        if (cb) {
            cb.setValue(v);
            cb.fireEvent('select',{relativeUrl:v});
        }
        return '';
    });
});
{/literal}
// ]]>
</script>
{/if}
