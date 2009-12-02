Ext.namespace('MODx.util.Progress');
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
    ,lock: function() { this.locked = true; this.show(); }
    ,unlock: function() { this.locked = false; this.hide(); }
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
  var layout = new Ext.form.Layout();
  var fields = [];
  layout.stack.push.apply(layout.stack, arguments);
  for(var i = 0; i < arguments.length; i=i+1) {
    if(arguments[i].isFormField) {
      fields.push(arguments[i]);
    }
  }
  layout.render(this.el);

  if(fields.length > 0) {
    this.items.addAll(fields);
    for(var f=0;f<fields.length;f=f+1) {
      fields[f].render('x-form-el-' + fields[f].id);
    }
  }
  return this;
};


Ext.form.AMPMField = function(id,v) {
    return new Ext.form.ComboBox({
        store: new Ext.data.SimpleStore({
            fields: ['ampm']
            ,data: [['am'],['pm']]
        })
        ,displayField: 'ampm'
        ,hiddenName: id
        ,mode: 'local'
        ,editable: false
        ,forceSelection: true
        ,triggerAction: 'all'
        ,width: 60
        ,value: v || 'am'
    });
};

Ext.form.HourField = function(id,name,v){
    return new Ext.form.ComboBox({
        store: new Ext.data.SimpleStore({
            fields: ['hour']
            ,data: [[1],[2],[3],[4],[5],[6],[7],[8],[9],[10],[11],[12]]
        })
        ,displayField: 'hour'
        ,mode: 'local'
        ,triggerAction: 'all'
        ,width: 60
        ,forceSelection: true
        ,rowHeight: false
        ,editable: false
        ,value: v || 1
        ,transform: id
    }); 
};


Ext.override(Ext.tree.TreeNodeUI,{
    hasClass : function(className){
        var el = Ext.fly(this.elNode);
        return className && (' '+el.dom.className+' ').indexOf(' '+className+' ') !== -1;
    }
});


/* allows for messages in JSON responses */
Ext.override(Ext.form.Action.Submit,{         
    handleResponse : function(response){        
        var m = Ext.decode(response.responseText); /* shaun 7/11/07 */
        if (this.form.errorReader) {
            var rs = this.form.errorReader.read(response);
            var errors = [];
            if (rs.records) {
                for(var i = 0, len = rs.records.length; i < len; i=i+1) {
                    var r = rs.records[i];
                    errors[i] = r.data;
                }
            }
            if (errors.length < 1) { errors = null; }
            return {
                success : rs.success
                ,message : m.message /* shaun 7/11/07 */
                ,object : m.object /* shaun 7/18/07 */
                ,errors : errors
            };
        }
        return Ext.decode(response.responseText);
    }
});

/* QTips to form fields */
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
        wrapDiv = field.getEl().up('div.x-form-element');
        if(wrapDiv){
            label = wrapDiv.child('label');
        }
        if(label){
            return label;
        }
        wrapDiv = field.getEl().up('div.x-form-item');
        if(wrapDiv) {
            label = wrapDiv.child('label');        
        }
        if(label){
            return label;
        }
    }
});

/* allow copying to clipboard */
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


Ext.onReady(function() {
    MODx.util.JSONReader = MODx.load({ xtype: 'modx-json-reader' });
    MODx.form.Handler = MODx.load({ xtype: 'modx-form-handler' });
    MODx.msg = MODx.load({ xtype: 'modx-msg' });
});