/**
 * Overrides native Ext.Button behavior to use FontAwesome icons
 *
 * @class MODx.Button
 * @extends Ext.Button
 * @constructor
 * @param {Object} config An object of options.
 * @xtype modx-button
 */
MODx.Button = function(config) {

    config = config || {};

    if(config.iconCls){
        config.ctCls = config.cls + ' ' + config.ctCls
        config.cls = config.iconCls
        config.iconCls = ''
    }
    Ext.applyIf(config,{
        template: new Ext.XTemplate('<span id="{4}" class="x-btn icon {1} {3}" unselectable="on">'+
                                    '   <i class="{2}">'+
                                //    '       <button type="{0}"></button>'+
                                    '   </i>'+
                                    '</span>').compile()
    });

    MODx.Button.superclass.constructor.call(this,config);

};
Ext.extend(MODx.Button,Ext.Button,{


    // private
    onRender : function(ct, position){
        if(!this.template){
            if(!Ext.Button.buttonTemplate){
                // hideous table template
                Ext.Button.buttonTemplate = new Ext.Template(
                    '<span id="{4}" class="x-btn icon {1} {3}" unselectable="on">'+
                    '   <i class="{iconCls}"></i>'+
                    '</span>');
                Ext.Button.buttonTemplate.compile();
            }
            this.template = Ext.Button.buttonTemplate;
        }

        var btn, targs = this.getTemplateArgs();


        targs.iconCls = this.iconCls;

        if(position){
            btn = this.template.insertBefore(position, targs, true);
        }else{
            btn = this.template.append(ct, targs, true);
        }
        /**
         * An {@link Ext.Element Element} encapsulating the Button's clickable element. By default,
         * this references a <tt>&lt;button&gt;</tt> element. Read only.
         * @type Ext.Element
         * @property btnEl
         */
        this.btnEl = btn.child('i');
        this.mon(this.btnEl, {
            scope: this,
            focus: this.onFocus,
            blur: this.onBlur
        });

        this.initButtonEl(btn, this.btnEl);

        Ext.ButtonToggleMgr.register(this);
    },

});
Ext.reg('modx-button',MODx.Button);

