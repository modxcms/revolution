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

/** add clearDirty to basicform */
Ext.override(Ext.form.BasicForm,{
    clearDirty : function(nodeToRecurse){
        nodeToRecurse = nodeToRecurse || this;
        nodeToRecurse.items.each(function(f){
            if (!f.getValue) return;
            
            if(f.items){
                this.clearDirty(f);
            } else if(f.originalValue != f.getValue()){
                f.originalValue = f.getValue();
            }
        },this);
    }
});


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

/* add helper method to set checkbox boxLabel */
Ext.override(Ext.form.Checkbox, {
    setBoxLabel: function(boxLabel){
        this.boxLabel = boxLabel;
        if(this.rendered){
            this.wrap.child('.x-form-cb-label').update(boxLabel);
        }
    }
});

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
        if (!el) return '';
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


Ext.util.Format.trimCommas = function(s) {
    s = s.replace(',,',',');
    var len = s.length;
    if (s.substr(len-1,1) == ",") {
        s = s.substring(0,len-1);
    }
    if (s.substr(0,1) == ",") {
        s = s.substring(1);
    }
    if (s == ',') { s = ''; }
    return s;
};

/* rowactions plugin */
Ext.ns('Ext.ux.grid');if('function'!==typeof RegExp.escape){RegExp.escape=function(s){if('string'!==typeof s){return s}return s.replace(/([.*+?\^=!:${}()|\[\]\/\\])/g,'\\$1')}}Ext.ux.grid.RowActions=function(a){Ext.apply(this,a);this.addEvents('beforeaction','action','beforegroupaction','groupaction');Ext.ux.grid.RowActions.superclass.constructor.call(this)};Ext.extend(Ext.ux.grid.RowActions,Ext.util.Observable,{actionEvent:'click',autoWidth:true,dataIndex:'',editable:false,header:'',isColumn:true,keepSelection:false,menuDisabled:true,sortable:false,tplGroup:'<tpl for="actions">'+'<div class="ux-grow-action-item<tpl if="\'right\'===align"> ux-action-right</tpl> '+'{cls}" style="{style}" qtip="{qtip}">{text}</div>'+'</tpl>',tplRow:'<div class="ux-row-action">'+'<tpl for="actions">'+'<div class="ux-row-action-item {cls} <tpl if="text">'+'ux-row-action-text</tpl>" style="{hide}{style}" qtip="{qtip}">'+'<tpl if="text"><span qtip="{qtip}">{text}</span></tpl></div>'+'</tpl>'+'</div>',hideMode:'visibility',widthIntercept:4,widthSlope:21,init:function(g){this.grid=g;this.id=this.id||Ext.id();var h=g.getColumnModel().lookup;delete(h[undefined]);h[this.id]=this;if(!this.tpl){this.tpl=this.processActions(this.actions)}if(this.autoWidth){this.width=this.widthSlope*this.actions.length+this.widthIntercept;this.fixed=true}var i=g.getView();var j={scope:this};j[this.actionEvent]=this.onClick;g.afterRender=g.afterRender.createSequence(function(){i.mainBody.on(j);g.on('destroy',this.purgeListeners,this)},this);if(!this.renderer){this.renderer=function(a,b,c,d,e,f){b.css+=(b.css?' ':'')+'ux-row-action-cell';return this.tpl.apply(this.getData(a,b,c,d,e,f))}.createDelegate(this)}if(i.groupTextTpl&&this.groupActions){i.interceptMouse=i.interceptMouse.createInterceptor(function(e){if(e.getTarget('.ux-grow-action-item')){return false}});i.groupTextTpl='<div class="ux-grow-action-text">'+i.groupTextTpl+'</div>'+this.processActions(this.groupActions,this.tplGroup).apply()}if(true===this.keepSelection){g.processEvent=g.processEvent.createInterceptor(function(a,e){if('mousedown'===a){return!this.getAction(e)}},this)}},getData:function(a,b,c,d,e,f){return c.data||{}},processActions:function(b,c){var d=[];Ext.each(b,function(a,i){if(a.iconCls&&'function'===typeof(a.callback||a.cb)){this.callbacks=this.callbacks||{};this.callbacks[a.iconCls]=a.callback||a.cb}var o={cls:a.iconIndex?'{'+a.iconIndex+'}':(a.iconCls?a.iconCls:''),qtip:a.qtipIndex?'{'+a.qtipIndex+'}':(a.tooltip||a.qtip?a.tooltip||a.qtip:''),text:a.textIndex?'{'+a.textIndex+'}':(a.text?a.text:''),hide:a.hideIndex?'<tpl if="'+a.hideIndex+'">'+('display'===this.hideMode?'display:none':'visibility:hidden')+';</tpl>':(a.hide?('display'===this.hideMode?'display:none':'visibility:hidden;'):''),align:a.align||'right',style:a.style?a.style:''};d.push(o)},this);var e=new Ext.XTemplate(c||this.tplRow);return new Ext.XTemplate(e.apply({actions:d}))},getAction:function(e){var a=false;var t=e.getTarget('.ux-row-action-item');if(t){a=t.className.replace(/ux-row-action-item /,'');if(a){a=a.replace(/ ux-row-action-text/,'');a=a.trim()}}return a},onClick:function(e,a){var b=this.grid.getView();var c=e.getTarget('.x-grid3-row');var d=b.findCellIndex(a.parentNode.parentNode);var f=this.getAction(e);if(false!==c&&false!==d&&false!==f){var g=this.grid.store.getAt(c.rowIndex);if(this.callbacks&&'function'===typeof this.callbacks[f]){this.callbacks[f](this.grid,g,f,c.rowIndex,d)}if(true!==this.eventsSuspended&&false===this.fireEvent('beforeaction',this.grid,g,f,c.rowIndex,d)){return}else if(true!==this.eventsSuspended){this.fireEvent('action',this.grid,g,f,c.rowIndex,d)}}t=e.getTarget('.ux-grow-action-item');if(t){var h=b.findGroup(a);var i=h?h.id.replace(/ext-gen[0-9]+-gp-/,''):null;var j;if(i){var k=new RegExp(RegExp.escape(i));j=this.grid.store.queryBy(function(r){return r._groupId.match(k)});j=j?j.items:[]}f=t.className.replace(/ux-grow-action-item (ux-action-right )*/,'');if('function'===typeof this.callbacks[f]){this.callbacks[f](this.grid,j,f,i)}if(true!==this.eventsSuspended&&false===this.fireEvent('beforegroupaction',this.grid,j,f,i)){return false}this.fireEvent('groupaction',this.grid,j,f,i)}}});Ext.reg('rowactions',Ext.ux.grid.RowActions);

/*
 * Ext JS Library 0.30
 * Copyright(c) 2006-2009, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */
Ext.SwitchButton = Ext.extend(Ext.Component, {
    initComponent : function(){
        Ext.SwitchButton.superclass.initComponent.call(this);

        var mc = new Ext.util.MixedCollection();
        mc.addAll(this.items);
        this.items = mc;

        this.addEvents('change');

        if(this.handler){
            this.on('change', this.handler, this.scope || this);
        }
    },

    onRender : function(ct, position){
        var el = document.createElement('table');
        el.cellSpacing = 0;
        el.className = 'x-rbtn';
        el.id = this.id;

        var row = document.createElement('tr');
        el.appendChild(document.createElement('tbody')).appendChild(row);

        var count = this.items.length;
        var last = count - 1;
        this.activeItem = this.items.get(this.activeItem);

        for(var i = 0; i < count; i++){
            var item = this.items.itemAt(i);

            var cell = row.appendChild(document.createElement('td'));
            cell.id = this.id + '-rbi-' + i;

            var cls = i == 0 ? 'x-rbtn-first' : (i == last ? 'x-rbtn-last' : 'x-rbtn-item');
            item.baseCls = cls;

            if(this.activeItem == item){
                cls += '-active';
            }
            cell.className = cls;

            var button = document.createElement('button');
            button.innerHTML = '&#160;';
            button.className = item.iconCls;
            button.qtip = item.tooltip;

            cell.appendChild(button);

            item.cell = cell;
        }

        this.el = Ext.get(ct.dom.appendChild(el));

        this.el.on('click', this.onClick, this);
    },

    getActiveItem : function(){
        return this.activeItem;
    },

    setActiveItem : function(item){
        if(typeof item != 'object' && item !== null){
            item = this.items.get(item);
        }
        var current = this.getActiveItem();
        if(item != current){
            if(current){
                Ext.fly(current.cell).removeClass(current.baseCls + '-active');
            }
            if(item) {
                Ext.fly(item.cell).addClass(item.baseCls + '-active');
            }
            this.activeItem = item;
            this.fireEvent('change', this, item);
        }
        return item;
    },
    
    onClick : function(e){
        var target = e.getTarget('td', 2);
        if(!this.disabled && target){
            this.setActiveItem(parseInt(target.id.split('-rbi-')[1], 10));
        }
    }
});

Ext.reg('switch', Ext.SwitchButton);


/* superboxselect */
Ext.namespace('Ext.ux.form');Ext.ux.form.SuperBoxSelect=function(config){config.listClass = 'x-superboxselect-list';Ext.ux.form.SuperBoxSelect.superclass.constructor.call(this,config);this.addEvents('beforeadditem','additem','newitem','beforeremoveitem','removeitem','clear')};Ext.ux.form.SuperBoxSelect=Ext.extend(Ext.ux.form.SuperBoxSelect,Ext.form.ComboBox,{ctCls: 'x-superboxselect-ct',addNewDataOnBlur:false,allowAddNewData:false,allowQueryAll:true,backspaceDeletesLastItem:true,classField:null,clearBtnCls:'',clearLastQueryOnEscape:false,clearOnEscape:false,displayFieldTpl:null,extraItemCls:'',extraItemStyle:'',expandBtnCls:'',fixFocusOnTabSelect:true,forceFormValue:true,forceSameValueQuery:false,itemDelimiterKey:Ext.EventObject.ENTER,navigateItemsWithTab:true,pinList:true,preventDuplicates:true,queryFilterRe:'',queryValuesDelimiter:'|',queryValuesIndicator:'valuesqry',removeValuesFromStore:true,renderFieldBtns:true,stackItems:false,styleField:null,supressClearValueRemoveEvents:false,validationEvent:'blur',valueDelimiter:',',initComponent:function(){Ext.apply(this,{items:new Ext.util.MixedCollection(false),usedRecords:new Ext.util.MixedCollection(false),addedRecords:[],remoteLookup:[],hideTrigger:true,grow:false,resizable:false,multiSelectMode:false,preRenderValue:null,filteredQueryData:''});if(this.queryFilterRe){if(Ext.isString(this.queryFilterRe)){this.queryFilterRe=new RegExp(this.queryFilterRe)}}if(this.transform){this.doTransform()}if(this.forceFormValue){this.items.on({add:this.manageNameAttribute,remove:this.manageNameAttribute,clear:this.manageNameAttribute,scope:this})}Ext.ux.form.SuperBoxSelect.superclass.initComponent.call(this);if(this.mode==='remote'&&this.store){this.store.on('load',this.onStoreLoad,this)}},onRender:function(ct,position){var h=this.hiddenName;this.hiddenName=null;Ext.ux.form.SuperBoxSelect.superclass.onRender.call(this,ct,position);this.hiddenName=h;this.manageNameAttribute();var extraClass=(this.stackItems===true)?'x-superboxselect-stacked':'';if(this.renderFieldBtns){extraClass+=' x-superboxselect-display-btns'}this.el.removeClass('x-form-text').addClass('x-superboxselect-input-field');this.wrapEl=this.el.wrap({tag:'ul'});this.outerWrapEl=this.wrapEl.wrap({tag:'div',cls:'x-form-text x-superboxselect '+extraClass});this.inputEl=this.el.wrap({tag:'li',cls:'x-superboxselect-input'});if(this.renderFieldBtns){this.setupFieldButtons().manageClearBtn()}this.setupFormInterception()},doTransform:function(){var s=Ext.getDom(this.transform),transformValues=[];if(!this.store){this.mode='local';var d=[],opts=s.options;for(var i=0,len=opts.length;i<len;i++){var o=opts[i],oe=Ext.get(o),value=oe.getAttributeNS(null,'value')||'',cls=oe.getAttributeNS(null,'className')||'',style=oe.getAttributeNS(null,'style')||'';if(o.selected){transformValues.push(value)}d.push([value,o.text,cls,typeof(style)==="string"?style:style.cssText])}this.store=new Ext.data.SimpleStore({'id':0,fields:['value','text','cls','style'],data:d});Ext.apply(this,{valueField:'value',displayField:'text',classField:'cls',styleField:'style'})}if(transformValues.length){this.value=transformValues.join(',')}},setupFieldButtons:function(){this.buttonWrap=this.outerWrapEl.createChild({cls:'x-superboxselect-btns'});this.buttonClear=this.buttonWrap.createChild({tag:'div',cls:'x-superboxselect-btn-clear '+this.clearBtnCls});if(this.allowQueryAll){this.buttonExpand=this.buttonWrap.createChild({tag:'div',cls:'x-superboxselect-btn-expand '+this.expandBtnCls})}this.initButtonEvents();return this},initButtonEvents:function(){this.buttonClear.addClassOnOver('x-superboxselect-btn-over').on('click',function(e){e.stopEvent();if(this.disabled){return}this.clearValue();this.el.focus()},this);if(this.allowQueryAll){this.buttonExpand.addClassOnOver('x-superboxselect-btn-over').on('click',function(e){e.stopEvent();if(this.disabled){return}if(this.isExpanded()){this.multiSelectMode=false}else if(this.pinList){this.multiSelectMode=true}this.onTriggerClick()},this)}},removeButtonEvents:function(){this.buttonClear.removeAllListeners();if(this.allowQueryAll){this.buttonExpand.removeAllListeners()}return this},clearCurrentFocus:function(){if(this.currentFocus){this.currentFocus.onLnkBlur();this.currentFocus=null}return this},initEvents:function(){var el=this.el;el.on({click:this.onClick,focus:this.clearCurrentFocus,blur:this.onBlur,keydown:this.onKeyDownHandler,keyup:this.onKeyUpBuffered,scope:this});this.on({collapse:this.onCollapse,expand:this.clearCurrentFocus,scope:this});this.wrapEl.on('click',this.onWrapClick,this);this.outerWrapEl.on('click',this.onWrapClick,this);this.inputEl.focus=function(){el.focus()};Ext.ux.form.SuperBoxSelect.superclass.initEvents.call(this);Ext.apply(this.keyNav,{tab:function(e){if(this.fixFocusOnTabSelect&&this.isExpanded()){e.stopEvent();el.blur();this.onViewClick(false);this.focus(false,10);return true}this.onViewClick(false);if(el.dom.value!==''){this.setRawValue('')}return true},down:function(e){if(!this.isExpanded()&&!this.currentFocus){if(this.allowQueryAll){this.onTriggerClick()}}else{this.inKeyMode=true;this.selectNext()}},enter:function(){}})},onClick:function(){this.clearCurrentFocus();this.collapse();this.autoSize()},beforeBlur:function(){if(this.allowAddNewData&&this.addNewDataOnBlur){var v=this.el.dom.value;if(v!==''){this.fireNewItemEvent(v)}}Ext.form.ComboBox.superclass.beforeBlur.call(this)},onFocus:function(){this.outerWrapEl.addClass(this.focusClass);Ext.ux.form.SuperBoxSelect.superclass.onFocus.call(this)},onBlur:function(){this.outerWrapEl.removeClass(this.focusClass);this.clearCurrentFocus();if(this.el.dom.value!==''){this.applyEmptyText();this.autoSize()}Ext.ux.form.SuperBoxSelect.superclass.onBlur.call(this)},onCollapse:function(){this.view.clearSelections();this.multiSelectMode=false},onWrapClick:function(e){e.stopEvent();this.collapse();this.el.focus();this.clearCurrentFocus()},markInvalid:function(msg){var elp,t;if(!this.rendered||this.preventMark){return}this.outerWrapEl.addClass(this.invalidClass);msg=msg||this.invalidText;switch(this.msgTarget){case'qtip':Ext.apply(this.el.dom,{qtip:msg,qclass:'x-form-invalid-tip'});Ext.apply(this.wrapEl.dom,{qtip:msg,qclass:'x-form-invalid-tip'});if(Ext.QuickTips){Ext.QuickTips.enable()}break;case'title':this.el.dom.title=msg;this.wrapEl.dom.title=msg;this.outerWrapEl.dom.title=msg;break;case'under':if(!this.errorEl){elp=this.getErrorCt();if(!elp){this.el.dom.title=msg;break}this.errorEl=elp.createChild({cls:'x-form-invalid-msg'});this.errorEl.setWidth(elp.getWidth(true)-20)}this.errorEl.update(msg);Ext.form.Field.msgFx[this.msgFx].show(this.errorEl,this);break;case'side':if(!this.errorIcon){elp=this.getErrorCt();if(!elp){this.el.dom.title=msg;break}this.errorIcon=elp.createChild({cls:'x-form-invalid-icon'})}this.alignErrorIcon();Ext.apply(this.errorIcon.dom,{qtip:msg,qclass:'x-form-invalid-tip'});this.errorIcon.show();this.on('resize',this.alignErrorIcon,this);break;default:t=Ext.getDom(this.msgTarget);t.innerHTML=msg;t.style.display=this.msgDisplay;break}this.fireEvent('invalid',this,msg)},clearInvalid:function(){if(!this.rendered||this.preventMark){return}this.outerWrapEl.removeClass(this.invalidClass);switch(this.msgTarget){case'qtip':this.el.dom.qtip='';this.wrapEl.dom.qtip='';break;case'title':this.el.dom.title='';this.wrapEl.dom.title='';this.outerWrapEl.dom.title='';break;case'under':if(this.errorEl){Ext.form.Field.msgFx[this.msgFx].hide(this.errorEl,this)}break;case'side':if(this.errorIcon){this.errorIcon.dom.qtip='';this.errorIcon.hide();this.un('resize',this.alignErrorIcon,this)}break;default:var t=Ext.getDom(this.msgTarget);t.innerHTML='';t.style.display='none';break}this.fireEvent('valid',this)},alignErrorIcon:function(){if(this.wrap){this.errorIcon.alignTo(this.wrap,'tl-tr',[Ext.isIE?5:2,3])}},expand:function(){if(this.isExpanded()||!this.hasFocus){return}if(this.bufferSize){this.doResize(this.bufferSize);delete this.bufferSize}this.list.alignTo(this.outerWrapEl,this.listAlign).show();this.innerList.setOverflow('auto');this.mon(Ext.getDoc(),{scope:this,mousewheel:this.collapseIf,mousedown:this.collapseIf});this.fireEvent('expand',this)},restrictHeight:function(){var inner=this.innerList.dom,st=inner.scrollTop,list=this.list;inner.style.height='';var pad=list.getFrameWidth('tb')+(this.resizable?this.handleHeight:0)+this.assetHeight,h=Math.max(inner.clientHeight,inner.offsetHeight,inner.scrollHeight),ha=this.getPosition()[1]-Ext.getBody().getScroll().top,hb=Ext.lib.Dom.getViewHeight()-ha-this.getSize().height,space=Math.max(ha,hb,this.minHeight||0)-list.shadowOffset-pad-5;h=Math.min(h,space,this.maxHeight);this.innerList.setHeight(h);list.beginUpdate();list.setHeight(h+pad);list.alignTo(this.outerWrapEl,this.listAlign);list.endUpdate();if(this.multiSelectMode){inner.scrollTop=st}},validateValue:function(val){if(this.items.getCount()===0){if(this.allowBlank){this.clearInvalid();return true}else{this.markInvalid(this.blankText);return false}}this.clearInvalid();return true},manageNameAttribute:function(){if(this.items.getCount()===0&&this.forceFormValue){this.el.dom.setAttribute('name',this.hiddenName||this.name)}else{this.el.dom.removeAttribute('name')}},setupFormInterception:function(){var form;this.findParentBy(function(p){if(p.getForm){form=p.getForm()}});if(form){var formGet=form.getValues;form.getValues=function(asString){this.el.dom.disabled=true;var oldVal=this.el.dom.value;this.setRawValue('');var vals=formGet.call(form);this.el.dom.disabled=false;this.setRawValue(oldVal);if(this.forceFormValue&&this.items.getCount()===0){vals[this.name]=''}return asString?Ext.urlEncode(vals):vals}.createDelegate(this)}},onResize:function(w,h,rw,rh){var reduce=Ext.isIE6?4:Ext.isIE7?1:Ext.isIE8?1:0;if(this.wrapEl){this._width=w;this.outerWrapEl.setWidth(w-reduce);if(this.renderFieldBtns){reduce+=(this.buttonWrap.getWidth()+20);this.wrapEl.setWidth(w-reduce)}}Ext.ux.form.SuperBoxSelect.superclass.onResize.call(this,w,h,rw,rh);this.autoSize()},onEnable:function(){Ext.ux.form.SuperBoxSelect.superclass.onEnable.call(this);this.items.each(function(item){item.enable()});if(this.renderFieldBtns){this.initButtonEvents()}},onDisable:function(){Ext.ux.form.SuperBoxSelect.superclass.onDisable.call(this);this.items.each(function(item){item.disable()});if(this.renderFieldBtns){this.removeButtonEvents()}},clearValue:function(supressRemoveEvent){Ext.ux.form.SuperBoxSelect.superclass.clearValue.call(this);this.preventMultipleRemoveEvents=supressRemoveEvent||this.supressClearValueRemoveEvents||false;this.removeAllItems();this.preventMultipleRemoveEvents=false;this.fireEvent('clear',this);return this},fireNewItemEvent:function(val){this.view.clearSelections();this.collapse();this.setRawValue('');if(this.queryFilterRe){val=val.replace(this.queryFilterRe,'');if(!val){return}}this.fireEvent('newitem',this,val,this.filteredQueryData)},onKeyUp:function(e){if(this.editable!==false&&(!e.isSpecialKey()||e.getKey()===e.BACKSPACE)&&this.itemDelimiterKey.indexOf!==e.getKey()&&(!e.hasModifier()||e.shiftKey)){this.lastKey=e.getKey();this.dqTask.delay(this.queryDelay)}},onKeyDownHandler:function(e,t){var toDestroy,nextFocus,idx;if(e.getKey()===e.ESC){if(!this.isExpanded()){if(this.el.dom.value!=''&&(this.clearOnEscape||this.clearLastQueryOnEscape)){if(this.clearOnEscape){this.el.dom.value=''}if(this.clearLastQueryOnEscape){this.lastQuery=''}e.stopEvent()}}}if((e.getKey()===e.DELETE||e.getKey()===e.SPACE)&&this.currentFocus){e.stopEvent();toDestroy=this.currentFocus;this.on('expand',function(){this.collapse()},this,{single:true});idx=this.items.indexOfKey(this.currentFocus.key);this.clearCurrentFocus();if(idx<(this.items.getCount()-1)){nextFocus=this.items.itemAt(idx+1)}toDestroy.preDestroy(true);if(nextFocus){(function(){nextFocus.onLnkFocus();this.currentFocus=nextFocus}).defer(200,this)}return true}var val=this.el.dom.value,it,ctrl=e.ctrlKey;if(this.itemDelimiterKey===e.getKey()){e.stopEvent();if(val!==""){if(ctrl||!this.isExpanded()){this.fireNewItemEvent(val)}else{this.onViewClick();if(this.unsetDelayCheck){this.delayedCheck=true;this.unsetDelayCheck.defer(10,this)}}}else{if(!this.isExpanded()){return}this.onViewClick();if(this.unsetDelayCheck){this.delayedCheck=true;this.unsetDelayCheck.defer(10,this)}}return true}if(val!==''){this.autoSize();return}if(e.getKey()===e.HOME){e.stopEvent();if(this.items.getCount()>0){this.collapse();it=this.items.get(0);it.el.focus()}return true}if(e.getKey()===e.BACKSPACE){e.stopEvent();if(this.currentFocus){toDestroy=this.currentFocus;this.on('expand',function(){this.collapse()},this,{single:true});idx=this.items.indexOfKey(toDestroy.key);this.clearCurrentFocus();if(idx<(this.items.getCount()-1)){nextFocus=this.items.itemAt(idx+1)}toDestroy.preDestroy(true);if(nextFocus){(function(){nextFocus.onLnkFocus();this.currentFocus=nextFocus}).defer(200,this)}return}else{it=this.items.get(this.items.getCount()-1);if(it){if(this.backspaceDeletesLastItem){this.on('expand',function(){this.collapse()},this,{single:true});it.preDestroy(true)}else{if(this.navigateItemsWithTab){it.onElClick()}else{this.on('expand',function(){this.collapse();this.currentFocus=it;this.currentFocus.onLnkFocus.defer(20,this.currentFocus)},this,{single:true})}}}return true}}if(!e.isNavKeyPress()){this.multiSelectMode=false;this.clearCurrentFocus();return}if(e.getKey()===e.LEFT||(e.getKey()===e.UP&&!this.isExpanded())){e.stopEvent();this.collapse();it=this.items.get(this.items.getCount()-1);if(this.navigateItemsWithTab){if(it){it.focus()}}else{if(this.currentFocus){idx=this.items.indexOfKey(this.currentFocus.key);this.clearCurrentFocus();if(idx!==0){this.currentFocus=this.items.itemAt(idx-1);this.currentFocus.onLnkFocus()}}else{this.currentFocus=it;if(it){it.onLnkFocus()}}}return true}if(e.getKey()===e.DOWN){if(this.currentFocus){this.collapse();e.stopEvent();idx=this.items.indexOfKey(this.currentFocus.key);if(idx==(this.items.getCount()-1)){this.clearCurrentFocus.defer(10,this)}else{this.clearCurrentFocus();this.currentFocus=this.items.itemAt(idx+1);if(this.currentFocus){this.currentFocus.onLnkFocus()}}return true}}if(e.getKey()===e.RIGHT){this.collapse();it=this.items.itemAt(0);if(this.navigateItemsWithTab){if(it){it.focus()}}else{if(this.currentFocus){idx=this.items.indexOfKey(this.currentFocus.key);this.clearCurrentFocus();if(idx<(this.items.getCount()-1)){this.currentFocus=this.items.itemAt(idx+1);if(this.currentFocus){this.currentFocus.onLnkFocus()}}}else{this.currentFocus=it;if(it){it.onLnkFocus()}}}}},onKeyUpBuffered:function(e){if(!e.isNavKeyPress()){this.autoSize()}},reset:function(){this.killItems();Ext.ux.form.SuperBoxSelect.superclass.reset.call(this);this.addedRecords=[];this.autoSize().setRawValue('')},applyEmptyText:function(){this.setRawValue('');if(this.items.getCount()>0){this.el.removeClass(this.emptyClass);this.setRawValue('');return this}if(this.rendered&&this.emptyText&&this.getRawValue().length<1){this.setRawValue(this.emptyText);this.el.addClass(this.emptyClass)}return this},removeAllItems:function(){this.items.each(function(item){item.preDestroy(true)},this);this.manageClearBtn();return this},killItems:function(){this.items.each(function(item){item.kill()},this);this.resetStore();this.items.clear();this.manageClearBtn();return this},resetStore:function(){this.store.clearFilter();if(!this.removeValuesFromStore){return this}this.usedRecords.each(function(rec){this.store.add(rec)},this);this.usedRecords.clear();if(!this.store.remoteSort){this.store.sort(this.displayField,'ASC')}return this},sortStore:function(){var ss=this.store.getSortState();if(ss&&ss.field){this.store.sort(ss.field,ss.direction)}return this},getCaption:function(dataObject){if(typeof this.displayFieldTpl==='string'){this.displayFieldTpl=new Ext.XTemplate(this.displayFieldTpl)}var caption,recordData=dataObject instanceof Ext.data.Record?dataObject.data:dataObject;if(this.displayFieldTpl){caption=this.displayFieldTpl.apply(recordData)}else if(this.displayField){caption=recordData[this.displayField]}return caption},addRecord:function(record){var display=record.data[this.displayField],caption=this.getCaption(record),val=record.data[this.valueField],cls=this.classField?record.data[this.classField]:'',style=this.styleField?record.data[this.styleField]:'';if(this.removeValuesFromStore){this.usedRecords.add(val,record);this.store.remove(record)}this.addItemBox(val,display,caption,cls,style);this.fireEvent('additem',this,val,record)},createRecord:function(recordData){if(!this.recordConstructor){var recordFields=[{name:this.valueField},{name:this.displayField}];if(this.classField){recordFields.push({name:this.classField})}if(this.styleField){recordFields.push({name:this.styleField})}this.recordConstructor=Ext.data.Record.create(recordFields)}return new this.recordConstructor(recordData)},addItems:function(newItemObjects){if(Ext.isArray(newItemObjects)){Ext.each(newItemObjects,function(item){this.addItem(item)},this)}else{this.addItem(newItemObjects)}},addNewItem:function(newItemObject){this.addItem(newItemObject,true)},addItem:function(newItemObject,forcedAdd){var val=newItemObject[this.valueField];if(this.disabled){return false}if(this.preventDuplicates&&this.hasValue(val)){return}var record=this.findRecord(this.valueField,val);if(record){this.addRecord(record);return}else if(!this.allowAddNewData){return}if(this.mode==='remote'){this.remoteLookup.push(newItemObject);this.doQuery(val,false,false,forcedAdd);return}var rec=this.createRecord(newItemObject);this.store.add(rec);this.addRecord(rec);return true;},addItemBox:function(itemVal,itemDisplay,itemCaption,itemClass,itemStyle){var hConfig,parseStyle=function(s){var ret='';switch(typeof s){case'function':ret=s.call();break;case'object':for(var p in s){ret+=p+':'+s[p]+';'}break;case'string':ret=s+';'}return ret},itemKey=Ext.id(null,'sbx-item'),box=new Ext.ux.form.SuperBoxSelectItem({owner:this,disabled:this.disabled,renderTo:this.wrapEl,cls:this.extraItemCls+' '+itemClass,style:parseStyle(this.extraItemStyle)+' '+itemStyle,caption:itemCaption,display:itemDisplay,value:itemVal,key:itemKey,listeners:{'remove':function(item){if(this.fireEvent('beforeremoveitem',this,item.value)===false){return false}this.items.removeKey(item.key);if(this.removeValuesFromStore){if(this.usedRecords.containsKey(item.value)){this.store.add(this.usedRecords.get(item.value));this.usedRecords.removeKey(item.value);this.sortStore();if(this.view){this.view.render()}}}if(!this.preventMultipleRemoveEvents){this.fireEvent.defer(250,this,['removeitem',this,item.value,this.findInStore(item.value)])}},destroy:function(){this.collapse();this.autoSize().manageClearBtn().validateValue()},scope:this}});box.render();hConfig={tag:'input',type:'hidden',value:itemVal,name:(this.hiddenName||this.name)};if(this.disabled){Ext.apply(hConfig,{disabled:'disabled'})}box.hidden=this.el.insertSibling(hConfig,'before');this.items.add(itemKey,box);this.applyEmptyText().autoSize().manageClearBtn().validateValue()},manageClearBtn:function(){if(!this.renderFieldBtns||!this.rendered){return this}var cls='x-superboxselect-btn-hide';if(this.items.getCount()===0){this.buttonClear.addClass(cls)}else{this.buttonClear.removeClass(cls)}return this},findInStore:function(val){var index=this.store.find(this.valueField,val);if(index>-1){return this.store.getAt(index)}return false},getSelectedRecords:function(){var ret=[];if(this.removeValuesFromStore){ret=this.usedRecords.getRange()}else{var vals=[];this.items.each(function(item){vals.push(item.value)});Ext.each(vals,function(val){ret.push(this.findInStore(val))},this)}return ret},findSelectedItem:function(el){var ret;this.items.each(function(item){if(item.el.dom===el){ret=item;return false}});return ret},findSelectedRecord:function(el){var ret,item=this.findSelectedItem(el);if(item){ret=this.findSelectedRecordByValue(item.value)}return ret},findSelectedRecordByValue:function(val){var ret;if(this.removeValuesFromStore){this.usedRecords.each(function(rec){if(rec.get(this.valueField)==val){ret=rec;return false}},this)}else{ret=this.findInStore(val)}return ret},getValue:function(){var ret=[];this.items.each(function(item){ret.push(item.value)});return ret.join(this.valueDelimiter)},getCount:function(){return this.items.getCount()},getValueEx:function(){var ret=[];this.items.each(function(item){var newItem={};newItem[this.valueField]=item.value;newItem[this.displayField]=item.display;if(this.classField){newItem[this.classField]=item.cls||''}if(this.styleField){newItem[this.styleField]=item.style||''}ret.push(newItem)},this);return ret},initValue:function(){if(Ext.isObject(this.value)||Ext.isArray(this.value)){this.setValueEx(this.value);this.originalValue=this.getValue()}else{Ext.ux.form.SuperBoxSelect.superclass.initValue.call(this)}if(this.mode==='remote'){this.setOriginal=true}},addValue:function(value){if(Ext.isEmpty(value)){return}var values=value;if(!Ext.isArray(value)){value=''+value;values=value.split(this.valueDelimiter)}Ext.each(values,function(val){var record=this.findRecord(this.valueField,val);if(record){this.addRecord(record)}else if(this.mode==='remote'){this.remoteLookup.push(val)}},this);if(this.mode==='remote'){var q=this.remoteLookup.join(this.queryValuesDelimiter);this.doQuery(q,false,true)}},setValue:function(value){if(!this.rendered){this.value=value;return}this.removeAllItems().resetStore();this.remoteLookup=[];this.addValue(value)},setValueEx:function(data){if(!this.rendered){this.value=data;return}this.removeAllItems().resetStore();if(!Ext.isArray(data)){data=[data]}this.remoteLookup=[];if(this.allowAddNewData&&this.mode==='remote'){Ext.each(data,function(d){var r=this.findRecord(this.valueField,d[this.valueField])||this.createRecord(d);this.addRecord(r)},this);return}Ext.each(data,function(item){this.addItem(item)},this)},hasValue:function(val){var has=false;this.items.each(function(item){if(item.value==val){has=true;return false}},this);return has},onSelect:function(record,index){if(this.fireEvent('beforeselect',this,record,index)!==false){var val=record.data[this.valueField];if(this.preventDuplicates&&this.hasValue(val)){return}this.setRawValue('');this.lastSelectionText='';if(this.fireEvent('beforeadditem',this,val,record,this.filteredQueryData)!==false){this.addRecord(record)}if(this.store.getCount()===0||!this.multiSelectMode){this.collapse()}else{this.restrictHeight()}}},onDestroy:function(){this.items.purgeListeners();this.killItems();if(this.allowQueryAll){Ext.destroy(this.buttonExpand)}if(this.renderFieldBtns){Ext.destroy(this.buttonClear,this.buttonWrap)}Ext.destroy(this.inputEl,this.wrapEl,this.outerWrapEl);Ext.ux.form.SuperBoxSelect.superclass.onDestroy.call(this)},autoSize:function(){if(!this.rendered){return this}if(!this.metrics){this.metrics=Ext.util.TextMetrics.createInstance(this.el)}var el=this.el,v=el.dom.value,d=document.createElement('div');if(v===""&&this.emptyText&&this.items.getCount()<1){v=this.emptyText}d.appendChild(document.createTextNode(v));v=d.innerHTML;d=null;v+="&#160;";var w=Math.max(this.metrics.getWidth(v)+24,24);if(typeof this._width!='undefined'){w=Math.min(this._width,w)}this.el.setWidth(w);if(Ext.isIE){this.el.dom.style.top='0'}this.fireEvent('autosize',this,w);return this},shouldQuery:function(q){if(this.lastQuery){var m=q.match("^"+this.lastQuery);if(!m||this.store.getCount()){return true}else{return(m[0]!==this.lastQuery)}}return true},doQuery:function(q,forceAll,valuesQuery,forcedAdd){q=Ext.isEmpty(q)?'':q;if(this.queryFilterRe){this.filteredQueryData='';var m=q.match(this.queryFilterRe);if(m&&m.length){this.filteredQueryData=m[0]}q=q.replace(this.queryFilterRe,'');if(!q&&m){return}}var qe={query:q,forceAll:forceAll,combo:this,cancel:false};if(this.fireEvent('beforequery',qe)===false||qe.cancel){return false}q=qe.query;forceAll=qe.forceAll;if(forceAll===true||(q.length>=this.minChars)||valuesQuery&&!Ext.isEmpty(q)){if(forcedAdd||this.forceSameValueQuery||this.shouldQuery(q)){this.lastQuery=q;if(this.mode=='local'){this.selectedIndex=-1;if(forceAll){this.store.clearFilter()}else{this.store.filter(this.displayField,q)}this.onLoad()}else{this.store.baseParams[this.queryParam]=q;this.store.baseParams[this.queryValuesIndicator]=valuesQuery;this.store.load({params:this.getParams(q)});if(!forcedAdd){this.expand()}}}else{this.selectedIndex=-1;this.onLoad()}}},onStoreLoad:function(store,records,options){var q=options.params[this.queryParam]||store.baseParams[this.queryParam]||"",isValuesQuery=options.params[this.queryValuesIndicator]||store.baseParams[this.queryValuesIndicator];if(this.removeValuesFromStore){this.store.each(function(record){if(this.usedRecords.containsKey(record.get(this.valueField))){this.store.remove(record)}},this)}if(isValuesQuery){var params=q.split(this.queryValuesDelimiter);Ext.each(params,function(p){this.remoteLookup.remove(p);var rec=this.findRecord(this.valueField,p);if(rec){this.addRecord(rec)}},this);if(this.setOriginal){this.setOriginal=false;this.originalValue=this.getValue()}}if(q!==''&&this.allowAddNewData){Ext.each(this.remoteLookup,function(r){if(typeof r==="object"&&r[this.valueField]===q){this.remoteLookup.remove(r);if(records.length&&records[0].get(this.valueField)===q){this.addRecord(records[0]);return}var rec=this.createRecord(r);this.store.add(rec);this.addRecord(rec);this.addedRecords.push(rec);(function(){if(this.isExpanded()){this.collapse()}}).defer(10,this);return}},this)}var toAdd=[];if(q===''){Ext.each(this.addedRecords,function(rec){if(this.preventDuplicates&&this.usedRecords.containsKey(rec.get(this.valueField))){return}toAdd.push(rec)},this)}else{var re=new RegExp(Ext.escapeRe(q)+'.*','i');Ext.each(this.addedRecords,function(rec){if(this.preventDuplicates&&this.usedRecords.containsKey(rec.get(this.valueField))){return}if(re.test(rec.get(this.displayField))){toAdd.push(rec)}},this)}this.store.add(toAdd);this.sortStore();if(this.store.getCount()===0&&this.isExpanded()){this.collapse()}}});Ext.reg('superboxselect',Ext.ux.form.SuperBoxSelect);Ext.ux.form.SuperBoxSelectItem=function(config){Ext.apply(this,config);Ext.ux.form.SuperBoxSelectItem.superclass.constructor.call(this)};Ext.ux.form.SuperBoxSelectItem=Ext.extend(Ext.ux.form.SuperBoxSelectItem,Ext.Component,{initComponent:function(){Ext.ux.form.SuperBoxSelectItem.superclass.initComponent.call(this)},onElClick:function(e){var o=this.owner;o.clearCurrentFocus().collapse();if(o.navigateItemsWithTab){this.focus()}else{o.el.dom.focus();var that=this;(function(){this.onLnkFocus();o.currentFocus=this}).defer(10,this)}},onLnkClick:function(e){if(e){e.stopEvent()}this.preDestroy();if(!this.owner.navigateItemsWithTab){this.owner.el.focus()}},onLnkFocus:function(){this.el.addClass("x-superboxselect-item-focus");this.owner.outerWrapEl.addClass("x-form-focus")},onLnkBlur:function(){this.el.removeClass("x-superboxselect-item-focus");this.owner.outerWrapEl.removeClass("x-form-focus")},enableElListeners:function(){this.el.on('click',this.onElClick,this,{stopEvent:true});this.el.addClassOnOver('x-superboxselect-item x-superboxselect-item-hover')},enableLnkListeners:function(){this.lnk.on({click:this.onLnkClick,focus:this.onLnkFocus,blur:this.onLnkBlur,scope:this})},enableAllListeners:function(){this.enableElListeners();this.enableLnkListeners()},disableAllListeners:function(){this.el.removeAllListeners();this.lnk.un('click',this.onLnkClick,this);this.lnk.un('focus',this.onLnkFocus,this);this.lnk.un('blur',this.onLnkBlur,this)},onRender:function(ct,position){Ext.ux.form.SuperBoxSelectItem.superclass.onRender.call(this,ct,position);var el=this.el;if(el){el.remove()}this.el=el=ct.createChild({tag:'li'},ct.last());el.addClass('x-superboxselect-item');var btnEl=this.owner.navigateItemsWithTab?(Ext.isSafari?'button':'a'):'span';var itemKey=this.key;Ext.apply(el,{focus:function(){var c=this.down(btnEl+'.x-superboxselect-item-close');if(c){c.focus()}},preDestroy:function(){this.preDestroy()}.createDelegate(this)});this.enableElListeners();el.update(this.caption);var cfg={tag:btnEl,'class':'x-superboxselect-item-close',tabIndex:this.owner.navigateItemsWithTab?'0':'-1'};if(btnEl==='a'){cfg.href='#'}this.lnk=el.createChild(cfg);if(!this.disabled){this.enableLnkListeners()}else{this.disableAllListeners()}this.on({disable:this.disableAllListeners,enable:this.enableAllListeners,scope:this});this.setupKeyMap()},setupKeyMap:function(){this.keyMap=new Ext.KeyMap(this.lnk,[{key:[Ext.EventObject.BACKSPACE,Ext.EventObject.DELETE,Ext.EventObject.SPACE],fn:this.preDestroy,scope:this},{key:[Ext.EventObject.RIGHT,Ext.EventObject.DOWN],fn:function(){this.moveFocus('right')},scope:this},{key:[Ext.EventObject.LEFT,Ext.EventObject.UP],fn:function(){this.moveFocus('left')},scope:this},{key:[Ext.EventObject.HOME],fn:function(){var l=this.owner.items.get(0).el.focus();if(l){l.el.focus()}},scope:this},{key:[Ext.EventObject.END],fn:function(){this.owner.el.focus()},scope:this},{key:Ext.EventObject.ENTER,fn:function(){}}]);this.keyMap.stopEvent=true},moveFocus:function(dir){var el=this.el[dir=='left'?'prev':'next']()||this.owner.el;el.focus.defer(100,el)},preDestroy:function(supressEffect){if(this.fireEvent('remove',this)===false){return}var actionDestroy=function(){if(this.owner.navigateItemsWithTab){this.moveFocus('right')}this.hidden.remove();this.hidden=null;this.destroy()};if(supressEffect){actionDestroy.call(this)}else{this.el.hide({duration:0.2,callback:actionDestroy,scope:this})}return this},kill:function(){this.hidden.remove();this.hidden=null;this.purgeListeners();this.destroy()},onDisable:function(){if(this.hidden){this.hidden.dom.setAttribute('disabled','disabled')}this.keyMap.disable();Ext.ux.form.SuperBoxSelectItem.superclass.onDisable.call(this)},onEnable:function(){if(this.hidden){this.hidden.dom.removeAttribute('disabled')}this.keyMap.enable();Ext.ux.form.SuperBoxSelectItem.superclass.onEnable.call(this)},onDestroy:function(){Ext.destroy(this.lnk,this.el);Ext.ux.form.SuperBoxSelectItem.superclass.onDestroy.call(this)}});
Ext.reg('superbox',Ext.ux.form.SuperBoxSelectItem);

Ext.onReady(function() {
    MODx.util.JSONReader = MODx.load({ xtype: 'modx-json-reader' });
    MODx.form.Handler = MODx.load({ xtype: 'modx-form-handler' });
    MODx.msg = MODx.load({ xtype: 'modx-msg' });
});
/* always-submit checkboxes */
Ext.form.XCheckbox=Ext.extend(Ext.form.Checkbox,{submitOffValue:0,submitOnValue:1,onRender:function(){this.inputValue=this.submitOnValue;Ext.form.XCheckbox.superclass.onRender.apply(this,arguments);this.hiddenField=this.wrap.insertFirst({tag:'input',type:'hidden'});if(this.tooltip){this.imageEl.set({qtip:this.tooltip})}this.updateHidden()},setValue:function(v){v=this.convertValue(v);this.updateHidden(v);Ext.form.XCheckbox.superclass.setValue.apply(this,arguments)},updateHidden:function(v){v=undefined!==v?v:this.checked;v=this.convertValue(v);if(this.hiddenField){this.hiddenField.dom.value=v?this.submitOnValue:this.submitOffValue;this.hiddenField.dom.name=v?'':this.el.dom.name}},convertValue:function(v){return(v===true||v==='true'||v===this.submitOnValue||String(v).toLowerCase()==='on')}});Ext.reg('xcheckbox',Ext.form.XCheckbox);

/* drag/drop grids */
Ext.namespace('Ext.ux.dd');Ext.ux.dd.GridDragDropRowOrder=Ext.extend(Ext.util.Observable,{copy:false,scrollable:false,constructor:function(config){if(config)Ext.apply(this,config);this.addEvents({beforerowmove:true,afterrowmove:true,beforerowcopy:true,afterrowcopy:true});Ext.ux.dd.GridDragDropRowOrder.superclass.constructor.call(this)},init:function(grid){this.grid=grid;grid.enableDragDrop=true;grid.on({render:{fn:this.onGridRender,scope:this,single:true}})},onGridRender:function(grid){var self=this;this.target=new Ext.dd.DropTarget(grid.getEl(),{ddGroup:grid.ddGroup||'GridDD',grid:grid,gridDropTarget:this,notifyDrop:function(dd,e,data){if(this.currentRowEl){this.currentRowEl.removeClass('grid-row-insert-below');this.currentRowEl.removeClass('grid-row-insert-above')}var t=Ext.lib.Event.getTarget(e);var rindex=this.grid.getView().findRowIndex(t);if(rindex===false||rindex==data.rowIndex){return false}if(this.gridDropTarget.fireEvent(self.copy?'beforerowcopy':'beforerowmove',this.gridDropTarget,data.rowIndex,rindex,data.selections,123)===false){return false}var ds=this.grid.getStore();var selections=new Array();var keys=ds.data.keys;for(var key in keys){for(var i=0;i<data.selections.length;i++){if(keys[key]==data.selections[i].id){if(rindex==key){return false}selections.push(data.selections[i])}}}if(rindex>data.rowIndex&&this.rowPosition<0){rindex--}if(rindex<data.rowIndex&&this.rowPosition>0){rindex++}if(rindex>data.rowIndex&&data.selections.length>1){rindex=rindex-(data.selections.length-1)}if(rindex==data.rowIndex){return false}if(!self.copy){for(var i=0;i<data.selections.length;i++){ds.remove(ds.getById(data.selections[i].id))}}for(var i=selections.length-1;i>=0;i--){var insertIndex=rindex;ds.insert(insertIndex,selections[i])}var sm=this.grid.getSelectionModel();if(sm){sm.selectRecords(data.selections)}this.gridDropTarget.fireEvent(self.copy?'afterrowcopy':'afterrowmove',this.gridDropTarget,data.rowIndex,rindex,data.selections);return true},notifyOver:function(dd,e,data){var t=Ext.lib.Event.getTarget(e);var rindex=this.grid.getView().findRowIndex(t);var ds=this.grid.getStore();var keys=ds.data.keys;for(var key in keys){for(var i=0;i<data.selections.length;i++){if(keys[key]==data.selections[i].id){if(rindex==key){if(this.currentRowEl){this.currentRowEl.removeClass('grid-row-insert-below');this.currentRowEl.removeClass('grid-row-insert-above')}return this.dropNotAllowed}}}}if(rindex<0||rindex===false){this.currentRowEl.removeClass('grid-row-insert-above');return this.dropNotAllowed}try{var currentRow=this.grid.getView().getRow(rindex);var resolvedRow=new Ext.Element(currentRow).getY()-this.grid.getView().scroller.dom.scrollTop;var rowHeight=currentRow.offsetHeight;this.rowPosition=e.getPageY()-resolvedRow-(rowHeight/2);if(this.currentRowEl){this.currentRowEl.removeClass('grid-row-insert-below');this.currentRowEl.removeClass('grid-row-insert-above')}if(this.rowPosition>0){this.currentRowEl=new Ext.Element(currentRow);this.currentRowEl.addClass('grid-row-insert-below')}else{if(rindex-1>=0){var previousRow=this.grid.getView().getRow(rindex-1);this.currentRowEl=new Ext.Element(previousRow);this.currentRowEl.addClass('grid-row-insert-below')}else{this.currentRowEl.addClass('grid-row-insert-above')}}}catch(err){console.warn(err);rindex=false}return(rindex===false)?this.dropNotAllowed:this.dropAllowed},notifyOut:function(dd,e,data){if(this.currentRowEl){this.currentRowEl.removeClass('grid-row-insert-above');this.currentRowEl.removeClass('grid-row-insert-below')}}});if(this.targetCfg){Ext.apply(this.target,this.targetCfg)}if(this.scrollable){Ext.dd.ScrollManager.register(grid.getView().getEditorParent());grid.on({beforedestroy:this.onBeforeDestroy,scope:this,single:true})}},getTarget:function(){return this.target},getGrid:function(){return this.grid},getCopy:function(){return this.copy?true:false},setCopy:function(b){this.copy=b?true:false},onBeforeDestroy:function(grid){Ext.dd.ScrollManager.unregister(grid.getView().getEditorParent())}});


/** selectability in Ext grids */
if (!Ext.grid.GridView.prototype.templates) {
   Ext.grid.GridView.prototype.templates = {};
}
Ext.grid.GridView.prototype.templates.cell = new Ext.Template(
   '<td class="x-grid3-col x-grid3-cell x-grid3-td-{id} x-selectable {css}" style="{style}" tabIndex="0" {cellAttr}>',
   '<div class="x-grid3-cell-inner x-grid3-col-{id}" {attr}>{value}</div>',
   '</td>'
);

/* combocolumn */
if (!MODx.grid) { MODx.grid = {}; }
MODx.grid.ComboColumn = Ext.extend(Ext.grid.Column,{
    gridId: undefined
    ,constructor: function(cfg){
        MODx.grid.ComboColumn.superclass.constructor.call(this, cfg);
        this.renderer = (this.editor && this.editor.triggerAction) ? MODx.grid.ComboBoxRenderer(this.editor,this.gridId) : function(value) {return value;};
    }
});
Ext.grid.Column.types['combocolumn'] = MODx.grid.ComboColumn;
MODx.grid.ComboBoxRenderer = function(combo, gridId) {
    var getValue = function(value) {
        var idx = combo.store.find(combo.valueField, value);
        var rec = combo.store.getAt(idx);
        if (rec) {
            return rec.get(combo.displayField);
        }
        return value;
    };

    return function(value) {
        if (combo.store.getCount() == 0 && gridId) {
            combo.store.on(
                'load',
                function() {
                    var grid = Ext.getCmp(gridId);
                    if (grid) {
                        grid.getView().refresh();
                    }
                },{single: true}
            );
            return value;
        }
        return getValue(value);
    };
};
