/* override default Ext.Window component properties */
// these also apply for Windows that do not extend MODx.Window (like console for ex.)
// we use CSS3 box-shadows in 2014, removes clutter from the DOM
Ext.Window.prototype.floating = { shadow: false };
/* override default Ext.Window component methods */
Ext.override(Ext.Window, {
    // prevents ugly slow js animations when opening a window
    // we cannot do the CSS3 animations stuff in these overrides, as not all windows are animated!
    // so they just prevent the normal JS animation to take effect
    animShow: function() {
        this.afterShow();

        // some windows (like migx) don't seem to call onShow
        // so we have to do a check here after onShow should have finished
        var win = this; // we need a reference to this for setTimeout
        // wait for onShow to finish and check if the window is already visible then, if not, try to do that
        setTimeout(function() {
            if (!win.el.hasClass('anim-ready')) {
                win.el.addClass('anim-ready');
                setTimeout(function() {
                    if (win.mask !== undefined) {
                        // respect that the mask is not always the same object
                        if (win.mask instanceof Ext.Element) {
                            win.mask.addClass('fade-in');
                        } else {
                            win.mask.el.addClass('fade-in');
                        }
                    }
                    win.el.addClass('zoom-in');
                }, 250);
            }
        }, 300);
    }
    ,animHide: function() {
        this.afterHide();

    }
    ,onShow: function() {
        // skip MODx.msg windows, the animations do not work with them as they are always the same element!
        if (!this.el.hasClass('x-window-dlg')) {
            // first set the class that scales the window down a bit
            // this has to be done after the full window is positioned correctly by extjs
            this.addClass('anim-ready');
            // let the scale transformation to 0.7 finish before animating in
            var win = this; // we need a reference to this for setTimeout
            setTimeout(function() {
                if (win.mask !== undefined) {
                    // respect that the mask is not always the same object
                    if (win.mask instanceof Ext.Element) {
                        win.mask.addClass('fade-in');
                    } else {
                        win.mask.el.addClass('fade-in');
                    }
                }
                win.el.addClass('zoom-in');
            }, 250);
        } else {
            // we need to handle MODx.msg windows (Ext.Msg singletons, e.g. always the same element, no multiple instances) differently
            this.mask.addClass('fade-in');
            this.el.applyStyles({'opacity': 1});
        }
    }
    ,onHide: function() {
        // for some unknown (to me) reason, onHide() get's called when a window is initialized, e.g. before onShow()
        // so we need to prevent the following routine be applied prematurely
        if (this.el.hasClass('zoom-in')) {
            this.el.removeClass('zoom-in');
            if (this.mask !== undefined) {
                // respect that the mask is not always the same object
                if (this.mask instanceof Ext.Element) {
                    this.mask.removeClass('fade-in');
                } else {
                    this.mask.el.removeClass('fade-in');
                }
            }
            this.addClass('zoom-out');
            // let the CSS animation finish before hiding the window
            var win = this; // we need a reference to this for setTimeout
            setTimeout(function() {
                // we have an unsolved problem with windows that are destroyed on hide
                // the zoom-out animation cannot be applied for such windows, as they
                // get destroyed too early, if someone knows a solution, please tell =)
                if (!win.isDestroyed) {
                    win.el.hide();
                    // and remove the CSS3 animation classes
                    win.el.removeClass('zoom-out');
                    win.el.removeClass('anim-ready');
                }
            }, 250);
        } else if (this.el.hasClass('x-window-dlg')) {
            // we need to handle MODx.msg windows (Ext.Msg singletons, e.g. always the same element, no multiple instances) differently
            this.el.applyStyles({'opacity': 0});

            if (this.mask !== undefined) {
                // respect that the mask is not always the same object
                if (this.mask instanceof Ext.Element) {
                    this.mask.removeClass('fade-in');
                } else {
                    this.mask.el.removeClass('fade-in');
                }
            }
        }
    }
});

/**
 * Abstract class for Ext.Window creation in MODx
 *
 * @class MODx.Window
 * @extends Ext.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype modx-window
 */
MODx.Window = function(config) {
    config = config || {};
    this.isSmallScreen = Ext.getBody().getViewSize().height <= 768;
    /*
        Update boolean modxFbarHas[___]SaveSwitch properties for later use
    */
    if (config.hasOwnProperty('modxFbarSaveSwitches') && config.modxFbarSaveSwitches.length > 0) {
        config.modxFbarSaveSwitches.forEach(saveSwitch => {
            saveSwitch = saveSwitch[0].toUpperCase() + saveSwitch.slice(1);
            const configKey = `modxFbarHas${saveSwitch}Switch`;
            config[configKey] = true;
        });

    }
    /*
        Setup the standard system footer bar if fbar and buttons properties are empty.
        Note that buttons overrides fbar and can be used to specify a customized
        set of window buttons.
    */
    if (!config.hasOwnProperty('fbar') && (!config.hasOwnProperty('buttons') || config.buttons.length == 0)) {
        const footerBar = this.getWindowFbar(config);
        if (footerBar) {
            config.buttonAlign = 'left';
            config.fbar = footerBar;
        }
    }
    Ext.applyIf(config,{
        modal: false

        ,modxFbarHasClearCacheSwitch: false
        ,modxFbarHasDuplicateValuesSwitch: false
        ,modxFbarHasRedirectSwitch: false

        ,modxFbarButtons: config.modxFbarButtons || 'c-s'
        ,modxFbarSaveSwitches: []
        ,modxPseudoModal: false

        ,layout: 'auto'
        ,closeAction: 'hide'
        ,shadow: true
        ,resizable: true
        ,collapsible: true
        ,maximizable: true
        ,autoHeight: false
        ,autoScroll: true
        ,allowDrop: true
        ,width: 400
        ,constrain: true
        ,constrainHeader: true
        ,cls: 'modx-window'
        /*
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { config.closeAction !== 'close' ? this.hide() : this.close(); }
        },{
            text: config.saveBtnText || _('save')
            ,cls: 'primary-button'
            ,scope: this
            ,handler: this.submit
        }]
        */
        ,record: {}
        ,keys: [{
            key: Ext.EventObject.ENTER
            ,fn: function(keyCode, event) {
                    var elem = event.getTarget();
                    var component = Ext.getCmp(elem.id);
                    if (component instanceof Ext.form.TextArea) {
                        return component.append("\n");
                    } else {
                        this.submit();
                    }
                }
            ,scope: this
        }]
        ,tools: [{
            id: 'gear',
            title: 'Window Settings',
            // href: '#'
            menu: {
                xtype: 'menu',
                anchor: true,
                items: [
                    {
                    xtype: 'menucheckitem',
                    text: 'Remove Masks',
                    checked: true
                    // bind: '{indented}'
                }, {
                    text: 'Disabled Item',
                    disabled: true,
                    separator: true
                }
                ]
            }
        }]
    });
    MODx.Window.superclass.constructor.call(this,config);
    this.options = config;
    this.config = config;
    this.addEvents({
        success: true
        ,failure: true
        ,beforeSubmit: true
        ,updateWindow: false
    });
    this._loadForm();
    this.on({
        render: function() {
            console.log('window render, this:', this);
            if (MODx.config.enable_overlays) {
                if (this.modxPseudoModal) {
                    if (MODx.openPseudoModals.length === 0) {
                        MODx.mask = this.container.createChild({cls:'ext-el-mask clickthrough'}, this.el.dom);
                        MODx.mask.setStyle('backgroundColor', overlayCssColorNonblocking);
                        // console.log('render, dynamic mask color: ', overlayCssColorNonblocking);
                        MODx.mask.hide();
                        MODx.mask.resizeMask = function() {
                            // console.log('window resized!');
                            MODx.mask.setSize(Ext.lib.Dom.getViewWidth(true), Ext.lib.Dom.getViewHeight(true));
                        };
                        // console.log('custom mask el: ', MODx.mask);
                        window.addEventListener('resize', MODx.mask.resizeMask);
                    }
                    MODx.openPseudoModals.push({
                        modalId: this.itemId
                    });
                    // console.log('open modxPseudoModals: ',MODx.openPseudoModals);
                }
                if (this.modal) {
                    console.log('rendering real modal...');
                }
            }
        },
        afterrender: function() {
            this.originalHeight = this.el.getHeight();
            this.toolsHeight = this.originalHeight - this.body.getHeight() + 50;
            this.resizeWindow();
        },
        beforeShow: function() {
            if (this.modxPseudoModal && !MODx.mask.isVisible()) {
                Ext.getBody().addClass('x-body-masked');
                MODx.mask.setSize(Ext.lib.Dom.getViewWidth(true), Ext.lib.Dom.getViewHeight(true));
                MODx.mask.show();
            }
        },
        show: function() {
            // console.log('showing a modxPseudoModal...');
            // console.log(`modxPseudoModal opacity: ${overlayOpacityNonblocking}`);
            if (this.modxPseudoModal && MODx.mask.isVisible()) {
                setTimeout(function() {
                    MODx.mask.setStyle('opacity', overlayOpacityNonblocking);
                    // MODx.mask.addClass('fade-in');
                }, 250);
            }
            // console.log('show, mask color: ', MODx.mask.getColor('backgroundColor'));
            if (this.config.blankValues) {
                this.fp.getForm().reset();
            }
            if (this.config.allowDrop) {
                this.loadDropZones();
            }
            this.syncSize();
            this.focusFirstField();
        },
        beforehide: function() {
            if (this.modxPseudoModal && MODx.mask && MODx.openPseudoModals.length === 1) {
                MODx.mask.removeClass('fade-in');
            }
        },
        hide: function() {
            if (this.modxPseudoModal) {
                if (MODx.openPseudoModals.length > 1) {
                    MODx.openPseudoModals.forEach((modxPseudoModal, i) => {
                        if (modxPseudoModal.modalId == this.itemId) {
                            MODx.openPseudoModals.splice(i, 1);
                        }
                    });
                } else {
                    MODx.openPseudoModals = [];
                    MODx.mask.hide();
                    MODx.mask.remove();
                    Ext.getBody().removeClass('x-body-masked');
                    window.removeEventListener('resize', MODx.mask.resizeMask);
                }
                // console.log('hide, openPseudoModals: ', MODx.openPseudoModals);
            }
        }
    });
    Ext.EventManager.onWindowResize(this.resizeWindow, this);
};
Ext.extend(MODx.Window,Ext.Window,{
    _loadForm: function() {
        if (this.checkIfLoaded(this.config.record || null)) { return false; }

        var r = this.config.record;
        /* set values here, since setValue after render seems to be broken */
        if (this.config.fields) {
            var l = this.config.fields.length;
            for (var i=0;i<l;i++) {
                var f = this.config.fields[i];
                if (r[f.name]) {
                    if (f.xtype == 'checkbox' || f.xtype == 'radio') {
                        f.checked = r[f.name];
                    } else {
                        f.value = r[f.name];
                    }
                }
            }
        }

        /*
            When a switch is rendered in the footer bar, we need to
            insert a hidden field in the form to to be able to relay its value to
            the processor
        */
        if (this.config.hasOwnProperty('modxFbarSaveSwitches') && this.config.modxFbarSaveSwitches.length > 0) {
            this.config.modxFbarSaveSwitches.forEach(saveSwitch => {
                switch (saveSwitch) {
                    case 'redirect':
                        defaultValue = this.config.redirect ;
                        break;
                    case 'duplicateValues':
                        defaultValue = 0;
                        break;
                    default:
                        defaultValue = 1;

                }
                this.setFbarSwitchHiddenField(saveSwitch, defaultValue);
            });

        }
        console.log('final fields: ', this.config.fields);
        /*
        if (this.modxFbarHasClearCacheSwitch) {
            // console.log('adding hidden cache switch...');
            const   switchId = `${this.id}-clearcache`,
                    switchCmp = Ext.getCmp(switchId)
            ;
            if (switchCmp) {
                this.config.fields.push({
                    xtype: 'hidden'
                    ,name: 'clearCache'
                    ,id: `${switchId}-hidden`
                    ,value: 1
                });
            }
        }
        if (this.modxFbarHasRedirectSwitch) {
            // console.log('adding hidden redirect switch..., default val: ',this.config.redirect);
            const   switchId = `${this.id}-redirect`,
                    switchCmp = Ext.getCmp(switchId)
            ;
            if (switchCmp) {
                this.config.fields.push({
                    xtype: 'hidden'
                    ,name: 'redirect'
                    ,id: `${switchId}-hidden`
                    ,value: this.config.redirect ? 1 : 0
                });
            }
        }
        */
        this.fp = this.createForm({
            url: this.config.url
            ,baseParams: this.config.baseParams || { action: this.config.action || '' }
            ,items: this.config.fields || []
        });
        var w = this;
        this.fp.getForm().items.each(function(f) {
            f.on('invalid', function(){
                w.doLayout();
            });
        });
        this.renderForm();
    }

    ,focusFirstField: function() {
        if (this.fp && this.fp.getForm() && this.fp.getForm().items.getCount() > 0) {
            var fld = this.findFirstTextField();
            if (fld) { fld.focus(false,200); }
        }
    }

    ,findFirstTextField: function(i) {
        i = i || 0;
        var fld = this.fp.getForm().items.itemAt(i);
        if (!fld) return false;
        if (fld.isXType('combo') || fld.isXType('checkbox') || fld.isXType('radio') || fld.isXType('displayfield') || fld.isXType('statictextfield') || fld.isXType('hidden')) {
            i = i+1;
            fld = this.findFirstTextField(i);
        }
        return fld;
    }

    ,submit: function(close) {
        close = close === false ? false : true;
        var f = this.fp.getForm();
        if (f.isValid() && this.fireEvent('beforeSubmit',f.getValues())) {
            // console.log('window form submit, this:', this);
            // console.log('window form submit, form:', f);
            console.log('window form submit, form vals:', f.getValues());
            // return false;
            f.submit({
                waitMsg: this.config.waitMsg ||  _('saving')
                ,submitEmptyText: this.config.submitEmptyText !== false
                ,scope: this
                ,failure: function(frm,a) {
                    if (this.fireEvent('failure',{f:frm,a:a})) {
                        MODx.form.Handler.errorExt(a.result,frm);
                    }
                    this.doLayout();
                }
                ,success: function(frm,a) {
                    if (this.config.success) {
                        Ext.callback(this.config.success,this.config.scope || this,[frm,a]);
                    }
                    this.fireEvent('success',{f:frm,a:a});
                    if (close) { this.config.closeAction !== 'close' ? this.hide() : this.close(); }
                    this.doLayout();
                }
            });
        }
    }

    ,createForm: function(config) {
        Ext.applyIf(this.config,{
            formFrame: true
            ,border: false
            ,bodyBorder: false
            ,autoHeight: true
        });
        config = config || {};
        Ext.applyIf(config,{
            labelAlign: this.config.labelAlign || 'top'
            ,labelWidth: this.config.labelWidth || 100
            ,labelSeparator: this.config.labelSeparator || ''
            ,frame: this.config.formFrame
            ,border: this.config.border
            ,bodyBorder: this.config.bodyBorder
            ,autoHeight: this.config.autoHeight
            ,anchor: '100% 100%'
            ,errorReader: MODx.util.JSONReader
            ,defaults: this.config.formDefaults || {
                msgTarget: this.config.msgTarget || 'under'
                ,anchor: '100%'
            }
            ,url: this.config.url
            ,baseParams: this.config.baseParams || {}
            ,fileUpload: this.config.fileUpload || false
        });
        return new Ext.FormPanel(config);
    }

    ,renderForm: function() {
        this.fp.on('destroy', function() {
            Ext.EventManager.removeResizeListener(this.resizeWindow, this);
        }, this);
        this.add(this.fp);
    }

    ,checkIfLoaded: function(r) {
        r = r || {};
        if (this.fp && this.fp.getForm()) { /* so as not to duplicate form */
            this.fp.getForm().reset();
            this.fp.getForm().setValues(r);
            return true;
        }
        return false;
    }

    /* @smg6511:
        Suggest moving away from using this bulk setValues method and
        explicitly specifying each field’s value param in window configs,
        as is done for standard form panel pages. This will already have been done
        for the element quick create/edit windows. Also the above value-setting
        procedure in the _loadForm method could be dropped too. All windows in
        windows.js would need to be updated before dropping.
    */
    ,setValues: function(r) {
        if (r === null) { return false; }
        this.fp.getForm().setValues(r);
    }

    ,reset: function() {
        this.fp.getForm().reset();
    }

    ,hideField: function(f) {
        f.disable();
        f.hide();
        var d = f.getEl().up('.x-form-item');
        if (d) { d.setDisplayed(false); }
    }

    ,showField: function(f) {
        f.enable();
        f.show();
        var d = f.getEl().up('.x-form-item');
        if (d) { d.setDisplayed(true); }
    }

    ,loadDropZones: function() {
        if (this._dzLoaded) return false;
        var flds = this.fp.getForm().items;
        flds.each(function(fld) {
            if (fld.isFormField && (
                fld.isXType('textfield') || fld.isXType('textarea')
            ) && !fld.isXType('combo')) {
                new MODx.load({
                    xtype: 'modx-treedrop'
                    ,target: fld
                    ,targetEl: fld.getEl().dom
                });
            }
        });
        this._dzLoaded = true;
    }

    ,resizeWindow: function(){
        var viewHeight = Ext.getBody().getViewSize().height;
        var el = this.fp.getForm().el;
        if(viewHeight < this.originalHeight){
            el.setStyle('overflow-y', 'scroll');
            el.setHeight(viewHeight - this.toolsHeight);
        }else{
            el.setStyle('overflow-y', 'auto');
            el.setHeight('auto');
        }
    }

    /**
     *
     */
    ,setFbarSwitchHiddenField: function(fbarSwitchFieldName, defaultValue = 1) {

        const   switchId = `${this.id}-${fbarSwitchFieldName}`,
                switchCmp = Ext.getCmp(switchId)
        ;
        if (switchCmp) {
            this.config.fields.push({
                xtype: 'hidden',
                name: fbarSwitchFieldName,
                id: `${switchId}-hidden`,
                value: defaultValue
            });
        }
    }

    /**
     *
     */
    ,getFbarSwitch: function(windowId, fbarSwitchFieldName, switchLabel, switchIsChecked = true) {

        const switchCmp = {
            xtype: 'xcheckbox',
            id: `${windowId}-${fbarSwitchFieldName}`,
            hideLabel: true,
            boxLabel: switchLabel,
            inputValue: 1,
            checked: switchIsChecked,
            listeners: {
                check: {
                    fn: function(cmp) {
                        const hiddenCmp = Ext.getCmp(`${windowId}-${fbarSwitchFieldName}-hidden`);
                        if (hiddenCmp) {
                            const value = cmp.getValue() === false ? 0 : 1;
                            hiddenCmp.setValue(value);
                        }
                    },
                    scope: this
                }
            }
        };
        // console.log(`getting switch (${fbarSwitchFieldName}): `, switchCmp);
        return switchCmp;
    }

    /**
     *
     */
    ,getSaveButton: function(config, isPrimaryButton = true, isSaveAndClose = false) {
        // console.log('getSaveButton, this', this);
        const defaultBtnText = isSaveAndClose ? _('save_and_close') : _('save') ;
        let btn;
        if (isPrimaryButton) {
            // console.log('modxFbarButtons: ',config.modxFbarButtons);
            // console.log('isPrimaryButton, config.saveBtnText: ',config.saveBtnText);
            // console.log('isPrimaryButton, isSaveAndClose: ',isSaveAndClose);
            btn = {
                text: config.saveBtnText || defaultBtnText,
                cls: 'primary-button',
                handler: this.submit,
                scope: this
            };
        } else {
            btn = {
                text: config.saveBtnText || defaultBtnText,
                handler: function() {
                    this.submit(false);
                },
                scope: this
            };
        }
        // console.log('getSaveButton, btn:', btn);
        return btn;
    }

    /**
     *
     */
    ,getWindowButtons: function(config) {
        const   btns = [{
                    text: config.cancelBtnText || _('cancel'),
                    handler: function() {
                        this.config.closeAction !== 'close' ? this.hide() : this.close();
                    },
                    scope: this
                }],
                specification = config.modxFbarButtons || 'c-s'
        ;
        switch(specification) {
            case 'c-s':
                btns.push(this.getSaveButton(config));
                break;
            case 'c-s-sc':
                btns.push(this.getSaveButton(config, false));
                btns.push(this.getSaveButton(config, true, true));
                break;
            case 'custom':
                break;
        }
        return btns;
    }

    /**
     *
     */
    ,getWindowFbar: function(config) {
        // console.log('getting window fbar...');
        const   windowId = config.id,
                windowButtons = this.getWindowButtons(config),
                footerBar = []
        ;
        if (config.modxFbarHasClearCacheSwitch) {
            const cacheSwitch = this.getFbarSwitch(windowId, 'clearCache', _('clear_cache_on_save'));
            footerBar.push(cacheSwitch);
        }
        if (config.modxFbarHasDuplicateValuesSwitch) {
            const dupValuesSwitch = this.getFbarSwitch(windowId, 'duplicateValues', _('element_duplicate_values'), false);
            footerBar.push(dupValuesSwitch);
        }
        if (config.modxFbarHasRedirectSwitch) {
            const redirectSwitch = this.getFbarSwitch(windowId, 'redirect', _('duplicate_redirect'), config.redirect);
            footerBar.push(redirectSwitch);
        }
        footerBar.push('->');
        if (windowButtons && windowButtons.length > 0) {
            windowButtons.forEach(button => {
                footerBar.push(button);
            });
        }

        return footerBar;
    }

});
Ext.reg('modx-window',MODx.Window);
