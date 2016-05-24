Ext.namespace('MODx.panel');

MODx.Panel = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        cls: 'modx-panel'
        ,title: ''
    });
    MODx.Panel.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx.Panel,Ext.Panel);
Ext.reg('modx-panel',MODx.Panel);

MODx.FormPanel = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        autoHeight: true
        ,collapsible: true
        ,bodyStyle: ''
        ,layout: 'anchor'
        ,border: false
        ,header: false
        ,method: 'POST'
        ,cls: 'modx-form'
        ,allowDrop: true
        ,errorReader: MODx.util.JSONReader
        ,checkDirty: true
        ,useLoadingMask: false
        ,defaults: { collapsible: false ,autoHeight: true, border: false }
    });
    if (config.items) { this.addChangeEvent(config.items); }

    MODx.FormPanel.superclass.constructor.call(this,config);
    this.config = config;

    this.addEvents({
        setup: true
        ,fieldChange: true
        ,ready: true
        ,beforeSubmit: true
        ,success: true
        ,failure: true
        ,save: true
        ,actionNew: true
        ,actionContinue: true
        ,actionClose: true
        ,postReady: true
    });
    this.getForm().addEvents({
        success: true
        ,failure: true
    });
    this.dropTargets = [];
    this.on('ready',this.onReady);
    if (this.config.useLoadingMask) {
        this.on('render', function() {
            this.mask = new Ext.LoadMask(this.getEl());
            this.mask.show();
        });
    }
    if (this.fireEvent('setup',config)) {
        this.clearDirty();
    }
    this.focusFirstField();
};
Ext.extend(MODx.FormPanel,Ext.FormPanel,{
    isReady: false
    ,defaultValues: []
    ,initialized: false

    ,submit: function(o) {
        var fm = this.getForm();
        if (fm.isValid() || o.bypassValidCheck) {
            o = o || {};
            o.headers = {
                'Powered-By': 'MODx'
                ,'modAuth': MODx.siteId
            };
            if (this.fireEvent('beforeSubmit',{
               form: fm
               ,options: o
               ,config: this.config
            })) {
                fm.submit({
                    waitMsg: this.config.saveMsg || _('saving')
                    ,scope: this
                    ,headers: o.headers
                    ,clientValidation: (o.bypassValidCheck ? false : true)
                    ,failure: function(f,a) {
                    	if (this.fireEvent('failure',{
                    	   form: f
                    	   ,result: a.result
                    	   ,options: o
                    	   ,config: this.config
                    	})) {
                            MODx.form.Handler.errorExt(a.result,f);
                    	}
                    }
                    ,success: function(f,a) {
                        if (this.config.success) {
                            Ext.callback(this.config.success,this.config.scope || this,[f,a]);
                        }
                        this.fireEvent('success',{
                            form:f
                            ,result:a.result
                            ,options:o
                            ,config:this.config
                        });
                        this.clearDirty();
                        this.fireEvent('setup',this.config);
                        
                        //get our Active input value and keep focus
                        var lastActiveEle = Ext.state.Manager.get('curFocus');
                        if (lastActiveEle && lastActiveEle != '') {
                            Ext.state.Manager.clear('curFocus');
                            var initFocus = document.getElementById(lastActiveEle);
                            if(initFocus) initFocus.focus();
                        }
                    }
                });
            }
        } else {
            return false;
        }
        return true;
    }

    ,focusFirstField: function() {
        if (this.getForm().items.getCount() > 0) {
            var fld = this.findFirstTextField();
            if (fld) { fld.focus(false,200); }
        }
    }
    ,findFirstTextField: function(i) {
        i = i || 0;
        var fld = this.getForm().items.itemAt(i);
        if (!fld) return false;
        if (fld.isXType('combo') || fld.isXType('checkbox') || fld.isXType('radio') || fld.isXType('displayfield') || fld.isXType('statictextfield') || fld.isXType('hidden')) {
            i = i+1;
            fld = this.findFirstTextField(i);
        }
        return fld;
    }

    ,addChangeEvent: function(items) {
    	if (!items) { return false; }
    	if (typeof(items) == 'object' && items.items) {
            items = items.items;
    	}

        for (var f=0;f<items.length;f++) {
            var cmp = items[f];
            if (cmp.items) {
                this.addChangeEvent(cmp.items);
            } else if (cmp.xtype) {
                if (!cmp.listeners) { cmp.listeners = {}; }
                var ctypes = ['change'];
                cmp.enableKeyEvents = true;
                switch (cmp.xtype) {
                    case 'numberfield':
                    case 'textfield':
                    case 'textarea':
                        ctypes = ['keydown', 'change'];
                        break;
                    case 'checkbox':
                    case 'xcheckbox':
                    case 'radio':
                        ctypes = ['check'];
                        break;
                }
                if (cmp.xtype && cmp.xtype.indexOf('modx-combo') == 0) {
                    ctypes = ['select'];
                }

                var that = this;
                Ext.iterate(ctypes, function(ctype) {
                    if (cmp.listeners[ctype] && cmp.listeners[ctype].fn) {
                        cmp.listeners[ctype] = {fn:that.fieldChangeEvent.createSequence(cmp.listeners[ctype].fn,cmp.listeners[ctype].scope),scope:that}
                    } else {
                        cmp.listeners[ctype] = {fn:that.fieldChangeEvent,scope:that};
                    }
                });
            }
        }
    }

    ,fieldChangeEvent: function(fld,nv,ov,f) {
        if (!this.isReady) { return false; }
        var f = this.config.onDirtyForm ? Ext.getCmp(this.config.onDirtyForm) : this.getForm();
        this.fireEvent('fieldChange',{
            field: fld
            ,nv: nv
            ,ov: ov
            ,form: f
        });
    }

    ,markDirty: function() {
        this.fireEvent('fieldChange');
    }

    ,isDirty: function() {
        var f = this.config.onDirtyForm ? Ext.getCmp(this.config.onDirtyForm) : this.getForm();
    	return f.isDirty();
    }

    ,clearDirty: function() {
        var f = this.config.onDirtyForm ? Ext.getCmp(this.config.onDirtyForm) : this.getForm();
    	return f.clearDirty();
    }

    ,onReady: function(r) {
    	this.isReady = true;
        if (this.config.allowDrop) { this.loadDropZones(); }
        if (this.config.useLoadingMask && this.mask) {
            this.mask.hide();
        }
        this.fireEvent('postReady');
    }

    ,loadDropZones: function() {
        var dropTargets = this.dropTargets;
        var flds = this.getForm().items;
        flds.each(function(fld) {
            if (fld.isFormField && (
                fld.isXType('textfield') || fld.isXType('textarea')
            ) && !fld.isXType('combo')) {
                var el = fld.getEl();
                if (el) {
                    var target = new MODx.load({
                        xtype: 'modx-treedrop'
                        ,target: fld
                        ,targetEl: el.dom
                    });
                    dropTargets.push(target);
                }
            }
        });
    }

    ,getField: function(f) {
        var fld = false;
        if (typeof f == 'string') {
            fld = this.getForm().findField(f);
            if (!fld) { fld = Ext.getCmp(f); }
        }
        return fld;
    }

    ,hideField: function(flds) {
        if (!Ext.isArray(flds)) { flds = flds[flds]; }
        var f;
        for (var i=0;i<flds.length;i++) {
            f = this.getField(flds[i]);
            if (!f) return;
            f.hide();
            var d = f.getEl().up('.x-form-item');
            if (d) { d.setDisplayed(false); }
        }
    }

    ,showField: function(flds) {
        if (!Ext.isArray(flds)) { flds = flds[flds]; }
        var f;
        for (var i=0;i<flds.length;i++) {
            f = this.getField(flds[i]);
            if (!f) return;
            f.enable();
            f.show();
            var d = f.getEl().up('.x-form-item');
            if (d) { d.setDisplayed(true); }
        }
    }

    ,setLabel: function(flds,vals,bp){
        if (!Ext.isArray(flds)) { flds = flds[flds]; }
        if (!Ext.isArray(vals)) { vals = valss[vals]; }
        var f,v;
        for (var i=0;i<flds.length;i++) {
            f = this.getField(flds[i]);

            if (!f) return;
            v = String.format('{0}',vals[i]);
            if (f.xtype == 'checkbox' || f.xtype == 'xcheckbox' || f.xtype == 'radio') {
                f.setBoxLabel(v);
            } else if (f.label) {
                f.label.update(v);
            }
        }
    }

    ,destroy: function() {
        for (var i = 0; i < this.dropTargets.length; i++) {
            this.dropTargets[i].destroy();
        }
        MODx.FormPanel.superclass.destroy.call(this);
    }
});
Ext.reg('modx-formpanel',MODx.FormPanel);

MODx.panel.Wizard = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        layout: 'card'
        ,activeItem: 0
        ,resizable: true
        ,collapsible: true
        ,maximizable: true
        ,autoHeight: true
        ,width: 750
        ,firstPanel: ''
        ,lastPanel: ''
        ,defaults: { border: false }
        ,modal: true
        ,txtFinish: _('finish')
        ,txtNext: _('next')
        ,txtBack: _('back')
        ,bbar: [{
            id: 'pi-btn-bck'
            ,itemId: 'btn-back'
            ,text: config.txtBack || _('back')
            ,handler: this.navHandler.createDelegate(this,[-1])
            ,scope: this
            ,disabled: true
        },{
            id: 'pi-btn-fwd'
            ,itemId: 'btn-fwd'
            ,text: config.txtNext || _('next')
            ,handler: this.navHandler.createDelegate(this,[1])
            ,scope: this
        }]
    });
    MODx.panel.Wizard.superclass.constructor.call(this,config);
    this.config = config;
    this.lastActiveItem = this.config.firstPanel;
    this._go();
};
Ext.extend(MODx.panel.Wizard,Ext.Panel,{
    windows: {}

    ,_go: function() {
        this.getBottomToolbar().items.item(1).setText(this.config.txtNext);
        this.proceed(this.config.firstPanel);
    }

    ,navHandler: function(dir) {
        this.doLayout();
        var a = this.getLayout().activeItem;
        if (dir == -1) {
            this.proceed(a.config.back || a.config.id);
        } else {
            a.submit({
                scope: this
                ,proceed: this.proceed
            });
        }
    }

    ,proceed: function(id) {
        this.doLayout();
        this.getLayout().setActiveItem(id);
        if (id == this.config.firstPanel) {
            this.getBottomToolbar().items.item(0).setDisabled(true);
            this.getBottomToolbar().items.item(1).setText(this.config.txtNext);
        } else if (id == this.config.lastPanel) {
            this.getBottomToolbar().items.item(1).setText(this.config.txtFinish);
        } else {
            this.getBottomToolbar().items.item(0).setDisabled(false);
            this.getBottomToolbar().items.item(1).setText(this.config.txtNext);
        }
    }
});
Ext.reg('modx-panel-wizard',MODx.panel.Wizard);

MODx.panel.WizardPanel = function(config) {
	config = config || {};
	Ext.applyIf(config,{
        wizard: null
        ,checkDirty: false
        ,bodyStyle: 'padding: 3em 3em'
        ,hideMode: 'offsets'
	});
	MODx.panel.WizardPanel.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.WizardPanel,MODx.FormPanel);
Ext.reg('modx-wizard-panel',MODx.panel.WizardPanel);


MODx.PanelSpacer = {
    html: '<br />'
    ,border: false
};

/**
 * A template panel base class
 *
 * @class MODx.TemplatePanel
 * @extends Ext.Panel
 * @param {Object} config An object of options.
 * @xtype modx-template-panel
 */
MODx.TemplatePanel = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		frame:false
		,startingMarkup: '<tpl for=".">'
			+'<div class="empty-text-wrapper"><p>{text}</p></div>'
		+'</tpl>'
		,startingText: 'Loading...'
		,markup: null
		,plain:true
		,border: false
	});
	MODx.TemplatePanel.superclass.constructor.call(this,config);
	this.on('render', this.init, this);
}
Ext.extend(MODx.TemplatePanel,Ext.Panel,{
	init: function(){
		this.defaultMarkup = new Ext.XTemplate(this.startingMarkup, { compiled: true });
		this.reset();
		this.tpl = new Ext.XTemplate(this.markup, { compiled: true });
	}

	,reset: function(){
		this.body.hide();
		this.defaultMarkup.overwrite(this.body, {text: this.startingText});
		this.body.slideIn('r', {stopFx:true, duration:.2});
		setTimeout(function(){
			Ext.getCmp('modx-content').doLayout();
		}, 500);
	}

	,updateDetail: function(data) {
		this.body.hide();
		this.tpl.overwrite(this.body, data);
		this.body.slideIn('r', {stopFx:true, duration:.2});
		setTimeout(function(){
			Ext.getCmp('modx-content').doLayout();
		}, 500);
	}
});
Ext.reg('modx-template-panel',MODx.TemplatePanel);

/**
 * A breacrumb builder + the panel desc if necessary
 *
 * @class MODx.BreadcrumbsPanel
 * @extends Ext.Panel
 * @param {Object} config An object of options.
 * @xtype modx-breadcrumbs-panel
 */
MODx.BreadcrumbsPanel = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		frame:false
		,plain:true
		,border: false
		,desc: 'This the description part of this panel'
		,bdMarkup: '<tpl if="typeof(trail) != &quot;undefined&quot;">'
			+'<div class="crumb_wrapper"><ul class="crumbs">'
				+'<tpl for="trail">'
					+'<li{[values.className != undefined ? \' class="\'+values.className+\'"\' : \'\' ]}>'
						+'<tpl if="typeof pnl != \'undefined\'">'
							+'<button type="button" class="controlBtn {pnl}{[values.root ? \' root\' : \'\' ]}">{text}</button>'
						+'</tpl>'
                        +'<tpl if="typeof install != \'undefined\'">'
							+'<button type="button" class="controlBtn install{[values.root ? \' root\' : \'\' ]}">{text}</button>'
						+'</tpl>'
						+'<tpl if="typeof pnl == \'undefined\' && typeof install == \'undefined\'"><span class="text{[values.root ? \' root\' : \'\' ]}">{text}</span></tpl>'
					+'</li>'
				+'</tpl>'
			+'</ul></div>'
		+'</tpl>'
		+'<tpl if="typeof(text) != &quot;undefined&quot;">'
			+'<div class="panel-desc{[values.className != undefined ? \' \'+values.className+\'"\' : \'\' ]}"><p>{text}</p></div>'
		+'</tpl>'
		,root : {
			text : 'Home'
			,className: 'first'
			,root: true
			,pnl: ''
		}
		,bodyCssClass: 'breadcrumbs'
	});
	MODx.BreadcrumbsPanel.superclass.constructor.call(this,config);
	this.on('render', this.init, this);
}

Ext.extend(MODx.BreadcrumbsPanel,Ext.Panel,{
    data: {trail: []}

	,init: function(){
		this.tpl = new Ext.XTemplate(this.bdMarkup, { compiled: true });
		this.reset(this.desc);

        this.body.on('click', this.onClick, this);
	}

	,getResetText: function(srcInstance){
		if(typeof(srcInstance) != 'object' || srcInstance == null){
			return srcInstance;
		}
		var newInstance = srcInstance.constructor();
		for(var i in srcInstance){
			newInstance[i] = this.getResetText(srcInstance[i]);
		}
		//The trail is not a link
		if(newInstance.hasOwnProperty('pnl')){
			delete newInstance['pnl'];
		}
		return newInstance;
	}

	,updateDetail: function(data){
        this.data = data;
		// Automagically the trail root
		if(data.hasOwnProperty('trail')){
			var trail = data.trail;
			trail.unshift(this.root);
		}
		this._updatePanel(data);
	}

    ,getData: function() {
        return this.data;
    }
    
	,reset: function(msg){
		if(typeof(this.resetText) == "undefined"){
			this.resetText = this.getResetText(this.root);
		}	
		this.data = { text : msg ,trail : [this.resetText] };
		this._updatePanel(this.data);
	}	
	
	,onClick: function(e){
		var target = e.getTarget();

        var index = 1;
        var parent = target.parentElement;
        while ((parent = parent.previousSibling) != null) {
            index += 1;
        }

        var remove = this.data.trail.length - index;
        while (remove > 0) {
            this.data.trail.pop();
            remove -= 1;
        }

		elm = target.className.split(' ')[0];
		if(elm != "" && elm == 'controlBtn'){
			// Don't use "pnl" shorthand, it make the breadcrumb fail
			var panel = target.className.split(' ')[1];

            if (panel == 'install') {
                var last = this.data.trail[this.data.trail.length - 1];
                if (last != undefined && last.rec != undefined) {
                    this.data.trail.pop();
                    var grid = Ext.getCmp('modx-package-grid');
                    grid.install(last.rec);
                    return;
                }
            } else {
			    Ext.getCmp(panel).activate();
            }
		}
	}

	,_updatePanel: function(data){
		this.body.hide();
		this.tpl.overwrite(this.body, data);
		this.body.slideIn('r', {stopFx:true, duration:.2});
		setTimeout(function(){
			Ext.getCmp('modx-content').doLayout();
		}, 500);
	}
});
Ext.reg('modx-breadcrumbs-panel',MODx.BreadcrumbsPanel);
