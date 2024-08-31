/* override default Ext.Window component properties */
// these also apply for Windows that do not extend MODx.Window (like console for ex.)
// we use CSS3 box-shadows in 2014, removes clutter from the DOM
Ext.Window.prototype.floating = { shadow: false };

Ext.override(Ext.Window, {
    animShow: function() {
        this.afterShow();
    },
    animHide: function() {
        this.afterHide();
    },
    render: function(...args) {
        // ...args contains only one arg at index 0 - a ref to the body el
        // console.log('Ext.Window (base class) render... is modal?', this.modal);
        this.on({
            beforeshow: function() {
                const
                    window = this,
                    windowType = this.getWindowType(window),
                    maskObject = windowType === 'modal' ? this.mask : MODx.mask,
                    showMask = !MODx.maskConfig.getMaskAttribute(windowType, 'disabled')
                ;
                // console.log(`SHOW : ${showMask}`);
                if (showMask && windowType === 'modal') {
                    this.mask.addClass('modal');
                    maskObject.dom.style.backgroundColor = MODx.maskConfig.getMaskAttribute(windowType, 'color');
                }
                this.el.addClass('anim-ready');
                setTimeout(() => {
                    if (showMask) {
                        this.toggleMask(windowType, maskObject);
                    }
                    window.el.addClass('zoom-in');
                }, 250);
            },
            beforehide: function() {
                if (this.el.hasClass('zoom-in')) {
                    const
                        window = this,
                        windowType = this.getWindowType(window),
                        maskObject = windowType === 'modal' ? this.mask : MODx.mask,
                        hideMask = window.id !== 'modx-window-configure-mask'
                            && (windowType === 'modal'
                            || (windowType === 'pseudomodal'
                                && MODx.openPseudoModals.length <= 1)
                            )
                    ;
                    // console.log(`beforehide :: Hiding a ${windowType} window...\nArgs:`, arguments, '\nOpen modals:', MODx.openPseudoModals);

                    this.el.removeClass('zoom-in');
                    this.el.addClass('zoom-out');
                    if (hideMask) {
                        this.toggleMask(windowType, maskObject, 'hide');
                    }
                    this.hidden = true;
                    setTimeout(() => {
                        if (!this.isDestroyed) {
                            window.el.removeClass('zoom-out');
                            window.el.removeClass('anim-ready');
                            window.el.hide();
                            window.afterHide();
                            if (hideMask) {
                                Ext.getBody().removeClass('x-body-masked');
                            }
                        }
                    }, 250);
                }
                return false;
            }
        });
        Ext.Window.superclass.render.call(this, ...args);
    },

    /**
     * 
     * @param {*} window 
     * @returns 
     */
    getWindowType: function(window) {
        return window.el.hasClass('x-window-dlg') || window.modal === true ? 'modal' : 'pseudomodal';
    },

    /**
     * Controls the visibility of the masking element by applying or removing a specified css selector
     * @param {String} windowType Type of window being worked with (i.e., 'modal' or 'pseudomodal')
     * @param {Ext.Element|Object} maskElement
     * @param {String} action The toggle state being applied: 'show' or 'hide'
     */
    toggleMask: function(windowType, maskElement, action = 'show') {
        if (maskElement === undefined) {
            return;
        }
        const
            targetElement = maskElement instanceof Ext.Element ? maskElement : maskElement?.el
        ;
        if (targetElement) {
            if (action === 'hide') {
                // console.log('1 :: toggle targetEl:', targetElement);
                targetElement.dom.style.removeProperty('opacity');
                setTimeout(() => {
                    // console.log('2 :: toggle targetEl:', targetElement);
                    /*
                        Sometimes an empty Ext.Element (with only an id) will be present
                        by the time this runs, so ensure we only try to hide Elements that
                        can be hidden to avoid errors
                    */
                    if (Object.hasOwn(targetElement, 'dom')) {
                        targetElement.hide();
                    }
                }, 1000);
            } else if (this.id !== 'modx-window-status-modal') {
                // console.log('Showing status win mask');
                targetElement.dom.style.opacity = MODx.maskConfig.getMaskAttribute(windowType, 'opacity');
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
MODx.Window = function(config = {}) {
    this.isSmallScreen = Ext.getBody().getViewSize().height <= 768;
    /*
        Update boolean modxFbarHas[___]SaveSwitch properties for later use
    */
    if (Object.hasOwn(config, 'modxFbarSaveSwitches') && config.modxFbarSaveSwitches.length > 0) {
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
    if (!Object.hasOwn(config, 'fbar') && (!Object.hasOwn(config, 'buttons') || config.buttons.length === 0)) {
        const footerBar = this.getWindowFbar(config);
        if (footerBar) {
            config.buttonAlign = 'left';
            config.fbar = footerBar;
        }
    }
    Ext.applyIf(config, {
        modal: false,

        modxFbarHasClearCacheSwitch: false,
        modxFbarHasDuplicateValuesSwitch: false,
        modxFbarHasRedirectSwitch: false,

        modxFbarButtons: config.modxFbarButtons || 'c-s',
        modxFbarSaveSwitches: [],
        /*
            Windows are pseudomodal by default unless:
            1] a config value is passed
            2] the window's modal property is set to true
        */
        modxPseudoModal: Ext.isBoolean(config.modxPseudoModal) ? config.modxPseudoModal : !config.modal,

        layout: 'auto',
        closeAction: 'hide',
        shadow: true,
        resizable: true,
        collapsible: true,
        maximizable: true,
        autoHeight: false,
        autoScroll: true,
        allowDrop: true,
        width: 400,
        constrain: true,
        constrainHeader: true,
        cls: 'modx-window',
        record: {},
        keys: [
            {
                key: Ext.EventObject.ENTER,
                fn: function(keyCode, event) {
                    const
                        elem = event.getTarget(),
                        component = Ext.getCmp(elem.id)
                    ;
                    if (component instanceof Ext.form.TextArea) {
                        return component.append('\n');
                    }
                    this.submit();
                },
                scope: this
            }, {
                key: Ext.EventObject.RIGHT,
                alt: true,
                handler: function(keyCode, event) {
                    console.log('Alt right');
                    if (MODx.openPseudoModals.length > 1) {
                        console.log('Key shortcut :: focus next window...');
                    }
                },
                scope: this
            }, {
                key: Ext.EventObject.LEFT,
                alt: true,
                handler: function(keyCode, event) {
                    console.log('Alt left');
                    if (MODx.openPseudoModals.length > 1) {
                        console.log('Key shortcut :: focus prev window...');
                    }
                },
                scope: this
            }
        ],
        tools: [{
            id: 'gear',
            title: _('mask_toolbar_tool_title'),
            qtip: _('mask_toolbar_tool_qtip'),
            handler: function(evt, toolEl, panel, toolConfig) {
                const targetWindowType = panel.getWindowType(panel);
                let
                    configWindow = Ext.getCmp('modx-window-configure-mask'),
                    mask = document.querySelector(`.ext-el-mask.${targetWindowType}`)
                ;
                const
                    isDisabled = MODx.maskConfig.getMaskAttribute(targetWindowType, 'disabled'),
                    maskStyles = mask && !isDisabled ? window.getComputedStyle(mask) : null,
                    opacity = maskStyles
                        ? maskStyles.opacity
                        : MODx.maskConfig.getMaskAttribute(targetWindowType, 'opacity'),
                    bgColor = maskStyles
                        ? MODx.util.Color.rgbToHex(maskStyles.backgroundColor)
                        : MODx.maskConfig.getMaskAttribute(targetWindowType, 'color'),
                    onColorInput = e => {
                        mask.style.backgroundColor = e.target.value;
                    },
                    onOpacityInput = e => {
                        mask.style.opacity = e.target.value / 100;
                    },
                    setFieldsDisabled = (fieldMap, disabled = true, selectAll = false) => {
                        const filterList = [
                            'modx-mask-settings--opacity',
                            'modx-mask-settings--color'
                        ];
                        Object.keys(fieldMap).forEach(fieldKey => {
                            if (selectAll === true || filterList.includes(fieldKey)) {
                                fieldMap[fieldKey].setDisabled(disabled);
                            }
                        });
                    },
                    /**
                     * Controlled destruction of window needed to allow animation to work properly
                     */
                    dismiss = windowCmp => {
                        if (windowCmp instanceof MODx.Window) {
                            const
                                colorInput = document.getElementById('modx-mask-color'),
                                opacityInput = document.getElementById('modx-mask-opacity')
                            ;
                            colorInput.removeEventListener('input', onColorInput);
                            opacityInput.removeEventListener('input', onOpacityInput);
                            windowCmp.hide();
                            setTimeout(() => windowCmp.destroy(), 250);
                        }
                    }
                ;
                if (!configWindow) {
                    configWindow = new MODx.Window({
                        title: _('mask_config_window_title'),
                        width: panel.width - 100,
                        id: 'modx-window-configure-mask',
                        cls: 'modx-window configure',
                        modxPseudoModal: false,
                        autoHeight: true,
                        fields: [
                            {
                                xtype: 'checkbox',
                                itemId: 'modx-mask-settings--disabled',
                                boxLabel: _('mask_config_field_disabled'),
                                description: MODx.expandHelp ? '' : _('mask_config_field_disabled_desc'),
                                checked: isDisabled,
                                listeners: {
                                    check: function(cmp, checked) {
                                        const
                                            form = cmp.ownerCt.getForm(),
                                            fields = form.items.map
                                        ;
                                        if (checked) {
                                            mask.style.opacity = 0;
                                            setFieldsDisabled(fields);
                                        } else {
                                            if (!mask) {
                                                const maskCmp = MODx.maskConfig.createMask(panel, targetWindowType, 'configure');
                                                mask = maskCmp.dom;
                                                maskCmp.show();
                                            }
                                            mask.style.opacity = opacity;
                                            setFieldsDisabled(fields, false, true);
                                        }
                                    }
                                }
                            },
                            {
                                xtype: 'box',
                                hidden: !MODx.expandHelp,
                                html: _('mask_config_field_disabled_desc'),
                                cls: 'desc-under toggle-slider-above'
                            },
                            {
                                xtype: 'textfield',
                                itemId: 'modx-mask-settings--opacity',
                                id: 'modx-mask-opacity',
                                inputType: 'range',
                                fieldLabel: _('mask_config_field_opacity'),
                                min: 5,
                                max: 95,
                                step: 5,
                                disabled: isDisabled,
                                value: opacity <= 1 ? opacity * 100 : opacity
                            },
                            {
                                xtype: 'textfield',
                                itemId: 'modx-mask-settings--color',
                                id: 'modx-mask-color',
                                inputType: 'color',
                                fieldLabel: _('mask_config_field_color'),
                                enableKeyEvents: true,
                                disabled: isDisabled,
                                value: bgColor
                            },
                            {
                                xtype: 'checkbox',
                                itemId: 'modx-mask-settings--set-user',
                                boxLabel: 'Update User Settings',
                                description: MODx.expandHelp ? '' : _('mask_config_field_update_user_desc'),
                                disabled: isDisabled
                            },
                            {
                                xtype: 'box',
                                hidden: !MODx.expandHelp,
                                html: _('mask_config_field_update_user_desc'),
                                cls: 'desc-under toggle-slider-above'
                            },
                            {
                                xtype: 'checkbox',
                                itemId: 'modx-mask-settings--set-global',
                                boxLabel: _('mask_config_field_update_global'),
                                description: MODx.expandHelp ? '' : _('mask_config_field_update_global_desc'),
                                disabled: isDisabled
                            },
                            {
                                xtype: 'box',
                                hidden: !MODx.expandHelp,
                                html: _('mask_config_field_update_global_desc'),
                                cls: 'desc-under toggle-slider-above'
                            }
                        ],
                        buttons: [
                            {
                                text: _('cancel'),
                                handler: function(btn, e) {
                                    mask.style.backgroundColor = MODx.maskConfig.getMaskAttribute(targetWindowType, 'color');
                                    mask.style.opacity = isDisabled ? 0 : MODx.maskConfig.getMaskAttribute(targetWindowType, 'opacity');
                                    dismiss(configWindow);
                                }
                            },
                            {
                                text: _('save'),
                                cls: 'primary-button',
                                handler: function(btn, e) {
                                    const
                                        form = configWindow.fp.getForm(),
                                        fields = form.items,
                                        values = {
                                            disabled: Boolean(fields.map['modx-mask-settings--disabled'].getValue()),
                                            color: MODx.util.Color.rgbToHex(fields.map['modx-mask-settings--color'].getValue()),
                                            opacity: fields.map['modx-mask-settings--opacity'].getValue() / 100
                                        },
                                        saveToGlobalSettings = Boolean(fields.map['modx-mask-settings--set-global'].getValue()),
                                        saveToUserSettings = Boolean(fields.map['modx-mask-settings--set-user'].getValue())
                                    ;
                                    if (!saveToGlobalSettings && !saveToUserSettings) {
                                        /*
                                          - Show confirm window stating changes only last for session,
                                            with a 'Do not show this warning again' checkbox (persisted
                                            in a localStorage item).
                                          - Will need to check two condiditions (depends on if user is sudo
                                            or primary (id = 1) user, where both save switches will be available)
                                        */
                                        console.log('Let’s show a dialog confirming changes will be lost at end of session...');
                                    }
                                    MODx.maskConfig.updateSessionConfig(targetWindowType, values);
                                    if (saveToGlobalSettings || saveToUserSettings) {
                                        let settingsTarget;
                                        if (saveToGlobalSettings && saveToUserSettings) {
                                            settingsTarget = 'both';
                                        } else {
                                            settingsTarget = saveToGlobalSettings ? 'global' : 'user';
                                        }
                                        MODx.maskConfig.updateSystemSettings(targetWindowType, settingsTarget, values, MODx.config.user);
                                    }
                                    dismiss(configWindow);
                                }
                            }
                        ],
                        tools: [{
                            id: 'close',
                            handler: function() {
                                dismiss(configWindow);
                            }
                        }],
                        listeners: {
                            afterrender: function(cmp) {
                                const { tools } = cmp;
                                if (tools) {
                                    Object.keys(tools).forEach(tool => {
                                        if (tool !== 'close') {
                                            tools[tool].hide();
                                        }
                                    });
                                }
                            }
                        },
                        onEsc: function() {
                            dismiss(configWindow);
                        }
                    });
                    configWindow.show(evt.target);
                    // console.log('config win close action: ', configWindow.closeAction);
                }
                configWindow.toFront();
                /*
                    Show live adjustments to mask settings

                    Note: Setting up listeners here and not on the opacity and color Ext components
                    above because we need to listen for the 'input' event (which is not defined in Ext 3.4)
                    for range and color types. While we could extend the textfield (or its base) component
                    to define/add that listener for global use, electing to keep it simple here.
                */
                const
                    colorInput = document.getElementById('modx-mask-color'),
                    opacityInput = document.getElementById('modx-mask-opacity')
                ;
                colorInput.addEventListener('input', onColorInput);
                opacityInput.addEventListener('input', onOpacityInput);
            }
        }]
    });
    MODx.Window.superclass.constructor.call(this, config);
    this.options = config;
    this.config = config;
    this.addEvents({
        success: true,
        failure: true,
        beforeSubmit: true
    });
    this._loadForm();
    this.on({
        render: function() {
            if (this.modxPseudoModal && !MODx.maskConfig.getMaskAttribute('pseudomodal', 'disabled')) {
                MODx.maskConfig.createMask(this, 'pseudomodal', 'render', false);
            }
        },
        afterrender: function() {
            this.originalHeight = this.el.getHeight();
            this.toolsHeight = this.originalHeight - this.body.getHeight() + 50;
            this.resizeWindow();
        },
        beforeShow: function() {
            if (this.modxPseudoModal && !MODx.util.isEmptyObject(MODx.mask) && !MODx.mask?.isVisible()) {
                Ext.getBody().addClass('x-body-masked');
                MODx.mask.show();
            }
        },
        show: function() {
            if (this.modxPseudoModal) {
                this.registerPseudomodal(this);
            }
            if (this.config.blankValues) {
                this.fp.getForm().reset();
            }
            if (this.config.allowDrop) {
                this.loadDropZones();
            }
            this.syncSize();
            this.focusFirstField();
        },
        hide: function() {
            if (this.modxPseudoModal) {
                this.unregisterPseudomodal(this.getWindowIdentifier());
            }
            /*
                Re-focus one of the open windows, if any, so the esc key
                can be used to close each successive open window

                TODO: Track all non-dialog modals in obj that will replace
                MODx.openPseudoModals; it should take the shape of -
                ###
                MODx.openModals = {
                    pseudo: [
                        {
                            windowId: stringid,
                            window: windowObj
                        },
                        ...
                    ],
                    // Note: There can only be one standard modal open at a time
                    // A single configuration and/or dialog modal may coexist on top of either the standard or pseudo
                    standard: [
                        {
                            windowId: stringid,
                            window: windowObj
                        }
                    ]
                }
                ###
            */
            if (MODx.openPseudoModals.length > 0) {
                console.log('Bringing first pseudomodal to front...', MODx.openPseudoModals);
                MODx.openPseudoModals[0].window.toFront();
            }
        },
        destroy: function() {
            if (this.modxPseudoModal) {
                this.unregisterPseudomodal(this.getWindowIdentifier());
            }
        }
    });
    Ext.EventManager.onWindowResize(this.resizeWindow, this);
};
Ext.extend(MODx.Window, Ext.Window, {
    _loadForm: function() {
        if (this.checkIfLoaded(this.config.record || null)) {
            console.log('Form already loaded');
            return false;
        }

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
        if (Object.hasOwn(this.config, 'modxFbarSaveSwitches') && this.config.modxFbarSaveSwitches.length > 0) {
            // console.log('We have footer bar switches to build!');
            this.config.modxFbarSaveSwitches.forEach(saveSwitch => {
                let defaultValue = 1;
                // console.log('saveSwitch: ', saveSwitch);
                switch (saveSwitch) {
                    case 'redirect':
                        defaultValue = this.config.redirect === false ? 0 : 1;
                        break;
                    case 'duplicateValues':
                        defaultValue = 0;
                        break;
                    // no default
                }
                this.setFbarSwitchHiddenField(saveSwitch, defaultValue);
            });
        }
        // console.log('final fields: ', this.config.fields);
        this.fp = this.createForm({
            url: this.config.url,
            baseParams: this.config.baseParams || { action: this.config.action || '' },
            items: this.config.fields || []
        });
        var w = this;
        this.fp.getForm().items.each(function(f) {
            f.on('invalid', function(){
                w.doLayout();
            });
        });
        this.renderForm();
    },

    focusFirstField: function() {
        if (this.fp && this.fp.getForm() && this.fp.getForm().items.getCount() > 0) {
            var fld = this.findFirstTextField();
            if (fld) { fld.focus(false, 200); }
        }
    },

    findFirstTextField: function(i) {
        i = i || 0;
        var fld = this.fp.getForm().items.itemAt(i);
        if (!fld) { return false; }
        if (fld.isXType('combo') || fld.isXType('checkbox') || fld.isXType('radio') || fld.isXType('displayfield') || fld.isXType('statictextfield') || fld.isXType('hidden')) {
            i = i+1;
            fld = this.findFirstTextField(i);
        }
        return fld;
    },

    submit: function(closeOnSuccess) {
        const
            close = closeOnSuccess !== false,
            f = this.fp.getForm()
        ;
        if (f.isValid() && this.fireEvent('beforeSubmit', f.getValues())) {
            const
                exitDelay = 150,
                status = new MODx.window.SaveProgress({ exitDelay })
            ;
            status.init();
            f.submit({
                submitEmptyText: this.config.submitEmptyText !== false,
                scope: this,
                failure: function(frm, a) {
                    /*
                        Need to allow time for the status window to finish
                        closing, otherwise it becomes unreachable when the
                        error message alert is shown (and even after it is dismissed)
                    */
                    setTimeout(() => {
                        if (this.fireEvent('failure', {
                            f: frm,
                            a: a
                        })) {
                            status.exit('failure');
                            setTimeout(() => {
                                MODx.form.Handler.errorExt(a.result, frm);
                            }, exitDelay);
                        }
                        this.doLayout();
                    }, exitDelay);
                },
                success: function(frm, a) {
                    if (this.config.success) {
                        Ext.callback(this.config.success, this.config.scope || this, [frm, a]);
                    }
                    this.fireEvent('success', {
                        f: frm,
                        a: a
                    });
                    if (close) {
                        if (this.config.closeAction !== 'close') {
                            this.hide();
                        } else {
                            this.close();
                        }
                    }
                    status.exit();
                    this.doLayout();
                }
            });
        }
    },

    createForm: function(config) {
        Ext.applyIf(this.config, {
            formFrame: true,
            border: false,
            bodyBorder: false,
            autoHeight: true
        });
        config = config || {};
        Ext.applyIf(config, {
            labelAlign: this.config.labelAlign || 'top',
            labelWidth: this.config.labelWidth || 100,
            labelSeparator: this.config.labelSeparator || '',
            frame: this.config.formFrame,
            border: this.config.border,
            bodyBorder: this.config.bodyBorder,
            autoHeight: this.config.autoHeight,
            anchor: '100% 100%',
            errorReader: MODx.util.JSONReader,
            defaults: this.config.formDefaults || {
                msgTarget: this.config.msgTarget || 'under',
                anchor: '100%'
            },
            url: this.config.url,
            baseParams: this.config.baseParams || {},
            fileUpload: this.config.fileUpload || false
        });
        return new Ext.FormPanel(config);
    },

    renderForm: function() {
        this.fp.on('destroy', function() {
            Ext.EventManager.removeResizeListener(this.resizeWindow, this);
        }, this);
        this.add(this.fp);
    },

    checkIfLoaded: function(r) {
        r = r || {};
        if (this.fp && this.fp.getForm()) { /* so as not to duplicate form */
            this.fp.getForm().reset();
            this.fp.getForm().setValues(r);
            return true;
        }
        return false;
    },

    /* @smg6511:
        Suggest moving away from using this bulk setValues method and
        explicitly specifying each field’s value param in window configs,
        as is done for standard form panel pages. This will already have been done
        for the element quick create/edit windows. Also the above value-setting
        procedure in the _loadForm method could be dropped too. All windows in
        windows.js would need to be updated before dropping.
    */
    setValues: function(r) {
        if (r === null) { return false; }
        this.fp.getForm().setValues(r);
    },

    reset: function() {
        this.fp.getForm().reset();
    },

    hideField: function(f) {
        f.disable();
        f.hide();
        var d = f.getEl().up('.x-form-item');
        if (d) { d.setDisplayed(false); }
    },

    showField: function(f) {
        f.enable();
        f.show();
        var d = f.getEl().up('.x-form-item');
        if (d) { d.setDisplayed(true); }
    },

    loadDropZones: function() {
        if (this._dzLoaded) { return false; }
        var flds = this.fp.getForm().items;
        flds.each(function(fld) {
            if (fld.isFormField && (
                fld.isXType('textfield') || fld.isXType('textarea')
            ) && !fld.isXType('combo')) {
                new MODx.load({
                    xtype: 'modx-treedrop',
                    target: fld,
                    targetEl: fld.getEl().dom
                });
            }
        });
        this._dzLoaded = true;
    },

    resizeWindow: function() {
        var viewHeight = Ext.getBody().getViewSize().height;
        var el = this.fp.getForm().el;
        if(viewHeight < this.originalHeight){
            el.setStyle('overflow-y', 'scroll');
            el.setHeight(viewHeight - this.toolsHeight);
        }else{
            el.setStyle('overflow-y', 'auto');
            el.setHeight('auto');
        }
    },

    getWindowIdentifier: function() {
        return this.itemId || this.ident || this.id || Ext.id();
    },

    registerPseudomodal: function(window) {
        const windowId = this.getWindowIdentifier();
        MODx.openPseudoModals.push({
            windowId,
            window
        });
        // console.log('registerPseudomodal :: open modals', MODx.openPseudoModals);
    },

    /**
     * Removes a pseudomodal window reference from the registry
     * @param {String} windowId The window's unique identifier
     */
    unregisterPseudomodal: function(windowId) {
        // console.log(`Unegistering pseudomodal with id ${windowId}`);
        if (!typeof windowId === 'string') {
            console.error('Aborted unregistering a modal due to an invalid window id being passed.');
            return;
        }
        if (MODx.openPseudoModals.length > 1) {
            MODx.openPseudoModals.forEach((modxPseudoModal, i) => {
                if (modxPseudoModal.windowId === windowId) {
                    MODx.openPseudoModals.splice(i, 1);
                }
            });
            // console.log(`Unregistered window (id: ${windowId})\nRemaining modals:`, MODx.openPseudoModals);
        } else {
            MODx.openPseudoModals = [];
            // console.log(`Unregistered only window present (id: ${windowId})`, MODx.openPseudoModals);
        }
    },

    // getPseudomodalCount: function() {

    // },

    /**
     * 
     * @param {*} fbarSwitchFieldName 
     * @param {*} defaultValue 
     */
    setFbarSwitchHiddenField: function(fbarSwitchFieldName, defaultValue = 1) {
        // const
        //     windowId = this.getWindowIdentifier(),
        //     switchId = `${windowId}-${fbarSwitchFieldName}`,
        //     switchCmp = Ext.getCmp(switchId)
        // ;
        const switchId = `${this.getWindowIdentifier()}-${fbarSwitchFieldName}`;
        // console.log('switchCmp: ', switchCmp);
        // if (switchCmp) {
        // console.log(`Pushing hidden switch cmp for "${switchId}"`);
        this.config.fields.push({
            xtype: 'hidden',
            name: fbarSwitchFieldName,
            id: `${switchId}-hidden`,
            value: defaultValue
        });
        // }
    },

    /**
     * 
     * @param {*} windowId 
     * @param {*} fbarSwitchFieldName 
     * @param {*} switchLabel 
     * @param {*} switchIsChecked 
     * @returns 
     */
    getFbarSwitch: function(windowId, fbarSwitchFieldName, switchLabel, switchIsChecked = true) {
        const switchCmp = {
            xtype: 'xcheckbox',
            id: `${windowId}-${fbarSwitchFieldName}`,
            hideLabel: true,
            boxLabel: switchLabel,
            inputValue: 1,
            checked: switchIsChecked,
            listeners: {
                check: {
                    fn: function(cmp, checked) {
                        const hiddenCmp = Ext.getCmp(`${windowId}-${fbarSwitchFieldName}-hidden`);
                        // console.log(`fbar hidden id to find: ${windowId}-${fbarSwitchFieldName}-hidden`);
                        // console.log('fbar switch check evt, hiddenCmp', hiddenCmp);
                        if (hiddenCmp) {
                            // console.log('switch is checked?', checked);
                            const value = checked === false ? 0 : 1;
                            hiddenCmp.setValue(value);
                        }
                    },
                    scope: this
                }
            }
        };
        // console.log(`getting switch (${fbarSwitchFieldName}): `, switchCmp);
        return switchCmp;
    },

    /**
     * 
     * @param {*} config 
     * @param {*} isPrimaryButton 
     * @param {*} isSaveAndClose 
     * @returns 
     */
    getSaveButton: function(config, isPrimaryButton = true, isSaveAndClose = false) {
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
    },

    /**
     * 
     * @param {*} config 
     * @returns 
     */
    getWindowButtons: function(config) {
        const
            btns = [{
                text: config.cancelBtnText || _('close'),
                handler: function() {
                    if (this.config.closeAction !== 'close') {
                        this.hide();
                    } else {
                        this.close();
                    }
                },
                scope: this
            }],
            specification = config.modxFbarButtons || 'c-s'
        ;
        switch (specification) {
            case 'c-s':
                btns.push(this.getSaveButton(config));
                break;
            case 'c-s-sc':
                btns.push(this.getSaveButton(config, false));
                btns.push(this.getSaveButton(config, true, true));
                break;
            case 'custom':
                break;
            // no default
        }
        return btns;
    },

    /**
     * 
     * @param {*} config 
     * @returns 
     */
    getWindowFbar: function(config) {
        // console.log('getting window fbar...');
        const
            windowId = this.getWindowIdentifier(),
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
Ext.reg('modx-window', MODx.Window);
