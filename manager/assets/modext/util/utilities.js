Ext.namespace('MODx.util.Progress');
/**
 * Shows a Loading display when ajax calls are happening
 * 
 * @class MODx.util.LoadingBox
 * @extends Ext.Component
 * @param {Object} config An object of configuration properties
 * @xtype modx-loading-box
 */
MODx.util.LoadingBox = function(config) {
	config = config || {};
		
    Ext.Ajax.on('beforerequest',this.show,this);
    Ext.Ajax.on('requestcomplete',this.hide,this);
    Ext.Ajax.on('requestexception',this.hide,this);
};
Ext.override(MODx.util.LoadingBox,{
    enabled: true
    ,hide: function() {
        if (this.enabled) { Ext.Msg.hide(); }
    }
    ,show: function() {
        if (this.enabled) {
            Ext.Msg.show({
                title: _('please_wait')
                ,msg: _('loading')
                ,width:240
                ,modal: false
                ,cls: 'modx-loading-box'
                ,progress:true
                ,closable:false
            });
        }
    }
    ,disable: function() { this.enabled = false; }
    ,enable: function() { this.enabled = true; }
});
Ext.reg('modx-loading-box',MODx.util.LoadingBox);

/**
 * A JSON Reader specific to MODExt
 * 
 * @class MODx.util.JSONReader
 * @extends Ext.util.JSONReader
 * @param {Object} config An object of configuration properties
 * @xtype modx-json-reader
 */
MODx.util.JSONReader = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        successProperty:'success'
        ,totalProperty: 'total'
        ,root: 'data'
    });
    MODx.util.JSONReader.superclass.constructor.call(this,config,['id','msg']);
};
Ext.extend(MODx.util.JSONReader,Ext.data.JsonReader);
Ext.reg('modx-json-reader',MODx.util.JSONReader);

/**
 * @class MODx.util.Progress 
 */
MODx.util.Progress = {
    id: 0
    ,time: function(v,id,msg) {
        msg = msg || _('saving');
        if (MODx.util.Progress.id === id && v < 11) {
            Ext.MessageBox.updateProgress(v/10,msg);
        }
    }
    ,reset: function() {
        MODx.util.Progress.id = MODx.util.Progress.id + 1;
    }
};

/** Adds a lock mask to an element */
MODx.LockMask = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        msg: _('locked')
        ,msgCls: 'modx-lockmask'
    });
    MODx.LockMask.superclass.constructor.call(this,config.el,config);
};
Ext.extend(MODx.LockMask,Ext.LoadMask,{
    locked: false
    ,toggle: function() {
        if (this.locked) {
            this.hide();
            this.locked = false;
        } else {
            this.show();
            this.locked = true;
        }
    }
});
Ext.reg('modx-lockmask',MODx.LockMask);


/** 
 * Static Textfield
 */
MODx.StaticTextField = Ext.extend(Ext.form.TextField, {
    fieldClass: 'x-static-text-field',

    onRender: function() {
        this.readOnly = true;
        this.disabled = !this.initialConfig.submitValue;
        MODx.StaticTextField.superclass.onRender.apply(this, arguments);
    }
});
Ext.reg('statictextfield',MODx.StaticTextField);

/** 
 * Static Boolean
 */
MODx.StaticBoolean = Ext.extend(Ext.form.TextField, {
    fieldClass: 'x-static-text-field',

    onRender: function(tf) {
        this.readOnly = true;
        this.disabled = !this.initialConfig.submitValue;
        MODx.StaticBoolean.superclass.onRender.apply(this, arguments);
        this.on('change',this.onChange,this);
    }
    
    ,setValue: function(v) {
        if (v === 1) {
            this.addClass('green');
            v = _('yes');
        } else {
            this.addClass('red');
            v = _('no');
        }
        MODx.StaticBoolean.superclass.setValue.apply(this, arguments);
    }
});
Ext.reg('staticboolean',MODx.StaticBoolean);


/****************************************************************************
 *    Ext-specific overrides/extensions                                     *
 ****************************************************************************/

function $(el){
    if (!el) { return null; }
    var type = Ext.type(el);
    if (type === 'string'){
        el = document.getElementById(el);
        type = (el) ? 'element' : false;
    }
    if (type !== 'element') { return null; }
    return el;
}


Array.prototype.in_array = function(p_val) {
    for(var i=0,l=this.length;i<l;i=i+1) {
        if(this[i] === p_val) {
            return true;
        }
    }
    return false;
};


Ext.form.setCheckboxValues = function(form,id,mask) {
    var f, n=0;
    while ((f = form.findField(id+n)) !== null) {
        f.setValue((mask & (1<<n))?'true':'false');
        n=n+1;
    } 
};

Ext.form.getCheckboxMask = function(cbgroup) {
    var mask='';
    if (typeof(cbgroup) !== 'undefined') {
        if ((typeof(cbgroup)==='string')) { 
            mask = cbgroup+'';
        } else {
            for(var i=0,len=cbgroup.length;i<len;i=i+1) {
                mask += (mask !== '' ? ',' : '')+(cbgroup[i]-0);
            }
        }
    }
    return mask;
};


Ext.form.BasicForm.prototype.append = function() {
  // Create a new layout object
  var layout = new Ext.form.Layout();
  // Keep track of added fields that are form fields (isFormField)
  var fields = [];
  // Add all the fields on to the layout stack
  layout.stack.push.apply(layout.stack, arguments);

  // Add only those fields that are form fields to the 'fields' array
  for(var i = 0; i < arguments.length; i=i+1) {
    if(arguments[i].isFormField) {
      fields.push(arguments[i]);
    }
  }

  // Render the layout
  layout.render(this.el);

  // If we found form fields add them to the form's items collection and render the
  // fields into their containers created by the layout
  if(fields.length > 0) {
    this.items.addAll(fields);

    // Render each field
    for(var f=0;f<fields.length;f=f+1) {
      fields[f].render('x-form-el-' + fields[f].id);
    }
  }

  return this;
};


Ext.form.AMPMField = function(id,v) {
    return new Ext.form.ComboBox({
        store: new Ext.data.SimpleStore({
            fields: ['ampm'],
            data: [['am'],['pm']]
        }),
        displayField: 'ampm',
        hiddenName: id,
        mode: 'local',
        editable: false,
        forceSelection: true,
        triggerAction: 'all',
        width: 60,
        value: v || 'am'
    });
};

Ext.form.HourField = function(id,name,v){
    return new Ext.form.ComboBox({
        store: new Ext.data.SimpleStore({
            fields: ['hour'],
            data: [[1],[2],[3],[4],[5],[6],[7],[8],[9],[10],[11],[12]]
        }),
        displayField: 'hour',
        mode: 'local',
        triggerAction: 'all',
        width: 60,
        forceSelection: true,
        rowHeight: false,
        editable: false,
        value: v || 1,
        transform: id
    }); 
};


Ext.override(Ext.tree.TreeNodeUI,{
    hasClass : function(className){
        var el = Ext.fly(this.elNode);
        return className && (' '+el.dom.className+' ').indexOf(' '+className+' ') !== -1;
    }
});


// allows for messages in JSON responses
Ext.override(Ext.form.Action.Submit,{         
    handleResponse : function(response){        
        var m = Ext.decode(response.responseText); // shaun 7/11/07
        if(this.form.errorReader){
            var rs = this.form.errorReader.read(response);
            var errors = [];
            if(rs.records){
                for(var i = 0, len = rs.records.length; i < len; i=i+1) {
                    var r = rs.records[i];
                    errors[i] = r.data;
                }
            }
            if(errors.length < 1){
                errors = null;
            }
            return {
                success : rs.success,
                message : m.message, // shaun 7/11/07
                object : m.object, // shaun 7/18/07
                errors : errors
            };
        }
        return Ext.decode(response.responseText);
    }
});





/**
 * @class Ext.form.ColorField
 * @extends Ext.form.TriggerField
 * Provides a very simple color form field with a ColorMenu dropdown.
 * Values are stored as a six-character hex value without the '#'.
 * I.e. 'ffffff'
 * @constructor
 * Create a new ColorField
 * <br />Example:
 * <pre><code>
var cf = new Ext.form.ColorField({
    fieldLabel: 'Color',
    hiddenName:'pref_sales',
    showHexValue:true
});
</code></pre>
 * @param {Object} config
 */
Ext.form.ColorField = function(config){
    Ext.form.ColorField.superclass.constructor.call(this, config);
    this.on('render', this.handleRender);
};

Ext.extend(Ext.form.ColorField, Ext.form.TriggerField,  {
    /**
     * @cfg {Boolean} showHexValue
     * True to display the HTML Hexidecimal Color Value in the field
     * so it is manually editable.
     */
    showHexValue : true,
    
    /**
     * @cfg {String} triggerClass
     * An additional CSS class used to style the trigger button.  The trigger will always get the
     * class 'x-form-trigger' and triggerClass will be <b>appended</b> if specified (defaults to 'x-form-color-trigger'
     * which displays a calendar icon).
     */
    triggerClass : 'x-form-color-trigger',
    
    /**
     * @cfg {String/Object} autoCreate
     * A DomHelper element spec, or true for a default element spec (defaults to
     * {tag: "input", type: "text", size: "10", autocomplete: "off"})
     */
    // private
    defaultAutoCreate : {tag: "input", type: "text", size: "10",
                         autocomplete: "off", maxlength:"6"},
    
    /**
     * @cfg {String} lengthText
     * A string to be displayed when the length of the input field is
     * not 3 or 6, i.e. 'fff' or 'ffccff'.
     */
    lengthText: "Color hex values must be either 3 or 6 characters.",
    
    //text to use if blank and allowBlank is false
    blankText: "Must have a hexidecimal value in the format ABCDEF.",
    
    /**
     * @cfg {String} color
     * A string hex value to be used as the default color.  Defaults
     * to 'FFFFFF' (white).
     */
    defaultColor: '',
    
    maskRe: /[a-f0-9]/i,
    // These regexes limit input and validation to hex values
    regex: /[a-f0-9]/i,

    //private
    curColor: '',
    
    // private
    validateValue : function(value){
        if(!this.showHexValue) {
            return true;
        }
        if(value.length<1) {
            this.el.setStyle({
                'background-color':'#'+this.defaultColor
            });
            if(!this.allowBlank) {
                this.markInvalid(String.format(this.blankText, value));
                return false;
            }
            return true;
        }
        this.setColor(value);
        return true;
    },

    // private
    validateBlur : function(){
        return !this.menu || !this.menu.isVisible();
    },
    
    // Manually apply the invalid line image since the background
    // was previously cleared so the color would show through.
    markInvalid : function( msg ) {
        Ext.form.ColorField.superclass.markInvalid.call(this, msg);
        this.el.setStyle({
            'background-image': 'url(../lib/resources/images/default/grid/invalid_line.gif)'
        });
    },

    /**
     * Returns the current color value of the color field
     * @return {String} value The hexidecimal color value
     */
    getValue : function(){
        return this.curValue || this.defaultValue || "FFFFFF";
    },

    /**
     * Sets the value of the color field.  Format as hex value 'FFFFFF'
     * without the '#'.
     * @param {String} hex The color value
     */
    setValue : function(hex){
        Ext.form.ColorField.superclass.setValue.call(this, hex);
        this.setColor(hex);
    },
    
    /**
     * Sets the current color and changes the background.
     * Does *not* change the value of the field.
     * @param {String} hex The color value.
     */
    setColor : function(hex) {
        this.curColor = hex;
        h = hex.substr(0,1) !== '#' ? '#'+hex : hex;
        
        this.el.setStyle( {
            'background-color': h,
            'background-image': 'none'
        });
        if(!this.showHexValue) {
            /*this.el.setStyle({
                'text-indent': '-100px'
            });
            if(Ext.isIE) {
                this.el.setStyle({
                    'margin-left': '100px'
                });
            }*/
        }
    },
    
    handleRender: function() {
        this.setDefaultColor();
    },
    
    setDefaultColor : function() {
        this.setValue(this.defaultColor);
    },

    // private
    menuListeners : {
        select: function(m, d){
            this.setValue(d);
        },
        show : function(){ // retain focus styling
            this.onFocus();
        },
        hide : function(){
            this.focus();
            var ml = this.menuListeners;
            this.menu.un("select", ml.select,  this);
            this.menu.un("show", ml.show,  this);
            this.menu.un("hide", ml.hide,  this);
        }
    },
    
    //private
    handleSelect : function(palette, selColor) {
        this.setValue(selColor);
    },

    // private
    // Implements the default empty TriggerField.onTriggerClick function to display the ColorPicker
    onTriggerClick : function(){
        if(this.disabled){
            return;
        }
        if(this.menu === null){
            this.menu = new Ext.menu.ColorMenu();
            this.menu.palette.on('select', this.handleSelect, this );
        }
        this.menu.on(Ext.apply({}, this.menuListeners, {
            scope:this
        }));
        this.menu.show(this.el, "tl-bl?");
    }
});




/**
 * QTips to form fields
 */
Ext.form.Field.prototype.afterRender = Ext.form.Field.prototype.afterRender.createSequence(function() { 
    if (this.description) {
        Ext.QuickTips.register({
            target:  this.getEl()
            ,text: this.description
            ,enabled: true
        });
        var label = Ext.form.Field.findLabel(this);
        if(label){
            Ext.QuickTips.register({
                target:  label
                ,text: this.description
                ,enabled: true
            });
        }
    }
});
Ext.applyIf(Ext.form.Field,{
    findLabel: function(field) {
        var wrapDiv = null;
        var label = null;
        
        //find form-element and label?
        wrapDiv = field.getEl().up('div.x-form-element');
        if(wrapDiv){
            label = wrapDiv.child('label');
        }
        if(label){
            return label;
        }
        //find form-item and label
        wrapDiv = field.getEl().up('div.x-form-item');
        if(wrapDiv) {
            label = wrapDiv.child('label');        
        }
        if(label){
            return label;
        }
    }
});



/* Fix to Safari Scroll wheel bug */
Ext.override(Ext.grid.GridView, {
    layout : function(){
        if(!this.mainBody){
            return;         }
        var g = this.grid;
        var c = g.getGridEl();
        var csize = c.getSize(true);
        var vw = csize.width;
        if(vw < 20 || csize.height < 20){
            return;
        }
        if(g.autoHeight){
            this.scroller.dom.style.overflow = 'visible';
            this.scroller.dom.style.position = 'static';
        }else{
            this.el.setSize(csize.width, csize.height);
            var hdHeight = this.mainHd.getHeight();
            var vh = csize.height - (hdHeight);
            this.scroller.setSize(vw, vh);
            if(this.innerHd){
                this.innerHd.style.width = (vw)+'px';
            }
        }
        if(this.forceFit){
            if(this.lastViewWidth !== vw){
                this.fitColumns(false, false);
                this.lastViewWidth = vw;
            }
        }else {
            this.autoExpand();
            this.syncHeaderScroll();
        }
        this.onLayout(vw, vh);
    }
});

MODx.util.Clipboard = function() {
    return {
        escape: function(text){
            text = encodeURIComponent(text);
            return text.replace(/%0A/g, "%0D%0A");
        }
        
        ,copy: function(text){
            if (Ext.isIE) {
                window.clipboardData.setData("Text", text);
            } else {
                var flashcopier = 'flashcopier';
                if (!document.getElementById(flashcopier)) {
                    var divholder = document.createElement('div');
                    divholder.id = flashcopier;
                    document.body.appendChild(divholder);
                }                
                document.getElementById(flashcopier).innerHTML = '';                
                var divinfo = '<embed src="' + MODx.config.manager_url
                    + 'assets/modext/_clipboard.swf" FlashVars="clipboard=' 
                    + MODx.util.Clipboard.escape(text)
                    + '" width="0" height="0" type="application/x-shockwave-flash"></embed>';
                document.getElementById(flashcopier).innerHTML = divinfo;
            }
        }
    };
}();

/* fix for checkbox label issue */
Ext.override(Ext.form.Checkbox, {
    onRender: function(ct, position){
        Ext.form.Checkbox.superclass.onRender.call(this, ct, position);
        if(this.inputValue !== undefined){
            this.el.dom.value = this.inputValue;
        }
        this.innerWrap = this.el.wrap({
            cls: this.baseCls+'-wrap-inner'
        });
        this.wrap = this.innerWrap.wrap({cls: this.baseCls+'-wrap'});
        this.imageEl = this.innerWrap.createChild({
            tag: 'img',
            src: Ext.BLANK_IMAGE_URL,
            cls: this.baseCls
        });
        if(this.boxLabel){
            this.labelEl = this.innerWrap.createChild({
                tag: 'label',
                htmlFor: this.el.id,
                cls: 'x-form-cb-label',
                html: this.boxLabel
            });
        }
        if(this.checked){
            this.setValue(true);
        }else{
            this.checked = this.el.dom.checked;
        }
        this.originalValue = this.checked;
    },
    afterRender: function(){
        Ext.form.Checkbox.superclass.afterRender.call(this);
        this.imageEl[this.checked ? 'addClass' : 'removeClass'](this.checkedCls);
    },
    initCheckEvents: function(){
        this.innerWrap.addClassOnOver(this.overCls);
        this.innerWrap.addClassOnClick(this.mouseDownCls);
        this.innerWrap.on('click', this.onClick, this);
    },
    onFocus: function(e) {
        Ext.form.Checkbox.superclass.onFocus.call(this, e);
        this.innerWrap.addClass(this.focusCls);
    },
    onBlur: function(e) {
        Ext.form.Checkbox.superclass.onBlur.call(this, e);
        this.innerWrap.removeClass(this.focusCls);
    },
    onClick: function(e){
        if (e.getTarget().htmlFor != this.el.dom.id) {
            if (e.getTarget() !== this.el.dom) {
                this.el.focus();
            }
            if (!this.disabled && !this.readOnly) {
                this.toggleValue();
            }
        }
    },
    onEnable: Ext.form.Checkbox.superclass.onEnable,
    onDisable: Ext.form.Checkbox.superclass.onDisable,
    onKeyUp: undefined,
    setValue: function(v) {
        var checked = this.checked;
        this.checked = (v === true || v === 'true' || v == '1' || String(v).toLowerCase() == 'on');
        if(this.rendered){
            this.el.dom.checked = this.checked;
            this.el.dom.defaultChecked = this.checked;
            this.imageEl[this.checked ? 'addClass' : 'removeClass'](this.checkedCls);
        }
        if(checked != this.checked){
            this.fireEvent("check", this, this.checked);
            if(this.handler){
                this.handler.call(this.scope || this, this, this.checked);
            }
        }
    },
    getResizeEl: function() {
        return this.wrap;
    }
});
Ext.override(Ext.form.Radio, {
    checkedCls: 'x-form-radio-checked'
});

Ext.onReady(function() {
    MODx.util.LoadingBox = MODx.load({ xtype: 'modx-loading-box' });
    MODx.util.JSONReader = MODx.load({ xtype: 'modx-json-reader' });
    MODx.form.Handler = MODx.load({ xtype: 'modx-form-handler' });
    MODx.msg = MODx.load({ xtype: 'modx-msg' });
});