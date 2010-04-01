/**
 * @class MODx.panel.PackageDownload
 * @extends MODx.Panel
 * @param {Object} config An object of config properties
 * @xtype modx-panel-package-download
 */
MODx.panel.PackageDownload = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        border: false
        ,id: 'modx-panel-package-download'
        ,layout: 'column'
        ,border: true
        ,autoHeight: true
        ,anchor: '97%'
        ,autoScroll: true
        ,hideMode: 'offsets'
        ,items: [{
            xtype: 'modx-tree-package-download'
            ,id: 'modx-tree-package-download'
            ,columnWidth: 0.7
            ,anchor: '60%'
            ,height: 270
            ,autoHeight: false
            ,preventRender: true
            ,hideMode: 'offsets'
        },{
            columnWidth: 0.3
            ,id: 'modx-package-info-col'
            ,height: 270
            ,anchor: '35%'
            ,border: false
            ,autoScroll: true
            ,hideMode: 'offsets'
            ,items: [{
                id: 'modx-panel-package-info'
                ,xtype: 'panel'
                ,style: 'padding: ".5em"'
                ,height: 270
                ,autoScroll: true
                ,html: ''
            }]
        }]
    });
    MODx.panel.PackageDownload.superclass.constructor.call(this,config);
    this.loadTemplates();
    
    var t = Ext.getCmp('modx-tree-package-download');
    t.on('click',function(n,e) {
        var p = Ext.getCmp('modx-panel-package-info');
        var detailEl = p.body;
        if(n && n.attributes){
            var data = n.attributes;
            if (this.tpls[data.type]) {
                detailEl.hide();
                this.tpls[data.type].overwrite(detailEl, data.data || {});
                detailEl.slideIn('l', {stopFx:true,duration:'.2'});
                this.curData = data.data;
            }
        } else {
            detailEl.update('');
        }
    },this);
};
Ext.extend(MODx.panel.PackageDownload,MODx.Panel,{
    tpls: {}
    ,curData: {}
    
    ,showMoreInfo: function(btn) {
        if (!this.mi) {
            this.mi = MODx.load({
                xtype: 'modx-window-package-more-info'
                ,data: this.curData
            });
        }
        this.mi.showTpl(btn,this.curData);        
    }
    ,loadTemplates: function() {
        this.tpls = {
            version: new Ext.XTemplate(
                '<div class="details" style="padding: 1em;">'
                ,'<tpl for=".">'
                    ,'<div class="modx-pb-details-info">'
                    ,'<h3>{name}</h3>'
                    ,'<b>'+_('version')+':</b> <span>{version}</span><br />'
                    ,'<b>'+_('release')+':</b> <span>{release}</span><br />'
                    ,'<b>'+_('released_on')+':</b> <span>{releasedon}</span><br />'
                    ,'<b>'+_('supports')+'</b>: <span>{supports}</span><br /><br />'
                    ,'<a id="pd-version-more-info" href="javascript:;" onclick="Ext.getCmp(\'modx-panel-package-download\').showMoreInfo(this);">'+_('more_info')+'</a><br />'
                    ,'<br /><p>{description}</p>'
                    ,'</div>'
                ,'</tpl>'
                ,'</div>'
            )
            ,'package': new Ext.XTemplate(
                '<div class="details" style="padding: 1em;">'
                ,'<tpl for=".">'
                    ,'<div class="modx-pb-details-info">'
                    ,'<h3>{name}</h3>'
                    ,'<p>{description}</p></div>'
                ,'</tpl></div>'
            )
            ,category: new Ext.XTemplate(
                '<div class="details" style="padding: 1em;">'
                ,'<tpl for=".">'
                    ,'<div class="modx-pb-details-info">'
                    ,'<h3>{name}</h3>'
                    ,'<p>{description}</p></div>'
                ,'</tpl></div>'
            ) 
            ,repository: new Ext.XTemplate(
                '<div class="details" style="padding: 1em;">'
                ,'<tpl for=".">'
                    ,'<div class="modx-pb-details-info">'
                    ,'<h3>{name}</h3>'
                    ,'<p>{description}</p></div>'
                ,'</tpl></div>'
            )
        };
        for (var i in this.tpls) { if (this.tpls[i]) { this.tpls[i].compile(); } }
    }
    
});
Ext.reg('modx-panel-package-download',MODx.panel.PackageDownload);

/**
 * @class MODx.window.PackageMoreInfo
 * @extends MODx.Window
 * @param {Object} config An object of configuration parameters
 * @xtype modx-window-package-more-info
 */
MODx.window.PackageMoreInfo = function(config) {
    config = config || {};
    this.tpl = this.createTpl();
    Ext.applyIf(config,{
        title: _('package_information')
        ,anchor: '95%'
        ,autoHeight: true
        ,url: MODx.config.connectors_url+'workspace/packages.php'
        ,action: 'info'
        ,autoScroll: true
        ,fields: [{
            html: '<div id="modx-pmi-content" style="overflow: auto; width: 100%;"></div>'
            ,autoScroll: true
            ,height: 500
        }]
        ,buttons: [{
            text: _('ok')
            ,handler: function() { this.hide(); }
            ,scope: this
        }]
    });
    MODx.window.PackageMoreInfo.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.PackageMoreInfo,MODx.Window,{
    createTpl: function() {
        this.tpl = new Ext.XTemplate(
            '<div class="details" style="padding: 1em;">'
            ,'<tpl for=".">'
                ,'<div class="pmi-content">'
                ,'<div style="float: right;"><img src="{screenshot}" alt="" width="200" height="134" /></div>'
                ,'<h3>{name}</h3>'
                ,'<tpl if="author">'
                    ,'<i>'+_('by')+' {author}</i><br />'
                ,'</tpl>'
                ,'<span>'+_('released_on')+': {releasedon}</span><br />'
                ,'<span>'+_('license')+': {license}</span><br />'
                ,'<span>'+_('downloads')+': {downloads}</span><br />'
                ,'<span>'+_('supports')+': {supports}</span><br />'
                ,'<br /><h4>'+_('description')+'</h4>'
                ,'<p>{description}</p>'
                ,'<tpl if="instructions">'
                    ,'<br /><h4>'+_('installation_instructions')+'</h4>'
                    ,'<p>{instructions}</p>'
                ,'</tpl>'
                ,'</div>'
            ,'</tpl></div>'
        );
        this.tpl.compile();
        return this.tpl;
    }
    
    ,showTpl: function(btn,data) {
        this.show(btn,function() { 
            this.tpl.overwrite('modx-pmi-content',data);
            this.center();
        },this);
    }
});
Ext.reg('modx-window-package-more-info',MODx.window.PackageMoreInfo);

/**
 * @class MODx.tree.PackageDownload
 * @extends MODx.tree.CheckboxTree
 * @param {Object} config An object of config properties
 * @xtype modx-tree-package-download
 */
MODx.tree.PackageDownload = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        baseParams: {
            action: 'getPackages'
            ,provider: ''
        }
        ,loaderConfig: {
            preloadChildren: false
        }
        ,rootVisible: false
        ,tbar: [{
            icon: MODx.config.template_url+'images/restyle/icons/refresh.png'
            ,cls: 'x-btn-icon'
            ,scope: this
            ,tooltip: {text: _('tree_refresh')}
            ,handler: this.loadDataFromProvider
            ,hideMode: 'offsets'
        }]
    });
    MODx.tree.PackageDownload.superclass.constructor.call(this,config);
    this.on('render',this.setupMask,this);
};
Ext.extend(MODx.tree.PackageDownload,MODx.tree.Tree,{
    setProvider: function(p) {
        var m = this.getLoader().fullMask;
        if (!m) {
            m = this.setupMask();
        }
        m.show();
        this.provider = p;
        this.loadDataFromProvider();
    }
    ,loadDataFromProvider: function() {     
        this.getLoader().fullMask.show();
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'workspace/providers.php'
            ,params: {
                action: 'getPackages'
                ,provider: this.provider
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.loadRemoteData(r.object);
                    this.getLoader().fullMask.hide();
                },scope:this}
            }
        });
    }
    
    ,setupMask: function() {
        var tl = this.getLoader();
        Ext.apply(tl,{fullMask : new Ext.LoadMask(this.getEl(),{msg:_('loading')}) });
        tl.fullMask.removeMask=false;
        tl.on({
            'load' : function(){this.fullMask.hide();}
            ,'loadexception' : function(){this.fullMask.hide();}
            ,'beforeload' : function(){this.fullMask.show();}
            ,scope : tl
        });
        return tl.fullMask;
    }
});
Ext.reg('modx-tree-package-download',MODx.tree.PackageDownload);