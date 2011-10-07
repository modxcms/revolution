/**
MODExt Revolution 1.0
Copyright (c) 2007-2011, Shaun McCormick
All rights reserved
MODX-specific JS extension for ExtJS 3.0

-------------

The MODExt JS extension is distributed under the terms of the GNU GPLv3 license.
It extends ExtJS, distributed under the Open Source GPL 3.0 license.

http://www.gnu.org/licenses/gpl.html

-------------

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
General Public License for more details.
*/
Ext.onReady(function() {
    if (MODx.config.manager_language == 'en') return false;
    
    Date.dayNames = [
        _('sunday')
        ,_('monday')
        ,_('tuesday')
        ,_('wednesday')
        ,_('thursday')
        ,_('friday')
        ,_('saturday')
    ];
    Date.monthNames = [
        _('january')
        ,_('february')
        ,_('march')
        ,_('april')
        ,_('may')
        ,_('june')
        ,_('july')
        ,_('august')
        ,_('september')
        ,_('october')
        ,_('november')
        ,_('december')
    ];
    Ext.apply(Ext.grid.GridView.prototype, {
        sortAscText: _('ext_sortasc')
        ,sortDescText: _('ext_sortdesc')
        ,lockText: _('ext_column_lock')
        ,unlockText: _('ext_column_unlock')
        ,columnsText: _('ext_columns')
        ,emptyText: _('ext_emptymsg')
    });
    Ext.apply(Ext.DatePicker.prototype, {
        todayText: _('today')
        ,todayTip: _('ext_today_tip')
        ,minText: _('ext_mindate')
        ,maxText: _('ext_maxdate')
        ,monthNames: Date.monthNames
        ,dayNames: Date.dayNames
        ,nextText: _('ext_nextmonth')
        ,prevText: _('ext_prevmonth')
        ,monthYearText: _('ext_choosemonth')
    });
    
    Ext.MessageBox.buttonText = {
        yes: _('yes')
        ,no: _('no')
        ,ok: _('ok')
        ,cancel: _('cancel')
    };
    Ext.apply(Ext.PagingToolbar.prototype,{
        afterPageText: _('ext_afterpage')
        ,beforePageText: _('ext_beforepage')
        ,displayMsg: _('ext_displaying')
        ,emptyMsg: _('ext_emptymsg')
        ,firstText: _('ext_first')
        ,prevText: _('ext_prev')
        ,nextText: _('ext_next')
        ,lastText: _('ext_last')
        ,refreshText: _('ext_refresh')
    });
    Ext.apply(Ext.form.ComboBox.prototype,{
        loadingText: _('loading')
    });
    Ext.apply(Ext.form.Field.prototype,{
        invalidText: _('ext_invalidfield')
    });    
    Ext.apply(Ext.form.TextField.prototype,{
        minLengthText: _('ext_minlenfield')
        ,maxLengthText: _('ext_maxlenfield')
        ,invalidText: _('ext_invalidfield')
        ,blankText: _('field_required') 
    });
    Ext.apply(Ext.form.NumberField.prototype,{
        minText: _('ext_minvalfield')
        ,maxText: _('ext_maxvalfield')
        ,nanText: _('ext_nanfield')
    });
    Ext.apply(Ext.form.DateField.prototype,{
        disabledDaysText: _('disabled')
        ,disabledDatesText: _('disabled')
        ,minText: _('ext_datemin')
        ,maxText: _('ext_datemax')
        ,invalidText: _('ext_dateinv')
    });
    Ext.apply(Ext.form.VTypes,{
        emailText: _('ext_inv_email')
        ,urlText: _('ext_inv_url')
        ,alphaText: _('ext_inv_alpha')
        ,alphanumText: _('ext_inv_alphanum')
    });
    Ext.apply(Ext.grid.GroupingView.prototype,{
        emptyGroupText: _('ext_emptygroup')
        ,groupByText: _('ext_groupby')
        ,showGroupsText: _('ext_showgroups')
    });
    Ext.apply(Ext.grid.PropertyColumnModel.prototype,{
        nameText: _('name')
        ,valueText: _('value')
    });
    Ext.apply(Ext.form.CheckboxGroup.prototype,{
        blankText: _('ext_checkboxinv')
    });
    Ext.apply(Ext.form.RadioGroup.prototype,{
        blankText: _('ext_checkboxinv')
    });
    Ext.apply(Ext.form.TimeField.prototype, {
        minText: _('ext_timemin')
        ,maxText: _('ext_timemax')
        ,invalidText: _('ext_timeinv')
    });
});Ext.namespace('MODx.util.Progress');
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

/* switchbutton */
Ext.SwitchButton=Ext.extend(Ext.Component,{initComponent:function(){Ext.SwitchButton.superclass.initComponent.call(this);var a=new Ext.util.MixedCollection();a.addAll(this.items);this.items=a;this.addEvents('change');if(this.handler){this.on('change',this.handler,this.scope||this)}},onRender:function(a,b){var c=document.createElement('table');c.cellSpacing=0;c.className='x-rbtn';c.id=this.id;var d=document.createElement('tr');c.appendChild(d);var e=this.items.length;var f=e-1;this.activeItem=this.items.get(this.activeItem);for(var i=0;i<e;i++){var g=this.items.itemAt(i);var h=d.appendChild(document.createElement('td'));h.id=this.id+'-rbi-'+i;var j=i===0?'x-rbtn-first':(i==f?'x-rbtn-last':'x-rbtn-item');g.baseCls=j;if(this.activeItem==g){j+='-active'}h.className=j;var k=document.createElement('button');k.innerHTML='&#160;';k.className=g.iconCls;k.title=g.tooltip;h.appendChild(k);g.cell=h}this.el=Ext.get(a.dom.appendChild(c));this.el.on('click',this.onClick,this)},getActiveItem:function(){return this.activeItem},setActiveItem:function(a){if(typeof a!='object'&&a!==null){a=this.items.get(a)}var b=this.getActiveItem();if(a!=b){if(b){Ext.fly(b.cell).removeClass(b.baseCls+'-active')}if(a){Ext.fly(a.cell).addClass(a.baseCls+'-active')}this.activeItem=a;this.fireEvent('change',this,a)}return a},onClick:function(e){var a=e.getTarget('td',2);if(!this.disabled&&a){this.setActiveItem(parseInt(a.id.split('-rbi-')[1],10))}}});Ext.reg('switch',Ext.SwitchButton);

/* superboxselect */
Ext.namespace('Ext.ux.form');Ext.ux.form.SuperBoxSelect=function(config){config.listClass = 'x-superboxselect-list';Ext.ux.form.SuperBoxSelect.superclass.constructor.call(this,config);this.addEvents('beforeadditem','additem','newitem','beforeremoveitem','removeitem','clear')};Ext.ux.form.SuperBoxSelect=Ext.extend(Ext.ux.form.SuperBoxSelect,Ext.form.ComboBox,{addNewDataOnBlur:false,allowAddNewData:false,allowQueryAll:true,backspaceDeletesLastItem:true,classField:null,clearBtnCls:'',clearLastQueryOnEscape:false,clearOnEscape:false,displayFieldTpl:null,extraItemCls:'',extraItemStyle:'',expandBtnCls:'',fixFocusOnTabSelect:true,forceFormValue:true,forceSameValueQuery:false,itemDelimiterKey:Ext.EventObject.ENTER,navigateItemsWithTab:true,pinList:true,preventDuplicates:true,queryFilterRe:'',queryValuesDelimiter:'|',queryValuesIndicator:'valuesqry',removeValuesFromStore:true,renderFieldBtns:true,stackItems:false,styleField:null,supressClearValueRemoveEvents:false,validationEvent:'blur',valueDelimiter:',',initComponent:function(){Ext.apply(this,{items:new Ext.util.MixedCollection(false),usedRecords:new Ext.util.MixedCollection(false),addedRecords:[],remoteLookup:[],hideTrigger:true,grow:false,resizable:false,multiSelectMode:false,preRenderValue:null,filteredQueryData:''});if(this.queryFilterRe){if(Ext.isString(this.queryFilterRe)){this.queryFilterRe=new RegExp(this.queryFilterRe)}}if(this.transform){this.doTransform()}if(this.forceFormValue){this.items.on({add:this.manageNameAttribute,remove:this.manageNameAttribute,clear:this.manageNameAttribute,scope:this})}Ext.ux.form.SuperBoxSelect.superclass.initComponent.call(this);if(this.mode==='remote'&&this.store){this.store.on('load',this.onStoreLoad,this)}},onRender:function(ct,position){var h=this.hiddenName;this.hiddenName=null;Ext.ux.form.SuperBoxSelect.superclass.onRender.call(this,ct,position);this.hiddenName=h;this.manageNameAttribute();var extraClass=(this.stackItems===true)?'x-superboxselect-stacked':'';if(this.renderFieldBtns){extraClass+=' x-superboxselect-display-btns'}this.el.removeClass('x-form-text').addClass('x-superboxselect-input-field');this.wrapEl=this.el.wrap({tag:'ul'});this.outerWrapEl=this.wrapEl.wrap({tag:'div',cls:'x-form-text x-superboxselect '+extraClass});this.inputEl=this.el.wrap({tag:'li',cls:'x-superboxselect-input'});if(this.renderFieldBtns){this.setupFieldButtons().manageClearBtn()}this.setupFormInterception()},doTransform:function(){var s=Ext.getDom(this.transform),transformValues=[];if(!this.store){this.mode='local';var d=[],opts=s.options;for(var i=0,len=opts.length;i<len;i++){var o=opts[i],oe=Ext.get(o),value=oe.getAttributeNS(null,'value')||'',cls=oe.getAttributeNS(null,'className')||'',style=oe.getAttributeNS(null,'style')||'';if(o.selected){transformValues.push(value)}d.push([value,o.text,cls,typeof(style)==="string"?style:style.cssText])}this.store=new Ext.data.SimpleStore({'id':0,fields:['value','text','cls','style'],data:d});Ext.apply(this,{valueField:'value',displayField:'text',classField:'cls',styleField:'style'})}if(transformValues.length){this.value=transformValues.join(',')}},setupFieldButtons:function(){this.buttonWrap=this.outerWrapEl.createChild({cls:'x-superboxselect-btns'});this.buttonClear=this.buttonWrap.createChild({tag:'div',cls:'x-superboxselect-btn-clear '+this.clearBtnCls});if(this.allowQueryAll){this.buttonExpand=this.buttonWrap.createChild({tag:'div',cls:'x-superboxselect-btn-expand '+this.expandBtnCls})}this.initButtonEvents();return this},initButtonEvents:function(){this.buttonClear.addClassOnOver('x-superboxselect-btn-over').on('click',function(e){e.stopEvent();if(this.disabled){return}this.clearValue();this.el.focus()},this);if(this.allowQueryAll){this.buttonExpand.addClassOnOver('x-superboxselect-btn-over').on('click',function(e){e.stopEvent();if(this.disabled){return}if(this.isExpanded()){this.multiSelectMode=false}else if(this.pinList){this.multiSelectMode=true}this.onTriggerClick()},this)}},removeButtonEvents:function(){this.buttonClear.removeAllListeners();if(this.allowQueryAll){this.buttonExpand.removeAllListeners()}return this},clearCurrentFocus:function(){if(this.currentFocus){this.currentFocus.onLnkBlur();this.currentFocus=null}return this},initEvents:function(){var el=this.el;el.on({click:this.onClick,focus:this.clearCurrentFocus,blur:this.onBlur,keydown:this.onKeyDownHandler,keyup:this.onKeyUpBuffered,scope:this});this.on({collapse:this.onCollapse,expand:this.clearCurrentFocus,scope:this});this.wrapEl.on('click',this.onWrapClick,this);this.outerWrapEl.on('click',this.onWrapClick,this);this.inputEl.focus=function(){el.focus()};Ext.ux.form.SuperBoxSelect.superclass.initEvents.call(this);Ext.apply(this.keyNav,{tab:function(e){if(this.fixFocusOnTabSelect&&this.isExpanded()){e.stopEvent();el.blur();this.onViewClick(false);this.focus(false,10);return true}this.onViewClick(false);if(el.dom.value!==''){this.setRawValue('')}return true},down:function(e){if(!this.isExpanded()&&!this.currentFocus){if(this.allowQueryAll){this.onTriggerClick()}}else{this.inKeyMode=true;this.selectNext()}},enter:function(){}})},onClick:function(){this.clearCurrentFocus();this.collapse();this.autoSize()},beforeBlur:function(){if(this.allowAddNewData&&this.addNewDataOnBlur){var v=this.el.dom.value;if(v!==''){this.fireNewItemEvent(v)}}Ext.form.ComboBox.superclass.beforeBlur.call(this)},onFocus:function(){this.outerWrapEl.addClass(this.focusClass);Ext.ux.form.SuperBoxSelect.superclass.onFocus.call(this)},onBlur:function(){this.outerWrapEl.removeClass(this.focusClass);this.clearCurrentFocus();if(this.el.dom.value!==''){this.applyEmptyText();this.autoSize()}Ext.ux.form.SuperBoxSelect.superclass.onBlur.call(this)},onCollapse:function(){this.view.clearSelections();this.multiSelectMode=false},onWrapClick:function(e){e.stopEvent();this.collapse();this.el.focus();this.clearCurrentFocus()},markInvalid:function(msg){var elp,t;if(!this.rendered||this.preventMark){return}this.outerWrapEl.addClass(this.invalidClass);msg=msg||this.invalidText;switch(this.msgTarget){case'qtip':Ext.apply(this.el.dom,{qtip:msg,qclass:'x-form-invalid-tip'});Ext.apply(this.wrapEl.dom,{qtip:msg,qclass:'x-form-invalid-tip'});if(Ext.QuickTips){Ext.QuickTips.enable()}break;case'title':this.el.dom.title=msg;this.wrapEl.dom.title=msg;this.outerWrapEl.dom.title=msg;break;case'under':if(!this.errorEl){elp=this.getErrorCt();if(!elp){this.el.dom.title=msg;break}this.errorEl=elp.createChild({cls:'x-form-invalid-msg'});this.errorEl.setWidth(elp.getWidth(true)-20)}this.errorEl.update(msg);Ext.form.Field.msgFx[this.msgFx].show(this.errorEl,this);break;case'side':if(!this.errorIcon){elp=this.getErrorCt();if(!elp){this.el.dom.title=msg;break}this.errorIcon=elp.createChild({cls:'x-form-invalid-icon'})}this.alignErrorIcon();Ext.apply(this.errorIcon.dom,{qtip:msg,qclass:'x-form-invalid-tip'});this.errorIcon.show();this.on('resize',this.alignErrorIcon,this);break;default:t=Ext.getDom(this.msgTarget);t.innerHTML=msg;t.style.display=this.msgDisplay;break}this.fireEvent('invalid',this,msg)},clearInvalid:function(){if(!this.rendered||this.preventMark){return}this.outerWrapEl.removeClass(this.invalidClass);switch(this.msgTarget){case'qtip':this.el.dom.qtip='';this.wrapEl.dom.qtip='';break;case'title':this.el.dom.title='';this.wrapEl.dom.title='';this.outerWrapEl.dom.title='';break;case'under':if(this.errorEl){Ext.form.Field.msgFx[this.msgFx].hide(this.errorEl,this)}break;case'side':if(this.errorIcon){this.errorIcon.dom.qtip='';this.errorIcon.hide();this.un('resize',this.alignErrorIcon,this)}break;default:var t=Ext.getDom(this.msgTarget);t.innerHTML='';t.style.display='none';break}this.fireEvent('valid',this)},alignErrorIcon:function(){if(this.wrap){this.errorIcon.alignTo(this.wrap,'tl-tr',[Ext.isIE?5:2,3])}},expand:function(){if(this.isExpanded()||!this.hasFocus){return}if(this.bufferSize){this.doResize(this.bufferSize);delete this.bufferSize}this.list.alignTo(this.outerWrapEl,this.listAlign).show();this.innerList.setOverflow('auto');this.mon(Ext.getDoc(),{scope:this,mousewheel:this.collapseIf,mousedown:this.collapseIf});this.fireEvent('expand',this)},restrictHeight:function(){var inner=this.innerList.dom,st=inner.scrollTop,list=this.list;inner.style.height='';var pad=list.getFrameWidth('tb')+(this.resizable?this.handleHeight:0)+this.assetHeight,h=Math.max(inner.clientHeight,inner.offsetHeight,inner.scrollHeight),ha=this.getPosition()[1]-Ext.getBody().getScroll().top,hb=Ext.lib.Dom.getViewHeight()-ha-this.getSize().height,space=Math.max(ha,hb,this.minHeight||0)-list.shadowOffset-pad-5;h=Math.min(h,space,this.maxHeight);this.innerList.setHeight(h);list.beginUpdate();list.setHeight(h+pad);list.alignTo(this.outerWrapEl,this.listAlign);list.endUpdate();if(this.multiSelectMode){inner.scrollTop=st}},validateValue:function(val){if(this.items.getCount()===0){if(this.allowBlank){this.clearInvalid();return true}else{this.markInvalid(this.blankText);return false}}this.clearInvalid();return true},manageNameAttribute:function(){if(this.items.getCount()===0&&this.forceFormValue){this.el.dom.setAttribute('name',this.hiddenName||this.name)}else{this.el.dom.removeAttribute('name')}},setupFormInterception:function(){var form;this.findParentBy(function(p){if(p.getForm){form=p.getForm()}});if(form){var formGet=form.getValues;form.getValues=function(asString){this.el.dom.disabled=true;var oldVal=this.el.dom.value;this.setRawValue('');var vals=formGet.call(form);this.el.dom.disabled=false;this.setRawValue(oldVal);if(this.forceFormValue&&this.items.getCount()===0){vals[this.name]=''}return asString?Ext.urlEncode(vals):vals}.createDelegate(this)}},onResize:function(w,h,rw,rh){var reduce=Ext.isIE6?4:Ext.isIE7?1:Ext.isIE8?1:0;if(this.wrapEl){this._width=w;this.outerWrapEl.setWidth(w-reduce);if(this.renderFieldBtns){reduce+=(this.buttonWrap.getWidth()+20);this.wrapEl.setWidth(w-reduce)}}Ext.ux.form.SuperBoxSelect.superclass.onResize.call(this,w,h,rw,rh);this.autoSize()},onEnable:function(){Ext.ux.form.SuperBoxSelect.superclass.onEnable.call(this);this.items.each(function(item){item.enable()});if(this.renderFieldBtns){this.initButtonEvents()}},onDisable:function(){Ext.ux.form.SuperBoxSelect.superclass.onDisable.call(this);this.items.each(function(item){item.disable()});if(this.renderFieldBtns){this.removeButtonEvents()}},clearValue:function(supressRemoveEvent){Ext.ux.form.SuperBoxSelect.superclass.clearValue.call(this);this.preventMultipleRemoveEvents=supressRemoveEvent||this.supressClearValueRemoveEvents||false;this.removeAllItems();this.preventMultipleRemoveEvents=false;this.fireEvent('clear',this);return this},fireNewItemEvent:function(val){this.view.clearSelections();this.collapse();this.setRawValue('');if(this.queryFilterRe){val=val.replace(this.queryFilterRe,'');if(!val){return}}this.fireEvent('newitem',this,val,this.filteredQueryData)},onKeyUp:function(e){if(this.editable!==false&&(!e.isSpecialKey()||e.getKey()===e.BACKSPACE)&&this.itemDelimiterKey.indexOf!==e.getKey()&&(!e.hasModifier()||e.shiftKey)){this.lastKey=e.getKey();this.dqTask.delay(this.queryDelay)}},onKeyDownHandler:function(e,t){var toDestroy,nextFocus,idx;if(e.getKey()===e.ESC){if(!this.isExpanded()){if(this.el.dom.value!=''&&(this.clearOnEscape||this.clearLastQueryOnEscape)){if(this.clearOnEscape){this.el.dom.value=''}if(this.clearLastQueryOnEscape){this.lastQuery=''}e.stopEvent()}}}if((e.getKey()===e.DELETE||e.getKey()===e.SPACE)&&this.currentFocus){e.stopEvent();toDestroy=this.currentFocus;this.on('expand',function(){this.collapse()},this,{single:true});idx=this.items.indexOfKey(this.currentFocus.key);this.clearCurrentFocus();if(idx<(this.items.getCount()-1)){nextFocus=this.items.itemAt(idx+1)}toDestroy.preDestroy(true);if(nextFocus){(function(){nextFocus.onLnkFocus();this.currentFocus=nextFocus}).defer(200,this)}return true}var val=this.el.dom.value,it,ctrl=e.ctrlKey;if(this.itemDelimiterKey===e.getKey()){e.stopEvent();if(val!==""){if(ctrl||!this.isExpanded()){this.fireNewItemEvent(val)}else{this.onViewClick();if(this.unsetDelayCheck){this.delayedCheck=true;this.unsetDelayCheck.defer(10,this)}}}else{if(!this.isExpanded()){return}this.onViewClick();if(this.unsetDelayCheck){this.delayedCheck=true;this.unsetDelayCheck.defer(10,this)}}return true}if(val!==''){this.autoSize();return}if(e.getKey()===e.HOME){e.stopEvent();if(this.items.getCount()>0){this.collapse();it=this.items.get(0);it.el.focus()}return true}if(e.getKey()===e.BACKSPACE){e.stopEvent();if(this.currentFocus){toDestroy=this.currentFocus;this.on('expand',function(){this.collapse()},this,{single:true});idx=this.items.indexOfKey(toDestroy.key);this.clearCurrentFocus();if(idx<(this.items.getCount()-1)){nextFocus=this.items.itemAt(idx+1)}toDestroy.preDestroy(true);if(nextFocus){(function(){nextFocus.onLnkFocus();this.currentFocus=nextFocus}).defer(200,this)}return}else{it=this.items.get(this.items.getCount()-1);if(it){if(this.backspaceDeletesLastItem){this.on('expand',function(){this.collapse()},this,{single:true});it.preDestroy(true)}else{if(this.navigateItemsWithTab){it.onElClick()}else{this.on('expand',function(){this.collapse();this.currentFocus=it;this.currentFocus.onLnkFocus.defer(20,this.currentFocus)},this,{single:true})}}}return true}}if(!e.isNavKeyPress()){this.multiSelectMode=false;this.clearCurrentFocus();return}if(e.getKey()===e.LEFT||(e.getKey()===e.UP&&!this.isExpanded())){e.stopEvent();this.collapse();it=this.items.get(this.items.getCount()-1);if(this.navigateItemsWithTab){if(it){it.focus()}}else{if(this.currentFocus){idx=this.items.indexOfKey(this.currentFocus.key);this.clearCurrentFocus();if(idx!==0){this.currentFocus=this.items.itemAt(idx-1);this.currentFocus.onLnkFocus()}}else{this.currentFocus=it;if(it){it.onLnkFocus()}}}return true}if(e.getKey()===e.DOWN){if(this.currentFocus){this.collapse();e.stopEvent();idx=this.items.indexOfKey(this.currentFocus.key);if(idx==(this.items.getCount()-1)){this.clearCurrentFocus.defer(10,this)}else{this.clearCurrentFocus();this.currentFocus=this.items.itemAt(idx+1);if(this.currentFocus){this.currentFocus.onLnkFocus()}}return true}}if(e.getKey()===e.RIGHT){this.collapse();it=this.items.itemAt(0);if(this.navigateItemsWithTab){if(it){it.focus()}}else{if(this.currentFocus){idx=this.items.indexOfKey(this.currentFocus.key);this.clearCurrentFocus();if(idx<(this.items.getCount()-1)){this.currentFocus=this.items.itemAt(idx+1);if(this.currentFocus){this.currentFocus.onLnkFocus()}}}else{this.currentFocus=it;if(it){it.onLnkFocus()}}}}},onKeyUpBuffered:function(e){if(!e.isNavKeyPress()){this.autoSize()}},reset:function(){this.killItems();Ext.ux.form.SuperBoxSelect.superclass.reset.call(this);this.addedRecords=[];this.autoSize().setRawValue('')},applyEmptyText:function(){this.setRawValue('');if(this.items.getCount()>0){this.el.removeClass(this.emptyClass);this.setRawValue('');return this}if(this.rendered&&this.emptyText&&this.getRawValue().length<1){this.setRawValue(this.emptyText);this.el.addClass(this.emptyClass)}return this},removeAllItems:function(){this.items.each(function(item){item.preDestroy(true)},this);this.manageClearBtn();return this},killItems:function(){this.items.each(function(item){item.kill()},this);this.resetStore();this.items.clear();this.manageClearBtn();return this},resetStore:function(){this.store.clearFilter();if(!this.removeValuesFromStore){return this}this.usedRecords.each(function(rec){this.store.add(rec)},this);this.usedRecords.clear();if(!this.store.remoteSort){this.store.sort(this.displayField,'ASC')}return this},sortStore:function(){var ss=this.store.getSortState();if(ss&&ss.field){this.store.sort(ss.field,ss.direction)}return this},getCaption:function(dataObject){if(typeof this.displayFieldTpl==='string'){this.displayFieldTpl=new Ext.XTemplate(this.displayFieldTpl)}var caption,recordData=dataObject instanceof Ext.data.Record?dataObject.data:dataObject;if(this.displayFieldTpl){caption=this.displayFieldTpl.apply(recordData)}else if(this.displayField){caption=recordData[this.displayField]}return caption},addRecord:function(record){var display=record.data[this.displayField],caption=this.getCaption(record),val=record.data[this.valueField],cls=this.classField?record.data[this.classField]:'',style=this.styleField?record.data[this.styleField]:'';if(this.removeValuesFromStore){this.usedRecords.add(val,record);this.store.remove(record)}this.addItemBox(val,display,caption,cls,style);this.fireEvent('additem',this,val,record)},createRecord:function(recordData){if(!this.recordConstructor){var recordFields=[{name:this.valueField},{name:this.displayField}];if(this.classField){recordFields.push({name:this.classField})}if(this.styleField){recordFields.push({name:this.styleField})}this.recordConstructor=Ext.data.Record.create(recordFields)}return new this.recordConstructor(recordData)},addItems:function(newItemObjects){if(Ext.isArray(newItemObjects)){Ext.each(newItemObjects,function(item){this.addItem(item)},this)}else{this.addItem(newItemObjects)}},addNewItem:function(newItemObject){this.addItem(newItemObject,true)},addItem:function(newItemObject,forcedAdd){var val=newItemObject[this.valueField];if(this.disabled){return false}if(this.preventDuplicates&&this.hasValue(val)){return}var record=this.findRecord(this.valueField,val);if(record){this.addRecord(record);return}else if(!this.allowAddNewData){return}if(this.mode==='remote'){this.remoteLookup.push(newItemObject);this.doQuery(val,false,false,forcedAdd);return}var rec=this.createRecord(newItemObject);this.store.add(rec);this.addRecord(rec);return true;},addItemBox:function(itemVal,itemDisplay,itemCaption,itemClass,itemStyle){var hConfig,parseStyle=function(s){var ret='';switch(typeof s){case'function':ret=s.call();break;case'object':for(var p in s){ret+=p+':'+s[p]+';'}break;case'string':ret=s+';'}return ret},itemKey=Ext.id(null,'sbx-item'),box=new Ext.ux.form.SuperBoxSelectItem({owner:this,disabled:this.disabled,renderTo:this.wrapEl,cls:this.extraItemCls+' '+itemClass,style:parseStyle(this.extraItemStyle)+' '+itemStyle,caption:itemCaption,display:itemDisplay,value:itemVal,key:itemKey,listeners:{'remove':function(item){if(this.fireEvent('beforeremoveitem',this,item.value)===false){return false}this.items.removeKey(item.key);if(this.removeValuesFromStore){if(this.usedRecords.containsKey(item.value)){this.store.add(this.usedRecords.get(item.value));this.usedRecords.removeKey(item.value);this.sortStore();if(this.view){this.view.render()}}}if(!this.preventMultipleRemoveEvents){this.fireEvent.defer(250,this,['removeitem',this,item.value,this.findInStore(item.value)])}},destroy:function(){this.collapse();this.autoSize().manageClearBtn().validateValue()},scope:this}});box.render();hConfig={tag:'input',type:'hidden',value:itemVal,name:(this.hiddenName||this.name)};if(this.disabled){Ext.apply(hConfig,{disabled:'disabled'})}box.hidden=this.el.insertSibling(hConfig,'before');this.items.add(itemKey,box);this.applyEmptyText().autoSize().manageClearBtn().validateValue()},manageClearBtn:function(){if(!this.renderFieldBtns||!this.rendered){return this}var cls='x-superboxselect-btn-hide';if(this.items.getCount()===0){this.buttonClear.addClass(cls)}else{this.buttonClear.removeClass(cls)}return this},findInStore:function(val){var index=this.store.find(this.valueField,val);if(index>-1){return this.store.getAt(index)}return false},getSelectedRecords:function(){var ret=[];if(this.removeValuesFromStore){ret=this.usedRecords.getRange()}else{var vals=[];this.items.each(function(item){vals.push(item.value)});Ext.each(vals,function(val){ret.push(this.findInStore(val))},this)}return ret},findSelectedItem:function(el){var ret;this.items.each(function(item){if(item.el.dom===el){ret=item;return false}});return ret},findSelectedRecord:function(el){var ret,item=this.findSelectedItem(el);if(item){ret=this.findSelectedRecordByValue(item.value)}return ret},findSelectedRecordByValue:function(val){var ret;if(this.removeValuesFromStore){this.usedRecords.each(function(rec){if(rec.get(this.valueField)==val){ret=rec;return false}},this)}else{ret=this.findInStore(val)}return ret},getValue:function(){var ret=[];this.items.each(function(item){ret.push(item.value)});return ret.join(this.valueDelimiter)},getCount:function(){return this.items.getCount()},getValueEx:function(){var ret=[];this.items.each(function(item){var newItem={};newItem[this.valueField]=item.value;newItem[this.displayField]=item.display;if(this.classField){newItem[this.classField]=item.cls||''}if(this.styleField){newItem[this.styleField]=item.style||''}ret.push(newItem)},this);return ret},initValue:function(){if(Ext.isObject(this.value)||Ext.isArray(this.value)){this.setValueEx(this.value);this.originalValue=this.getValue()}else{Ext.ux.form.SuperBoxSelect.superclass.initValue.call(this)}if(this.mode==='remote'){this.setOriginal=true}},addValue:function(value){if(Ext.isEmpty(value)){return}var values=value;if(!Ext.isArray(value)){value=''+value;values=value.split(this.valueDelimiter)}Ext.each(values,function(val){var record=this.findRecord(this.valueField,val);if(record){this.addRecord(record)}else if(this.mode==='remote'){this.remoteLookup.push(val)}},this);if(this.mode==='remote'){var q=this.remoteLookup.join(this.queryValuesDelimiter);this.doQuery(q,false,true)}},setValue:function(value){if(!this.rendered){this.value=value;return}this.removeAllItems().resetStore();this.remoteLookup=[];this.addValue(value)},setValueEx:function(data){if(!this.rendered){this.value=data;return}this.removeAllItems().resetStore();if(!Ext.isArray(data)){data=[data]}this.remoteLookup=[];if(this.allowAddNewData&&this.mode==='remote'){Ext.each(data,function(d){var r=this.findRecord(this.valueField,d[this.valueField])||this.createRecord(d);this.addRecord(r)},this);return}Ext.each(data,function(item){this.addItem(item)},this)},hasValue:function(val){var has=false;this.items.each(function(item){if(item.value==val){has=true;return false}},this);return has},onSelect:function(record,index){if(this.fireEvent('beforeselect',this,record,index)!==false){var val=record.data[this.valueField];if(this.preventDuplicates&&this.hasValue(val)){return}this.setRawValue('');this.lastSelectionText='';if(this.fireEvent('beforeadditem',this,val,record,this.filteredQueryData)!==false){this.addRecord(record)}if(this.store.getCount()===0||!this.multiSelectMode){this.collapse()}else{this.restrictHeight()}}},onDestroy:function(){this.items.purgeListeners();this.killItems();if(this.allowQueryAll){Ext.destroy(this.buttonExpand)}if(this.renderFieldBtns){Ext.destroy(this.buttonClear,this.buttonWrap)}Ext.destroy(this.inputEl,this.wrapEl,this.outerWrapEl);Ext.ux.form.SuperBoxSelect.superclass.onDestroy.call(this)},autoSize:function(){if(!this.rendered){return this}if(!this.metrics){this.metrics=Ext.util.TextMetrics.createInstance(this.el)}var el=this.el,v=el.dom.value,d=document.createElement('div');if(v===""&&this.emptyText&&this.items.getCount()<1){v=this.emptyText}d.appendChild(document.createTextNode(v));v=d.innerHTML;d=null;v+="&#160;";var w=Math.max(this.metrics.getWidth(v)+24,24);if(typeof this._width!='undefined'){w=Math.min(this._width,w)}this.el.setWidth(w);if(Ext.isIE){this.el.dom.style.top='0'}this.fireEvent('autosize',this,w);return this},shouldQuery:function(q){if(this.lastQuery){var m=q.match("^"+this.lastQuery);if(!m||this.store.getCount()){return true}else{return(m[0]!==this.lastQuery)}}return true},doQuery:function(q,forceAll,valuesQuery,forcedAdd){q=Ext.isEmpty(q)?'':q;if(this.queryFilterRe){this.filteredQueryData='';var m=q.match(this.queryFilterRe);if(m&&m.length){this.filteredQueryData=m[0]}q=q.replace(this.queryFilterRe,'');if(!q&&m){return}}var qe={query:q,forceAll:forceAll,combo:this,cancel:false};if(this.fireEvent('beforequery',qe)===false||qe.cancel){return false}q=qe.query;forceAll=qe.forceAll;if(forceAll===true||(q.length>=this.minChars)||valuesQuery&&!Ext.isEmpty(q)){if(forcedAdd||this.forceSameValueQuery||this.shouldQuery(q)){this.lastQuery=q;if(this.mode=='local'){this.selectedIndex=-1;if(forceAll){this.store.clearFilter()}else{this.store.filter(this.displayField,q)}this.onLoad()}else{this.store.baseParams[this.queryParam]=q;this.store.baseParams[this.queryValuesIndicator]=valuesQuery;this.store.load({params:this.getParams(q)});if(!forcedAdd){this.expand()}}}else{this.selectedIndex=-1;this.onLoad()}}},onStoreLoad:function(store,records,options){var q=options.params[this.queryParam]||store.baseParams[this.queryParam]||"",isValuesQuery=options.params[this.queryValuesIndicator]||store.baseParams[this.queryValuesIndicator];if(this.removeValuesFromStore){this.store.each(function(record){if(this.usedRecords.containsKey(record.get(this.valueField))){this.store.remove(record)}},this)}if(isValuesQuery){var params=q.split(this.queryValuesDelimiter);Ext.each(params,function(p){this.remoteLookup.remove(p);var rec=this.findRecord(this.valueField,p);if(rec){this.addRecord(rec)}},this);if(this.setOriginal){this.setOriginal=false;this.originalValue=this.getValue()}}if(q!==''&&this.allowAddNewData){Ext.each(this.remoteLookup,function(r){if(typeof r==="object"&&r[this.valueField]===q){this.remoteLookup.remove(r);if(records.length&&records[0].get(this.valueField)===q){this.addRecord(records[0]);return}var rec=this.createRecord(r);this.store.add(rec);this.addRecord(rec);this.addedRecords.push(rec);(function(){if(this.isExpanded()){this.collapse()}}).defer(10,this);return}},this)}var toAdd=[];if(q===''){Ext.each(this.addedRecords,function(rec){if(this.preventDuplicates&&this.usedRecords.containsKey(rec.get(this.valueField))){return}toAdd.push(rec)},this)}else{var re=new RegExp(Ext.escapeRe(q)+'.*','i');Ext.each(this.addedRecords,function(rec){if(this.preventDuplicates&&this.usedRecords.containsKey(rec.get(this.valueField))){return}if(re.test(rec.get(this.displayField))){toAdd.push(rec)}},this)}this.store.add(toAdd);this.sortStore();if(this.store.getCount()===0&&this.isExpanded()){this.collapse()}}});Ext.reg('superboxselect',Ext.ux.form.SuperBoxSelect);Ext.ux.form.SuperBoxSelectItem=function(config){Ext.apply(this,config);Ext.ux.form.SuperBoxSelectItem.superclass.constructor.call(this)};Ext.ux.form.SuperBoxSelectItem=Ext.extend(Ext.ux.form.SuperBoxSelectItem,Ext.Component,{initComponent:function(){Ext.ux.form.SuperBoxSelectItem.superclass.initComponent.call(this)},onElClick:function(e){var o=this.owner;o.clearCurrentFocus().collapse();if(o.navigateItemsWithTab){this.focus()}else{o.el.dom.focus();var that=this;(function(){this.onLnkFocus();o.currentFocus=this}).defer(10,this)}},onLnkClick:function(e){if(e){e.stopEvent()}this.preDestroy();if(!this.owner.navigateItemsWithTab){this.owner.el.focus()}},onLnkFocus:function(){this.el.addClass("x-superboxselect-item-focus");this.owner.outerWrapEl.addClass("x-form-focus")},onLnkBlur:function(){this.el.removeClass("x-superboxselect-item-focus");this.owner.outerWrapEl.removeClass("x-form-focus")},enableElListeners:function(){this.el.on('click',this.onElClick,this,{stopEvent:true});this.el.addClassOnOver('x-superboxselect-item x-superboxselect-item-hover')},enableLnkListeners:function(){this.lnk.on({click:this.onLnkClick,focus:this.onLnkFocus,blur:this.onLnkBlur,scope:this})},enableAllListeners:function(){this.enableElListeners();this.enableLnkListeners()},disableAllListeners:function(){this.el.removeAllListeners();this.lnk.un('click',this.onLnkClick,this);this.lnk.un('focus',this.onLnkFocus,this);this.lnk.un('blur',this.onLnkBlur,this)},onRender:function(ct,position){Ext.ux.form.SuperBoxSelectItem.superclass.onRender.call(this,ct,position);var el=this.el;if(el){el.remove()}this.el=el=ct.createChild({tag:'li'},ct.last());el.addClass('x-superboxselect-item');var btnEl=this.owner.navigateItemsWithTab?(Ext.isSafari?'button':'a'):'span';var itemKey=this.key;Ext.apply(el,{focus:function(){var c=this.down(btnEl+'.x-superboxselect-item-close');if(c){c.focus()}},preDestroy:function(){this.preDestroy()}.createDelegate(this)});this.enableElListeners();el.update(this.caption);var cfg={tag:btnEl,'class':'x-superboxselect-item-close',tabIndex:this.owner.navigateItemsWithTab?'0':'-1'};if(btnEl==='a'){cfg.href='#'}this.lnk=el.createChild(cfg);if(!this.disabled){this.enableLnkListeners()}else{this.disableAllListeners()}this.on({disable:this.disableAllListeners,enable:this.enableAllListeners,scope:this});this.setupKeyMap()},setupKeyMap:function(){this.keyMap=new Ext.KeyMap(this.lnk,[{key:[Ext.EventObject.BACKSPACE,Ext.EventObject.DELETE,Ext.EventObject.SPACE],fn:this.preDestroy,scope:this},{key:[Ext.EventObject.RIGHT,Ext.EventObject.DOWN],fn:function(){this.moveFocus('right')},scope:this},{key:[Ext.EventObject.LEFT,Ext.EventObject.UP],fn:function(){this.moveFocus('left')},scope:this},{key:[Ext.EventObject.HOME],fn:function(){var l=this.owner.items.get(0).el.focus();if(l){l.el.focus()}},scope:this},{key:[Ext.EventObject.END],fn:function(){this.owner.el.focus()},scope:this},{key:Ext.EventObject.ENTER,fn:function(){}}]);this.keyMap.stopEvent=true},moveFocus:function(dir){var el=this.el[dir=='left'?'prev':'next']()||this.owner.el;el.focus.defer(100,el)},preDestroy:function(supressEffect){if(this.fireEvent('remove',this)===false){return}var actionDestroy=function(){if(this.owner.navigateItemsWithTab){this.moveFocus('right')}this.hidden.remove();this.hidden=null;this.destroy()};if(supressEffect){actionDestroy.call(this)}else{this.el.hide({duration:0.2,callback:actionDestroy,scope:this})}return this},kill:function(){this.hidden.remove();this.hidden=null;this.purgeListeners();this.destroy()},onDisable:function(){if(this.hidden){this.hidden.dom.setAttribute('disabled','disabled')}this.keyMap.disable();Ext.ux.form.SuperBoxSelectItem.superclass.onDisable.call(this)},onEnable:function(){if(this.hidden){this.hidden.dom.removeAttribute('disabled')}this.keyMap.enable();Ext.ux.form.SuperBoxSelectItem.superclass.onEnable.call(this)},onDestroy:function(){Ext.destroy(this.lnk,this.el);Ext.ux.form.SuperBoxSelectItem.superclass.onDestroy.call(this)}});
Ext.reg('superbox',Ext.ux.form.SuperBoxSelectItem);

Ext.onReady(function() {
    MODx.util.JSONReader = MODx.load({ xtype: 'modx-json-reader' });
    MODx.form.Handler = MODx.load({ xtype: 'modx-form-handler' });
    MODx.msg = MODx.load({ xtype: 'modx-msg' });
});
/* always-submit checkboxes */
Ext.form.XCheckbox=Ext.extend(Ext.form.Checkbox,{submitOffValue:0,submitOnValue:1,onRender:function(){this.inputValue=this.submitOnValue;Ext.form.XCheckbox.superclass.onRender.apply(this,arguments);this.hiddenField=this.wrap.insertFirst({tag:'input',type:'hidden'});if(this.tooltip){this.imageEl.set({qtip:this.tooltip})}this.updateHidden()},setValue:function(v){v=this.convertValue(v);this.updateHidden(v);Ext.form.XCheckbox.superclass.setValue.apply(this,arguments)},updateHidden:function(v){v=undefined!==v?v:this.checked;v=this.convertValue(v);if(this.hiddenField){this.hiddenField.dom.value=v?this.submitOnValue:this.submitOffValue;this.hiddenField.dom.name=v?'':this.el.dom.name}},convertValue:function(v){return(v===true||v==='true'||v===this.submitOnValue||String(v).toLowerCase()==='on')}});Ext.reg('xcheckbox',Ext.form.XCheckbox);MODx.Component = function(config) {
    config = config || {};
    MODx.Component.superclass.constructor.call(this,config);
    this.config = config;

    this._loadForm();
    if (this.config.tabs) {
        this._loadTabs();
    }
    this._loadComponents();
    this._loadActionButtons();
    MODx.activePage = this;
};
Ext.extend(MODx.Component,Ext.Component,{
    fields: {}
    ,form: null
    ,action: false

    ,_loadForm: function() {
        if (!this.config.form) { return false; }
        this.form = new Ext.form.BasicForm(Ext.get(this.config.form),{ errorReader : MODx.util.JSONReader });

        if (this.config.fields) {
            for (var i in this.config.fields) {
                if (this.config.fields.hasOwnProperty(i)) {
                    var f = this.config.fields[i];
                    if (f.xtype) {
                        f = Ext.ComponentMgr.create(f);
                    }
                    this.fields[i] = f;
                    this.form.add(f);
                }
            }
        }
        return this.form.render();
    }

    ,_loadActionButtons: function() {
        if (!this.config.buttons) { return false; }
        this.ab = MODx.load({
            xtype: 'modx-actionbuttons'
            ,form: this.form || null
            ,formpanel: this.config.formpanel || null
            ,actions: this.config.actions || null
            ,items: this.config.buttons || []
            ,loadStay: this.config.loadStay || false
        });
        return this.ab;
    }

    ,_loadTabs: function() {
        if (!this.config.tabs) { return false; }
        var o = this.config.tabOptions || {};
        Ext.applyIf(o,{
            xtype: 'modx-tabs'
            ,renderTo: this.config.tabs_div || 'tabs_div'
            ,items: this.config.tabs
        });
        return MODx.load(o);
    }

    ,_loadComponents: function() {
        if (!this.config.components) { return false; }
        var l = this.config.components.length;

        var cp = Ext.getCmp('modx-content');
        for (var i=0;i<l;i=i+1) {
            var a = MODx.load(this.config.components[i]);
            if (cp) {
                cp.add(a);
            }
        }
        if (cp) {
            cp.doLayout();
        }
        return true;
    }

    ,submitForm: function(listeners,options,otherParams) {
        listeners = listeners || {};
        otherParams = otherParams || {};
        if (!this.config.formpanel || !this.config.action) { return false; }
        f = Ext.getCmp(this.config.formpanel);
        if (!f) { return false; }

        for (var i in listeners) {
            if (typeof listeners[i] == 'function') {
                f.on(i,listeners[i],this);
            } else if (listeners[i] && typeof listeners[i] == 'object' && listeners[i].fn) {
                f.on(i,listeners[i].fn,listeners[i].scope || this);
            }
        }

        Ext.apply(f.baseParams,{
            'action':this.config.action
        });
        Ext.apply(f.baseParams,otherParams);
        options = options || {};
        options.headers = {
            'Powered-By': 'MODx'
            ,'modAuth': MODx.siteId
        };
        f.submit(options);
        return true;
    }
});
Ext.reg('modx-component',MODx.Component);


MODx.toolbar.ActionButtons = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        actions: { 'close': MODx.action.welcome }
        ,formpanel: false
        ,id: 'modx-action-buttons'
        ,loadStay: false
        ,params: {}
        ,items: []
        ,renderTo: 'modAB'
    });
    if (config.formpanel) {
        this.setupDirtyButtons(config.formpanel);
    }
    if (config.loadStay === true) {
        config.items.push('-',this.getStayMenu());
    }
    MODx.toolbar.ActionButtons.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx.toolbar.ActionButtons,Ext.Toolbar,{
    id: ''
    ,buttons: []
    ,options: { a_close: 'welcome' }
    ,stay: 'stay'

    ,checkDirtyBtns: []

    ,add: function() {
        var a = arguments, l = a.length;
        for(var i = 0; i < l; i++) {
            var el = a[i];
            var ex = ['-','->','<-','',' '];
            if (ex.indexOf(el) != -1 || (el.xtype && el.xtype == 'switch')) {
                MODx.toolbar.ActionButtons.superclass.add.call(this,el);
                continue;
            }

            var id = el.id || Ext.id();
            Ext.applyIf(el,{
                xtype: 'button'
                ,cls: (el.icon ? 'x-btn-icon bmenu' : 'x-btn-text bmenu')
                ,scope: this
                ,disabled: el.checkDirty ? true : false
                ,listeners: {}
                ,id: id
            });
            if (el.button) {
                MODx.toolbar.ActionButtons.superclass.add.call(this,el);
            }

            if (el.handler === null && el.menu === null) {
                el.handler = this.checkConfirm;
            } else if (el.confirm && el.handler) {
                el.handler = function() {
                    Ext.Msg.confirm(_('warning'),el.confirm,function(e) {
                      if (e === 'yes') { Ext.callback(el.handler,this); }
                    },el.scope || this);
                };
            } else if (el.handler) {} else { el.handler = this.handleClick; }

            /* if javascript is specified, run it when button is click, before this.checkConfirm is run */
            if (el.javascript) {
                el.listeners['click'] = {fn:this.evalJS,scope:this};
            }

            /* if checkDirty, disable until field change */
            if (el.xtype == 'button') {
                el.listeners['render'] = {fn:function(btn) {
                    if (el.checkDirty && btn) {
                        this.checkDirtyBtns.push(btn);
                    }
                },scope:this}
            }

            /* add button to toolbar */
            MODx.toolbar.ActionButtons.superclass.add.call(this,el);

            if (el.keys) {
                var map = new Ext.KeyMap(Ext.get(document));
                var y = el.keys.length;
                for (var x=0;x<y;x=x+1) {
                    var k = el.keys[x];
                    Ext.applyIf(k,{
                        scope: this
                        ,stopEvent: true
                        ,fn: function(e) {
                            var b = Ext.getCmp(id);
                            if (b) this.checkConfirm(b,e);
                        }
                    });
                    map.addBinding(k);
                }
            }
            delete el;
        }
    }

    ,evalJS: function(itm,e) {
        if (!eval(itm.javascript)) {
            e.stopEvent();
            e.preventDefault();
        }
    }

    ,checkConfirm: function(itm,e) {
        if (itm.confirm !== null && itm.confirm !== undefined) {
            this.confirm(itm,function() {
                this.handleClick(itm,e);
            },this);
        } else { this.handleClick(itm,e); }
        return false;
    }

    ,confirm: function(itm,callback,scope) {
        /* if no message go ahead and redirect...we dont like blank questions */
        if (itm.confirm === null) { return true; }

        Ext.Msg.confirm('',itm.confirm,function(e) {
            /* if the user is okay with the action */
            if (e === 'yes') {
                if (callback === null) { return true; }
                if (typeof(callback) === 'function') { /* if callback is a function, run it, and pass Button */
                    Ext.callback(callback,scope || this,[itm]);
                } else { location.href = callback; }
            }
            return true;
        },this);
        return true;
    }

    ,reloadPage: function() {
        location.href = location.href;
    }

    ,handleClick: function(itm,e) {
        var o = this.config;
        if (o.formpanel === false || o.formpanel === undefined || o.formpanel === null) return false;

        if (itm.method === 'remote') { /* if using connectors */
            MODx.util.Progress.reset();
            o.form = Ext.getCmp(o.formpanel);
            if (!o.form) return false;

            var f = o.form.getForm ? o.form.getForm() : o.form;
            var isv = true;
            if (f.items && f.items.items) {
                for (var fld in f.items.items) {
                    if (f.items.items[fld] && f.items.items[fld].validate) {
                        var fisv = f.items.items[fld].validate();
                        if (!fisv) {
                            f.items.items[fld].markInvalid();
                            isv = false;
                        }
                    }
                }
            }

            if (isv) {
                Ext.applyIf(o.params,{
                    action: itm.process
                   ,'modx-ab-stay': MODx.config.stay
                });

                Ext.apply(f.baseParams,o.params);

                o.form.on('success',function(r) {
                    if (o.form.clearDirty) o.form.clearDirty();
                    /* allow for success messages */
                    MODx.msg.status({
                        title: _('success')
                        ,message: _('save_successful')
                        ,dontHide: r.result.message != '' ? true : false
                    });
                    Ext.callback(this.redirectStay,this,[o,itm,r.result],1000);
                },this);
                o.form.submit({
                    headers: {
                        'Powered-By': 'MODx'
                        ,'modAuth': MODx.siteId
                    }
                });
            } else {
                Ext.Msg.alert(_('error'),_('correct_errors'));
            }
        } else { /* if just doing a URL redirect */
            Ext.applyIf(itm.params || {},o.baseParams || {});
            location.href = '?'+Ext.urlEncode(itm.params);
        }
        return false;
    }

    ,checkStay: function(itm,e) {
        this.stay = itm.value;
    }

    ,redirectStay: function(o,itm,res) {
        o = this.config;
        itm.params = itm.params || {};
        Ext.applyIf(itm.params,o.baseParams);
        var stay = Ext.state.Manager.get('modx.stay.'+MODx.request.a,'stay');
        switch (stay) {
            case 'new': /* if user selected 'new', then always redirect */
                if (o.form.hasListener('actionNew')) {
                    o.form.fireEvent('actionNew',itm.params);
                } else if (o.actions) {
                    if (MODx.request.parent) { itm.params.parent = MODx.request.parent; }
                    if (MODx.request.context_key) { itm.params.context_key = MODx.request.context_key; }
                    if (MODx.request.class_key) { itm.params.class_key = MODx.request.class_key; }
                    var a = Ext.urlEncode(itm.params);
                    location.href = '?a='+o.actions['new']+'&'+a;
                }
                break;
            case 'stay':
                var url;
                if (o.form.hasListener('actionContinue')) {
                    o.form.fireEvent('actionContinue',itm.params);
                } else if (o.actions) {
                    /* if Continue Editing, then don't reload the page - just hide the Progress bar
                       unless the user is on a 'Create' page...if so, then redirect
                       to the proper Edit page */
                    if ((itm.process === 'create' || itm.process === 'duplicate' || itm.reload) && res.object.id !== null) {
                        itm.params.id = res.object.id;
                        if (MODx.request.parent) { itm.params.parent = MODx.request.parent; }
                        if (MODx.request.context_key) { itm.params.context_key = MODx.request.context_key; }
                        url = Ext.urlEncode(itm.params);
                        location.href = '?a='+o.actions.edit+'&'+url;

                    } else if (itm.process === 'delete') {
                        itm.params.a = o.actions.cancel;
                        url = Ext.urlEncode(itm.params);
                        location.href = '?'+url;
                    }
                }
                break;
            case 'close': /* redirect to the cancel action */
                if (o.form.hasListener('actionClose')) {
                    o.form.fireEvent('actionClose',itm.params);
                } else if (o.actions) {
                    location.href = '?a='+o.actions.cancel+'&'+Ext.encode(itm.params);
                }
                break;
        }
    }

    ,getStayMenu: function() {
        var stay = Ext.state.Manager.get('modx.stay.'+MODx.request.a,'stay');
        var a = 0;
        switch (stay) {
            case 'new': a = 0; break;
            case 'close': a = 2; break;
            case 'stay': default: a = 1; break;
        }
        return {
            xtype:'switch'
            ,id: 'modx-stay-menu'
            ,activeItem: a
            ,items: [{
                tooltip: _('stay_new')
                ,value: 'new'
                ,menuIndex: 0
                ,id: 'modx-stay-new'
                ,iconCls:'icon-list-new'
            },{
                tooltip: _('stay')
                ,value: 'stay'
                ,menuIndex: 1
                ,id: 'modx-stay-stay'
                ,iconCls:'icon-mark-active'
            },{
                tooltip: _('close')
                ,value: 'close'
                ,menuIndex: 2
                ,id: 'modx-stay-close'
                ,iconCls:'icon-mark-complete'
            }]
            ,listeners: {
                change: function(btn,itm){
                    Ext.state.Manager.set('modx.stay.'+MODx.request.a,itm.value);
                }
                ,scope: this
                ,delay: 10
            }
        };
    }

    ,refreshTreeNode: function(tree,node,self) {
        var t = parent.Ext.getCmp(tree);
        t.refreshNode(node,self || false);
        return false;
    }

    ,setupDirtyButtons: function(f) {
        var fp = Ext.getCmp(f);
        if (fp) {
            fp.on('fieldChange',function(o) {
               for (var i=0;i<this.checkDirtyBtns.length;i=i+1) {
                    var btn = this.checkDirtyBtns[i];
                    btn.setDisabled(false);
               }
            },this);
        }
    }
});
Ext.reg('modx-actionbuttons',MODx.toolbar.ActionButtons);Ext.namespace('MODx.panel');

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
        ,ddGroup: 'modx-treedrop-dd'
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
    this.on('ready',this.onReady);
    if (this.config.useLoadingMask) {
        this.mask = new Ext.LoadMask(this.getEl(),{msg:_('loading')});
        this.mask.show();
    }
    this.fireEvent('setup',config);
    this.focusFirstField();
};
Ext.extend(MODx.FormPanel,Ext.FormPanel,{
    isReady: false

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
                var ctype = 'change';
                cmp.enableKeyEvents = true;
                switch (cmp.xtype) {
                    case 'textfield':
                    case 'textarea':
                        ctype = 'keydown';
                        break;
                    case 'checkbox':
                    case 'xcheckbox':
                    case 'radio':
                        ctype = 'check';
                        break;
                }
                cmp.listeners[ctype] = {fn:this.fieldChangeEvent,scope:this};
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
        var flds = this.getForm().items;
        flds.each(function(fld) {
            if (fld.isFormField && (
                fld.isXType('textfield') || fld.isXType('textarea')
            ) && !fld.isXType('combo')) {
                var el = fld.getEl();
                if (el) {
                    new MODx.load({
                        xtype: 'modx-treedrop'
                        ,target: fld
                        ,targetEl: el.dom
                    });
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
            if ((f.xtype == 'checkbox' || f.xtype == 'radio') && flds[i] == 'published') {
                f.setBoxLabel(v);
            } else if (f.label) {
                f.label.update(v);
            }
        }
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
};MODx.Tabs = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		enableTabScroll: true
        ,layoutOnTabChange: true
        ,plain: true
        ,deferredRender: true
        ,hideMode: 'offsets'
		,defaults: {
			autoScroll: true
			,autoHeight: true
            ,hideMode: 'offsets'
            ,border: true
            ,autoWidth: true
		}
	    ,activeTab: 0
        ,border: false
        ,autoHeight: true
        ,cls: 'modx-tabs'
	});
	MODx.Tabs.superclass.constructor.call(this,config);
	this.config = config;
};
Ext.extend(MODx.Tabs,Ext.TabPanel);
Ext.reg('modx-tabs',MODx.Tabs);/**
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
    Ext.applyIf(config,{
        modal: false
        ,layout: 'auto'
        ,closeAction: 'hide'
        ,shadow: true
        ,resizable: true
        ,collapsible: true
        ,maximizable: true
        ,autoHeight: true
        ,allowDrop: true
        ,width: 450
        ,cls: 'modx-window'
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: config.saveBtnText || _('save')
            ,scope: this
            ,handler: this.submit
        }]
        ,record: {}
        ,keys: [{
            key: Ext.EventObject.ENTER
            ,fn: this.submit
            ,scope: this
        }]
    });
    MODx.Window.superclass.constructor.call(this,config);
    this.options = config;
    this.config = config;
	
    this.addEvents({
        success: true
        ,failure: true
        ,beforeSubmit: true
    });
    this._loadForm();
    this.on('show',function() {
        if (this.config.blankValues) { this.fp.getForm().reset(); }
        if (this.config.allowDrop) { this.loadDropZones(); }
        this.syncSize();
        this.focusFirstField();
    },this);
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
        this.fp = this.createForm({
            url: this.config.url
            ,baseParams: this.config.baseParams || { action: this.config.action || '' }
            ,items: this.config.fields || []
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
            f.submit({
                waitMsg: _('saving')
                ,scope: this
                ,failure: function(frm,a) {
                    if (this.fireEvent('failure',{f:frm,a:a})) {
                        MODx.form.Handler.errorExt(a.result,frm);
                    }
                }
                ,success: function(frm,a) {
                    if (this.config.success) {
                        Ext.callback(this.config.success,this.config.scope || this,[frm,a]);
                    }
                    this.fireEvent('success',{f:frm,a:a});
                    if (close) { this.hide(); }
                }
            });
        }
    }
	
    ,createForm: function(config) {
        config = config || {};
        Ext.applyIf(config,{
            labelAlign: this.config.labelAlign || 'right'
            ,labelWidth: this.config.labelWidth || 100
            ,frame: this.config.formFrame || true
            ,border: false
            ,bodyBorder: false
            ,autoHeight: true
            ,errorReader: MODx.util.JSONReader
            ,url: this.config.url
            ,baseParams: this.config.baseParams || {}
            ,fileUpload: this.config.fileUpload || false
        });
        return new Ext.FormPanel(config);
    }

    ,renderForm: function() {
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
});
Ext.reg('modx-window',MODx.Window);Ext.namespace('MODx.tree');
/**
 * Generates the Tree in Ext. All modTree classes extend this base class.
 * 
 * @class MODx.tree.Tree
 * @extends Ext.tree.TreePanel
 * @constructor
 * @param {Object} config An object of options.
 * @xtype modx-tree
 */
MODx.tree.Tree = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        baseParams: {}
        ,action: 'getNodes'
        ,loaderConfig: {}
    });
    if (config.action) {
        config.baseParams.action = config.action;
    }
    config.loaderConfig.dataUrl = config.url;
    config.loaderConfig.baseParams = config.baseParams;
    Ext.applyIf(config.loaderConfig,{
        preloadChildren: true
        ,clearOnLoad: true
    });
        
    this.config = config;
    var tl,root;
    if (this.config.url) {
        tl = new Ext.tree.TreeLoader(config.loaderConfig);
        tl.on('beforeload',function(l,node) {
            tl.dataUrl = this.config.url+'?action='+this.config.action+'&id='+node.attributes.id;
            if (node.attributes.type) {
                tl.dataUrl += '&type='+node.attributes.type;
            }
        },this);
        tl.on('load',this.onLoad,this);
        root = {
            nodeType: 'async'
            ,text: config.root_name || config.rootName || ''
            ,draggable: false
            ,id: config.root_id || config.rootId || 'root'
        };
    } else {        
        tl = new Ext.tree.TreeLoader({
            preloadChildren: true
            ,baseAttrs: {
                uiProvider: MODx.tree.CheckboxNodeUI
            }
        });
        root = new Ext.tree.TreeNode({
            text: this.config.rootName || ''
            ,draggable: false
            ,id: this.config.rootId || 'root'
            ,children: this.config.data || []
        });
    }
    Ext.applyIf(config,{
        useArrows: true
        ,autoScroll: true
        ,animate: true
        ,enableDD: true
        ,enableDrop: true
        ,ddAppendOnly: false
        ,containerScroll: true
        ,collapsible: true
        ,border: false
        ,autoHeight: true
        ,rootVisible: true
        ,loader: tl
        ,header: false
        ,hideBorders: true
        ,bodyBorder: false
        ,cls: 'modx-tree'
        ,root: root
        ,preventRender: false
        ,menuConfig: {defaultAlign: 'tl-b?' ,enableScrolling: false}
    });
    if (config.remoteToolbar === true && (config.tbar === undefined || config.tbar === null)) {
        Ext.Ajax.request({
            url: config.remoteToolbarUrl || config.url
            ,params: {
                action: config.remoteToolbarAction || 'getToolbar'
            }
            ,success: function(r) {
                r = Ext.decode(r.responseText);
                var itms = this._formatToolbar(r.object);
                var tb = this.getTopToolbar();
                if (tb) {
                    for (var i=0;i<itms.length;i++) {
                        tb.add(itms[i]);
                    }
                    tb.doLayout();
                }
            }
            ,scope:this
        });
        config.tbar = {bodyStyle: 'padding: 0'};
    } else {
        var tb = this.getToolbar();
        if (config.tbar && config.useDefaultToolbar) {
            tb.push('-');
            for (var i=0;i<config.tbar.length;i++) {
                tb.push(config.tbar[i]);
            }
        } else if (config.tbar) {
            tb = config.tbar;
        }
        Ext.apply(config,{tbar: tb});
    }
    this.setup(config);
    this.config = config;
};
Ext.extend(MODx.tree.Tree,Ext.tree.TreePanel,{
    menu: null
    ,options: {}
    ,disableHref: false

    ,onLoad: function(ldr,node,resp) {
        var r = Ext.decode(resp.responseText);
        if (r.message) {
            var el = this.getTreeEl();
            el.addClass('modx-tree-load-msg');
            el.update(r.message);
            var w = 270;
            if (this.config.width > 150) {
                w = this.config.width;
            }
            el.setWidth(w);
            this.doLayout();
        }
    }

    /**
     * Sets up the tree and initializes it with the specified options.
     */
    ,setup: function(config) {
        config.listeners = config.listeners || {};
        config.listeners.render = {fn:function() {
            this.root.expand();
            var tl = this.getLoader();
            Ext.apply(tl,{fullMask : new Ext.LoadMask(this.getEl(),{msg:_('loading')})});
            tl.fullMask.removeMask=false;
            tl.on({
                'load' : function(){this.fullMask.hide();}
                ,'loadexception' : function(){this.fullMask.hide();}
                ,'beforeload' : function(){this.fullMask.show();}
                ,scope : tl
            });
        },scope:this};
        MODx.tree.Tree.superclass.constructor.call(this,config);
        this.addEvents('afterSort','beforeSort');
        this.cm = new Ext.menu.Menu(config.menuConfig);
        this.on('contextmenu',this._showContextMenu,this);
        this.on('beforenodedrop',this._handleDrop,this);
        this.on('nodedragover',this._handleDrop,this);
        this.on('nodedrop',this._handleDrag,this);
        this.on('click',this._saveState,this);
        this.on('contextmenu',this._saveState,this);
        this.on('click',this._handleClick,this);
	    
        this.treestate_id = this.config.id || Ext.id();
        this.on('load',this._initExpand,this,{single: true});
        this.on('expandnode',this._saveState,this);
        this.on('collapsenode',this._saveState,this);
    }
	
    /**
     * Expand the tree upon initialization.
     */
    ,_initExpand: function() {
        var treeState = Ext.state.Manager.get(this.treestate_id);
        if (Ext.isEmpty(treeState) && this.root) {
            this.root.expand();
            if (this.root.firstChild && this.config.expandFirst) {
                this.root.firstChild.select();
                this.root.firstChild.expand();
            }
        } else {
            for (var i=0;i<treeState.length;i++) {
                this.expandPath(treeState[i]);
            }
        }
    }
	
    /**
     * Add context menu items to the tree.
     * @param {Object, Array} items Either an Object config or array of Object configs.
     */
    ,addContextMenuItem: function(items) {
        var a = items, l = a.length;
        for(var i = 0; i < l; i++) {
            a[i].scope = this;
            this.cm.add(a[i]);
        }
    }
	
    /**
     * Shows the current context menu.
     * @param {Ext.tree.TreeNode} node The 
     * @param {Ext.EventObject} e The event object run.
     */
    ,_showContextMenu: function(node,e) {
        node.select();
        this.cm.activeNode = node;        
        this.cm.removeAll();
        var m;
        if (this.getMenu) {
            m = this.getMenu(node,e);
        } else if (node.attributes.menu && node.attributes.menu.items) {
            m = node.attributes.menu.items;
        }
        this.addContextMenuItem(m);
        this.cm.showAt(e.xy);
        e.preventDefault();
        e.stopEvent();
    }
    
    /**
     * Checks to see if a node exists in a tree node's children.
     * @param {Object} t The parent node.
     * @param {Object} n The node to find.
     * @return {Boolean} True if the node exists in the parent's children.
     */
    ,hasNode: function(t, n) {
        return (t.findChild('id', n.id)) || (t.leaf === true && t.parentNode.findChild('id', n.id));
    }
	
    /**
     * Refreshes the tree and runs an optional func.
     * @param {Function} func The function to run.
     * @param {Object} scope The scope to run the function in.
     * @param {Array} args An array of arguments to run with.
     * @return {Boolean} True if successful.
     */
    ,refresh: function(func,scope,args) {
        var treeState = Ext.state.Manager.get(this.treestate_id);
        this.root.reload();
        if (treeState === undefined) {this.root.expand(null,null);} else {
            for (var i=0;i<treeState.length;i++) {
                this.expandPath(treeState[i]);
            }
        }
        if (func) {
            scope = scope || this;
            args = args || [];
            this.root.on('load',function() {Ext.callback(func,scope,args);},scope);
        }
        return true;
    }
    
    ,removeChildren: function(node) {
        while(node.firstChild){
             var c = node.firstChild;
             node.removeChild(c);
             c.destroy();
        }
    }
    ,loadRemoteData: function(data) {
        this.removeChildren(this.getRootNode());
        for (var c in data) {
            if (typeof data[c] === 'object') {
                this.getRootNode().appendChild(data[c]);
            }
        }
    }
	
    ,reloadNode: function(n) {
        this.getLoader().load(n);
        n.expand();
    }
    
    /**
     * Abstracted remove function
     */
    ,remove: function(text,substr,split) {
        var node = this.cm.activeNode;
        var id = this._extractId(node.id,substr,split);
        var p = {action: this.config.removeAction || 'remove'};
        var pk = this.config.primaryKey || 'id';
        p[pk] = id;
        MODx.msg.confirm({
            title: this.config.removeTitle || _('warning')
            ,text: _(text)
            ,url: this.config.url
            ,params: p
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        }); 
    }
    
    ,_extractId: function(id,substr,split) {
        substr = substr || false;
        split = split || false;
        if (substr !== false) {
            id = node.id.substr(substr);
        }
        if (split !== false) {
            id = node.id.split('_');
            id = id[split];
        }
        return id;
    }
	
    /**
     * Expand the tree and all children.
     */
    ,expandNodes: function() {
        if (this.root) {
            this.root.expand();
            this.root.expandChildNodes(true);
        }
    }
	
    /**
     * Completely collapse the tree.
     */
    ,collapseNodes: function() {
        if (this.root) {
            this.root.collapseChildNodes(true);
            this.root.collapse();
        }
    }
	
    /**
     * Save the state of the tree's open children.
     * @param {Ext.tree.TreeNode} n The most recent expanded or collapsed node.
     */
    ,_saveState: function(n) {
        var s = Ext.state.Manager.get(this.treestate_id);
        var p = n.getPath();
        var i;
        if (!Ext.isObject(s) && !Ext.isArray(s)) {
            s = [s]; /* backwards compat */
        }
        if (Ext.isEmpty(p) || p == undefined) return; /* ignore invalid paths */
        if (n.expanded) { /* if expanding, add to state */
            if (Ext.isString(p) && s.indexOf(p) === -1) {
                var f = false;
                var sr;
                for (i=0;i<s.length;i++) {
                    if (s[i] == undefined || s[i] == 'undefined') { s.splice(i,1); continue; }
                    sr = s[i].search(p);
                    if (sr !== -1 && s[sr]) { /* dont add if already in */
                        if (s[sr].length > s[i].length) {
                            f = true;
                        }
                    }
                }
                if (!f) { /* if not in, add */
                    s.push(p);
                }
            }
        } else { /* if collapsing, remove from state */
            s = s.remove(p);
            /* remove all children of node */
            for (i=0;i<s.length;i++) {
                if (s[i] == undefined || s[i] == 'undefined') { s.splice(i,1); continue; }
                if (s[i].search(p) !== -1) {
                    delete s[i];
                }
            }
        }
        /* clear out undefineds */
        for (i=0;i<s.length;i++) {
            if (s[i] == undefined || s[i] == 'undefined') { s.splice(i,1); continue; }
        }
        Ext.state.Manager.set(this.treestate_id,s);
    }
    
    /**
     * Handles tree clicks
     * @param {Object} n The node clicked
     * @param {Object} e The event object
     */
    ,_handleClick: function (n,e) {
        e.stopEvent();
        e.preventDefault();
        
        if (this.disableHref) {return true;}
        if (e.ctrlKey) {return true;}
        if (n.attributes.page && n.attributes.page !== '') {
            location.href = n.attributes.page;
        } else {
            n.toggle();
        }
        return true;
    }
    
    
    ,encode: function(node) {
        if (!node) {node = this.getRootNode();}
        var _encode = function(node) {
            var resultNode = {};
            var kids = node.childNodes;
            for (var i = 0;i < kids.length;i=i+1) {
                var n = kids[i];
                resultNode[n.id] = {
                    id: n.id
                    ,checked: n.ui.isChecked()
                    ,type: n.attributes.type || ''
                    ,data: n.attributes.data || {}
                    ,children: _encode(n)
                };
            }
            return resultNode;
        };
        var nodes = _encode(node);
        return Ext.encode(nodes);
    }
        
    /**
     * Handles all drag events into the tree.
     * @param {Object} dropEvent The node dropped on the parent node.
     */
    ,_handleDrag: function(dropEvent) {
        function simplifyNodes(node) {
            var resultNode = {};
            var kids = node.childNodes;
            var len = kids.length;
            for (var i = 0; i < len; i++) {
                resultNode[kids[i].id] = simplifyNodes(kids[i]);
            }
            return resultNode;
        }

        var encNodes = Ext.encode(simplifyNodes(dropEvent.tree.root));
        this.fireEvent('beforeSort',encNodes);
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                data: encodeURIComponent(encNodes)
                ,action: this.config.sortAction || 'sort'
            }
            ,listeners: {
                'success': {fn:function(r) {
                    var el = dropEvent.dropNode.getUI().getTextEl();
                    if (el) {Ext.get(el).frame();}
                    this.fireEvent('afterSort',{event:dropEvent,result:r});
                },scope:this}
                ,'failure': {fn:function(r) {
                    MODx.form.Handler.errorJSON(r);
                    this.refresh();
                    return false;
                },scope:this}
            }
        });
    }
    
    /**
     * Abstract definition to handle drop events.
     */
    ,_handleDrop: function() { }

    /**
     * Semi unique ids across edits
     * @param {String} prefix Prefix the guid.
     * @return {String} The newly generated guid.
     */
    ,_guid: function(prefix){
        return prefix+(new Date().getTime());
    }
	
    /**
     * Redirects the page or the content frame to the correct location.
     * @param {String} loc The URL to direct to.
     */
    ,redirect: function(loc) {
        location.href = loc;
    }
	
    ,loadAction: function(p) {
        var id = this.cm.activeNode.id.split('_');id = id[1];
        var u = 'index.php?id='+id+'&'+p;
        location.href = u;
    }
    /**
     * Loads the default toolbar for the tree.
     * @access private
     * @see Ext.Toolbar
     */
    ,_loadToolbar: function() {}
	
    /**
     * Refreshes a given tree node.
     * @access public
     * @param {String} id The ID of the node
     * @param {Boolean} self If true, will refresh self rather than parent.
     */
    ,refreshNode: function(id,self) {
        var node = this.getNodeById(id);
        if (node) {
            var n = self ? node : node.parentNode;
            var l = this.getLoader().load(n,function() {n.expand();},this);
        }
    }

    /**
     * Refreshes selected active node
     * @access public
     */
    ,refreshActiveNode: function() {
        this.getLoader().load(this.cm.activeNode,this.cm.activeNode.expand);
    }
    
    /**
     * Refreshes selected active node's parent
     * @access public
     */
    ,refreshParentNode: function() {
        this.getLoader().load(this.cm.activeNode.parentNode,this.cm.activeNode.expand);
    }
    
    /**
     * Removes specified node
     * @param {String} id The node's ID
     */
    ,removeNode: function(id) {
        var node = this.getNodeById(id);
        if (node) {
            node.remove(); 
        }
    }
    
    /**
     * Dynamically removes active node
     */
    ,removeActiveNode: function() {
        this.cm.activeNode.remove();
    }
	
    /**
     * Gets a default toolbar setup
     */
    ,getToolbar: function() {
        var iu = MODx.config.template_url+'images/restyle/icons/';
        return [{
            icon: iu+'arrow_down.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('tree_expand')}
            ,handler: this.expandNodes
            ,scope: this
        },{
            icon: iu+'arrow_up.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('tree_collapse')}
            ,handler: this.collapseNodes
            ,scope: this
        },'-',{
            icon: iu+'refresh.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('tree_refresh')}
            ,handler: this.refresh
            ,scope: this
        }];
    }

    /**
     * Add Items to the toolbar.
     */
    ,_formatToolbar: function(a) {
        var l = a.length;
        for (var i = 0; i < l; i++) {
            if (a[i].handler) {
                a[i].handler = eval(a[i].handler);
            }
            Ext.applyIf(a[i],{
                scope: this
                ,cls: this.config.toolbarItemCls || 'x-btn-icon'
            });
        }
        return a;
    }
});
Ext.reg('modx-tree',MODx.tree.Tree);Ext.namespace('MODx.combo');
/* fixes combobox value loading issue */
Ext.override(Ext.form.ComboBox,{loaded:false,setValue:Ext.form.ComboBox.prototype.setValue.createSequence(function(v){var a=this.store.find(this.valueField,v);if(v&&v!==0&&this.mode=='remote'&&a==-1&&!this.loaded){var p={};p[this.valueField]=v;this.loaded=true;this.store.load({scope:this,params:p,callback:function(){this.setValue(v);this.collapse()}})}})});

MODx.combo.ComboBox = function(config,getStore) {
    config = config || {};
    Ext.applyIf(config,{
        displayField: 'name'
        ,valueField: 'id'
        ,triggerAction: 'all'
        ,fields: ['id','name']
        ,baseParams: {
            action: 'getList'
        }
        ,width: 150
        ,listWidth: 300
        ,editable: false
        ,resizable: true
        ,typeAhead: false
        ,forceSelection: true
        ,minChars: 3
        ,cls: 'modx-combo'
    });
    Ext.applyIf(config,{
        store: new Ext.data.JsonStore({
            url: config.connector || config.url
            ,root: 'results'
            ,totalProperty: 'total'
            ,fields: config.fields
            ,errorReader: MODx.util.JSONReader
            ,baseParams: config.baseParams || {}
            ,remoteSort: config.remoteSort || false
            ,autoDestroy: true
        })
    });
    if (getStore === true) {
       config.store.load();
       return config.store;
    }
    MODx.combo.ComboBox.superclass.constructor.call(this,config);
    this.config = config;
    return this;
};
Ext.extend(MODx.combo.ComboBox,Ext.form.ComboBox);
Ext.reg('modx-combo',MODx.combo.ComboBox);

MODx.combo.Renderer = function(combo) {
    var loaded = false;
    return (function(v) {
        var idx,rec;
        if (!combo.store) return v;
        if (!loaded) {
            if (combo.store.proxy !== undefined && combo.store.proxy !== null) {
                combo.store.load();
            }
            loaded = true;
        }
        var v2 = combo.getValue();
        idx = combo.store.find(combo.valueField,v2 ? v2 : v);
        rec = combo.store.getAt(idx);
        return (rec === undefined || rec === null ? (v2 ? v2 : v) : rec.get(combo.displayField));
    });
};

MODx.combo.Boolean = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.SimpleStore({
            fields: ['d','v']
            ,data: [[_('yes'),true],[_('no'),false]]
        })
        ,displayField: 'd'
        ,valueField: 'v'
        ,mode: 'local'
        ,triggerAction: 'all'
        ,editable: false
        ,selectOnFocus: false
        ,preventRender: true
        ,forceSelection: true
        ,enableKeyEvents: true
    });
    MODx.combo.Boolean.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Boolean,MODx.combo.ComboBox);
Ext.reg('combo-boolean',MODx.combo.Boolean);
Ext.reg('modx-combo-boolean',MODx.combo.Boolean);

MODx.util.PasswordField = function(config) {
    config = config || {};
    delete config.xtype;
    Ext.applyIf(config,{
        xtype: 'textfield'
        ,inputType: 'password'
    });
    MODx.util.PasswordField.superclass.constructor.call(this,config);
};
Ext.extend(MODx.util.PasswordField,Ext.form.TextField);
Ext.reg('text-password',MODx.util.PasswordField);
Ext.reg('modx-text-password',MODx.util.PasswordField);

MODx.combo.User = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'user'
        ,hiddenName: 'user'
        ,displayField: 'username'
        ,valueField: 'id'
        ,fields: ['username','id']
        ,pageSize: 20
        ,url: MODx.config.connectors_url+'security/user.php'
    });
    MODx.combo.User.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.User,MODx.combo.ComboBox);
Ext.reg('modx-combo-user',MODx.combo.User);

MODx.combo.UserGroup = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'group'
        ,hiddenName: 'group'
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['name','id','description']
        ,listWidth: 300
        ,pageSize: 20
        ,url: MODx.config.connectors_url+'security/group.php'
        ,tpl: new Ext.XTemplate('<tpl for="."><div class="x-combo-list-item"><span style="font-weight: bold">{name}</span>'
            ,'<br />{description}</div></tpl>')
    });
    MODx.combo.UserGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.UserGroup,MODx.combo.ComboBox);
Ext.reg('modx-combo-usergroup',MODx.combo.UserGroup);

MODx.combo.UserGroupRole = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'role'
        ,hiddenName: 'role'
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['name','id']
        ,pageSize: 20
        ,url: MODx.config.connectors_url+'security/role.php'
    });
    MODx.combo.UserGroupRole.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.UserGroupRole,MODx.combo.ComboBox);
Ext.reg('modx-combo-usergrouprole',MODx.combo.UserGroupRole);

MODx.combo.ResourceGroup = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'resourcegroup'
        ,hiddenName: 'resourcegroup'
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['name','id']
        ,pageSize: 20
        ,url: MODx.config.connectors_url+'security/resourcegroup.php'
    });
    MODx.combo.ResourceGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.ResourceGroup,MODx.combo.ComboBox);
Ext.reg('modx-combo-resourcegroup',MODx.combo.ResourceGroup);

MODx.combo.Context = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'context'
        ,hiddenName: 'context'
        ,displayField: 'key'
        ,valueField: 'key'
        ,fields: ['key']
        ,pageSize: 20
        ,url: MODx.config.connectors_url+'context/index.php'
    });
    MODx.combo.Context.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Context,MODx.combo.ComboBox);
Ext.reg('modx-combo-context',MODx.combo.Context);

MODx.combo.Policy = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'policy'
        ,hiddenName: 'policy'
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['name','id']
        ,allowBlank: false
        ,editable: false
        ,pageSize: 20
        ,url: MODx.config.connectors_url+'security/access/policy.php'
    });
    MODx.combo.Policy.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Policy,MODx.combo.ComboBox);
Ext.reg('modx-combo-policy',MODx.combo.Policy);

MODx.combo.Template = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'template'
        ,hiddenName: 'template'
        ,displayField: 'templatename'
        ,valueField: 'id'
        ,pageSize: 20
        ,fields: ['id','templatename','description','category_name']
        ,tpl: new Ext.XTemplate('<tpl for="."><div class="x-combo-list-item"><span style="font-weight: bold">{templatename}</span>'
            ,'<tpl if="category_name"> - <span style="font-style:italic">{category_name}</span></tpl>'
            ,'<br />{description}</div></tpl>')
        ,url: MODx.config.connectors_url+'element/template.php'
        ,listWidth: 350
        ,allowBlank: true
    });
    MODx.combo.Template.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Template,MODx.combo.ComboBox);
Ext.reg('modx-combo-template',MODx.combo.Template);

MODx.combo.Category = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'category'
        ,hiddenName: 'category'
        ,displayField: 'name'
        ,valueField: 'id'
        ,mode: 'remote'
        ,fields: ['id','category','parent','name']
        ,forceSelection: true
        ,typeAhead: false
        ,allowBlank: true
        ,editable: false
        ,enableKeyEvents: true
        ,url: MODx.config.connectors_url+'element/category.php'
        ,baseParams: { action: 'getList' ,showNone: true }
    });
    MODx.combo.Category.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Category,MODx.combo.ComboBox,{
    _onblur: function(t,e) { 
        var v = this.getRawValue();
        this.setRawValue(v);
        this.setValue(v,true);
    }
});
Ext.reg('modx-combo-category',MODx.combo.Category);

MODx.combo.Language = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'language'
        ,hiddenName: 'language'
        ,displayField: 'name'
        ,valueField: 'name'
        ,fields: ['name']
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        ,pageSize: 20
        ,url: MODx.config.connectors_url+'system/language.php'
    });
    MODx.combo.Language.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Language,MODx.combo.ComboBox);
Ext.reg('modx-combo-language',MODx.combo.Language);

MODx.combo.Charset = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'charset'
        ,hiddenName: 'charset'
        ,displayField: 'text'
        ,valueField: 'value'
        ,fields: ['value','text']
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        ,listWidth: 300
        ,url: MODx.config.connectors_url+'system/charset.php'
    });
    MODx.combo.Charset.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Charset,MODx.combo.ComboBox);
Ext.reg('modx-combo-charset',MODx.combo.Charset);

MODx.combo.RTE = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'rte'
        ,hiddenName: 'rte'
        ,displayField: 'value'
        ,valueField: 'value'
        ,fields: ['value']
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        ,listWidth: 300
        ,url: MODx.config.connectors_url+'system/rte.php'
    });
    MODx.combo.RTE.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.RTE,MODx.combo.ComboBox);
Ext.reg('modx-combo-rte',MODx.combo.RTE);

MODx.combo.Role = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'role'
        ,hiddenName: 'role'
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        ,listWidth: 300
        ,pageSize: 20
        ,url: MODx.config.connectors_url+'security/role.php'
        ,baseParams: { action: 'getList', addNone: true }
    });
    MODx.combo.Role.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Role,MODx.combo.ComboBox);
Ext.reg('modx-combo-role',MODx.combo.Role);

MODx.combo.ContentType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'content_type'
        ,hiddenName: 'content_type'
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        ,listWidth: 300
        ,pageSize: 20
        ,url: MODx.config.connectors_url+'system/contenttype.php'
        ,baseParams: { action: 'getList' }
    });
    MODx.combo.ContentType.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.ContentType,MODx.combo.ComboBox);
Ext.reg('modx-combo-content-type',MODx.combo.ContentType);

MODx.combo.ContentDisposition = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.SimpleStore({
            fields: ['d','v']
            ,data: [[_('inline'),0],[_('attachment'),1]]
        })
        ,name: 'content_dispo'
        ,hiddenName: 'content_dispo'
        ,width: 200
        ,displayField: 'd'
        ,valueField: 'v'
        ,mode: 'local'
        ,triggerAction: 'all'
        ,editable: false
        ,pageSize: 20
        ,selectOnFocus: false
        ,preventRender: true
    });
    MODx.combo.ContentDisposition.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.ContentDisposition,Ext.form.ComboBox);
Ext.reg('modx-combo-content-disposition',MODx.combo.ContentDisposition);

MODx.combo.ClassMap = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'class'
        ,hiddenName: 'class'
        ,url: MODx.config.connectors_url+'system/classmap.php'
        ,displayField: 'class'
        ,valueField: 'class'
        ,fields: ['class']
        ,editable: false
    });
    MODx.combo.ClassMap.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.ClassMap,MODx.combo.ComboBox);
Ext.reg('modx-combo-class-map',MODx.combo.ClassMap);

MODx.combo.Object = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'object'
        ,hiddenName: 'object'
        ,url: MODx.config.connectors_url+'workspace/builder/index.php'
        ,baseParams: { 
            action: 'getAssocObject'
            ,class_key: 'modResource'
        }
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['id','name']
        ,pageSize: 10
        ,editable: false
    });
    MODx.combo.Object.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Object,MODx.combo.ComboBox);
Ext.reg('modx-combo-object',MODx.combo.Object);

MODx.combo.Namespace = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'namespace'
        ,hiddenName: 'namespace'
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        ,listWidth: 300
        ,pageSize: 20
        ,url: MODx.config.connectors_url+'workspace/namespace.php'
        ,fields: ['name']
        ,displayField: 'name'
        ,valueField: 'name'
    });
    MODx.combo.Namespace.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Namespace,MODx.combo.ComboBox);
Ext.reg('modx-combo-namespace',MODx.combo.Namespace);

MODx.combo.Browser = function(config) {
    config = config || {};
    Ext.applyIf(config,{
       width: 300
       ,triggerAction: 'all'
    });
    MODx.combo.Browser.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx.combo.Browser,Ext.form.TriggerField,{
    browser: null
    
    ,onTriggerClick : function(btn){
        if (this.disabled){
            return false;
        }
        
        if (this.browser === null) {
            this.browser = MODx.load({
                xtype: 'modx-browser'
                ,id: Ext.id()
                ,multiple: true
                ,prependPath: this.config.prependPath || null
                ,prependUrl: this.config.prependUrl || null
                ,basePath: this.config.basePath || ''
                ,basePathRelative: this.config.basePathRelative || null
                ,baseUrl: this.config.baseUrl || ''
                ,baseUrlRelative: this.config.baseUrlRelative || null
                ,hideFiles: this.config.hideFiles || false
                ,rootVisible: this.config.rootVisible || false
                ,allowedFileTypes: this.config.allowedFileTypes || ''
                ,wctx: this.config.wctx || 'web'
                ,openTo: this.config.openTo || ''
                ,listeners: {
                    'select': {fn: function(data) {
                        this.setValue(data.relativeUrl);
                        this.fireEvent('select',data);
                    },scope:this}
                }
            });
        }
        this.browser.show(btn);
        return true;
    }
    
    ,onDestroy: function(){
        MODx.combo.Browser.superclass.onDestroy.call(this);
    }
});
Ext.reg('modx-combo-browser',MODx.combo.Browser);

MODx.combo.Country = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'country'
        ,hiddenName: 'country'
        ,url: MODx.config.connectors_url+'system/country.php'
        ,displayField: 'value'
        ,valueField: 'value'
        ,fields: ['value']
        ,editable: false
        ,value: 0
    });
    MODx.combo.Country.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Country,MODx.combo.ComboBox);
Ext.reg('modx-combo-country',MODx.combo.Country);

MODx.combo.PropertySet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'propertyset'
        ,hiddenName: 'propertyset'
        ,url: MODx.config.connectors_url+'element/propertyset.php'
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['id','name']
        ,editable: false
        ,value: 0
    });
    MODx.combo.PropertySet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.PropertySet,MODx.combo.ComboBox);
Ext.reg('modx-combo-property-set',MODx.combo.PropertySet);


MODx.ChangeParentField = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        triggerAction: 'all'
        ,editable: false
        ,readOnly: false
        ,formpanel: 'modx-panel-resource'
    });    
    MODx.ChangeParentField.superclass.constructor.call(this,config);
    this.config = config;
    this.on('click',this.onTriggerClick,this);
    this.addEvents({ end: true });
    this.on('end',this.end,this);
};
Ext.extend(MODx.ChangeParentField,Ext.form.TriggerField,{
    oldValue: false
    ,oldDisplayValue: false
    ,end: function(p) {
        var t = Ext.getCmp('modx-resource-tree');
        if (!t) return;
        p.d = p.d || p.v;
        
        t.removeListener('click',this.handleChangeParent,this);
        t.on('click',t._handleClick,t);
        t.disableHref = false;

        MODx.debug('Setting parent to: '+p.v);
        
        Ext.getCmp('modx-resource-parent-hidden').setValue(p.v);
        
        this.setValue(p.d);
        this.oldValue = false;
        
        Ext.getCmp(this.config.formpanel).fireEvent('fieldChange');
    }
    ,onTriggerClick: function() {
        if (this.disabled) { return false; }
        if (this.oldValue) {
            this.fireEvent('end',{
                v: this.oldValue
                ,d: this.oldDisplayValue
            });
            return false;
        }
        MODx.debug('onTriggerClick');

        var t = Ext.getCmp('modx-resource-tree');
        if (!t) {
            MODx.debug('no tree found, trying to activate');
            var tp = Ext.getCmp('modx-leftbar-tabpanel');
            if (tp) {
                tp.on('tabchange',function(tbp,tab) {
                    if (tab.id == 'modx-resource-tree-ct') {
                        this.disableTreeClick();
                    }
                },this);
                tp.activate('modx-resource-tree-ct');
            } else {
                MODx.debug('no tabpanel');
            }
            return false;
        }

        this.disableTreeClick();
    }

    ,disableTreeClick: function() {
        MODx.debug('Disabling tree click');
        t = Ext.getCmp('modx-resource-tree');
        if (!t) {
            MODx.debug('No tree found in disableTreeClick!');
            return false;
        }
        this.oldDisplayValue = this.getValue();
        this.oldValue = Ext.getCmp('modx-resource-parent-hidden').getValue();

        this.setValue(_('resource_parent_select_node'));

        t.expand();
        t.removeListener('click',t._handleClick);
        t.on('click',this.handleChangeParent,this);
        t.disableHref = true;

        return true;}
        
    ,handleChangeParent: function(node,e) {
        var t = Ext.getCmp('modx-resource-tree');
        if (!t) { return false; }
        t.disableHref = true;

        var id = node.id.split('_'); id = id[1];
        if (id == MODx.request.id) {
            MODx.msg.alert('',_('resource_err_own_parent'));            
            return false;
        }

        var ctxf = Ext.getCmp('modx-resource-context-key');
        if (ctxf) {
            var ctxv = ctxf.getValue();
            if (node.attributes && node.attributes.ctx != ctxv) {
                ctxf.setValue(node.attributes.ctx);
            }
        }
        this.fireEvent('end',{
            v: node.attributes.type != 'modContext' ? id : node.attributes.pk
            ,d: Ext.util.Format.stripTags(node.text)
        });
        e.preventDefault();
        e.stopEvent();
        return true;
    }
});
Ext.reg('modx-field-parent-change',MODx.ChangeParentField);


MODx.combo.TVWidget = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'widget'
        ,hiddenName: 'widget'
        ,displayField: 'name'
        ,valueField: 'value'
        ,fields: ['value','name']
        ,editable: false
        ,url: MODx.config.connectors_url+'element/tv/renders.php'
        ,baseParams: {
            action: 'getOutputs'
        }
        ,value: 'default'
    });
    MODx.combo.TVWidget.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.TVWidget,MODx.combo.ComboBox);
Ext.reg('modx-combo-tv-widget',MODx.combo.TVWidget);

MODx.combo.TVInputType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'type'
        ,hiddenName: 'type'
        ,displayField: 'name'
        ,valueField: 'value'
        ,editable: false
        ,fields: ['value','name']
        ,url: MODx.config.connectors_url+'element/tv/renders.php'
        ,baseParams: {
            action: 'getInputs'
        }
        ,value: 'text'
    });
    MODx.combo.TVInputType.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.TVInputType,MODx.combo.ComboBox);
Ext.reg('modx-combo-tv-input-type',MODx.combo.TVInputType);

MODx.combo.Action = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'action'
        ,hiddenName: 'action'
        ,displayField: 'controller'
        ,valueField: 'id'
        ,fields: ['id','controller']
        ,url: MODx.config.connectors_url+'system/action.php'
    });
    MODx.combo.Action.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Action,MODx.combo.ComboBox);
Ext.reg('modx-combo-action',MODx.combo.Action);Ext.namespace('MODx.grid');

MODx.grid.Grid = function(config) {
    config = config || {};
    this.config = config;
    this._loadStore();
    this._loadColumnModel();
	
    Ext.applyIf(config,{
        store: this.store
        ,cm: this.cm
        ,sm: new Ext.grid.RowSelectionModel({singleSelect:true})
        ,paging: (config.bbar ? true : false)
        ,loadMask: true
        ,autoHeight: true
        ,collapsible: true
        ,stripeRows: true
        ,header: false
        ,cls: 'modx-grid'
        ,preventRender: true
        ,preventSaveRefresh: true
        ,showPerPage: true
        ,stateful: false
        ,menuConfig: {
            defaultAlign: 'tl-b?'
            ,enableScrolling: false
        }
        ,viewConfig: {
            forceFit: true
            ,enableRowBody: true
            ,autoFill: true
            ,showPreview: true
            ,scrollOffset: 0
            ,emptyText: config.emptyText || _('ext_emptymsg')
        }
    });
    if (config.paging) {
        var pgItms = config.showPerPage ? ['-',_('per_page')+':',{
            xtype: 'textfield'
            ,value: config.pageSize || (parseInt(MODx.config.default_per_page) || 20)
            ,width: 40
            ,listeners: {
                'change': {fn:function(tf,nv,ov) {
                    if (Ext.isEmpty(nv)) return false;
                    nv = parseInt(nv);
                    this.getBottomToolbar().pageSize = nv;
                    this.store.load({params:{
                        start:0
                        ,limit: nv
                    }});
                },scope:this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: function() {
                            this.fireEvent('change',this.getValue());
                            this.blur();
                            return true;}
                        ,scope: cmp
                    });
                },scope:this}
            }
        }] : [];
        if (config.pagingItems) {
            for (var i=0;i<config.pagingItems.length;i++) {
                pgItms.push(config.pagingItems[i]);
            }
        }
        Ext.applyIf(config,{
            bbar: new Ext.PagingToolbar({
                pageSize: config.pageSize || (parseInt(MODx.config.default_per_page) || 20)
                ,store: this.getStore()
                ,displayInfo: true
                ,items: pgItms
            })
        });
    }
    if (config.grouping) {
        Ext.applyIf(config,{
          view: new Ext.grid.GroupingView({ 
            forceFit: true 
            ,scrollOffset: 0
            ,groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "'
                +(config.pluralText || _('records')) + '" : "'
                +(config.singleText || _('record'))+'"]})' 
          })
        });
    }
    if (config.tbar) {
        for (var i = 0;i<config.tbar.length;i++) {
            var itm = config.tbar[i];
            if (itm.handler && typeof(itm.handler) == 'object' && itm.handler.xtype) {
                itm.handler = this.loadWindow.createDelegate(this,[itm.handler],true);
            }
            if (!itm.scope) { itm.scope = this; }
        }
    }
    MODx.grid.Grid.superclass.constructor.call(this,config);
    this._loadMenu(config);
    this.addEvents('beforeRemoveRow','afterRemoveRow','afterAutoSave');
    if (!config.preventRender) { this.render(); }
	
    this.on('rowcontextmenu',this._showMenu,this);    
    if (config.autosave) {
        this.on('afteredit',this.saveRecord,this);
    }
	
    if (config.paging && config.grouping) {
        this.getBottomToolbar().bind(this.store);
    }

    this.getStore().load({
        params: {
            start: config.pageStart || 0
            ,limit: config.pageSize || (parseInt(MODx.config.default_per_page) || 20)
        }
        ,scope: this
        ,callback: function() { this.getStore().reload(); } /* fixes comboeditor bug */
    });
    this.getStore().on('exception',this.onStoreException,this);
    this.config = config;
};
Ext.extend(MODx.grid.Grid,Ext.grid.EditorGridPanel,{
    windows: {}

    ,onStoreException: function(dp,type,act,opt,resp){
        var r = Ext.decode(resp.responseText);
        if (r.message) {
            this.getView().emptyText = r.message;
            this.getView().refresh(false);
        }
    }
    
    ,saveRecord: function(e) {
        e.record.data.menu = null;
        var p = this.config.saveParams || {};
        Ext.apply(e.record.data,p);
        var d = Ext.util.JSON.encode(e.record.data);
        var url = this.config.saveUrl || (this.config.url || this.config.connector);
        MODx.Ajax.request({
            url: url
            ,params: {
                action: this.config.save_action || 'updateFromGrid'
                ,data: d
            }
            ,listeners: {
                'success': {fn:function(r) {
                    if (this.config.save_callback) {
                        Ext.callback(this.config.save_callback,this.config.scope || this,[r]);
                    }
                    e.record.commit();
                    if (!this.config.preventSaveRefresh) {
                        this.refresh();
                    }
                    this.fireEvent('afterAutoSave',r);
                },scope:this}
            }
        });
        return true;
    }
    
    ,loadWindow: function(btn,e,win,or) {
        var r = this.menu.record;
        if (!this.windows[win.xtype] || win.force) {  
            Ext.applyIf(win,{
                record: win.blankValues ? {} : r
                ,grid: this
                ,listeners: {
                    'success': {fn:win.success || this.refresh,scope:win.scope || this}
                }
            });
            if (or) {
                Ext.apply(win,or);
            }
            this.windows[win.xtype] = Ext.ComponentMgr.create(win);
        }
        if (this.windows[win.xtype].setValues && win.blankValues !== true && r != undefined) {
            this.windows[win.xtype].setValues(r);
        }
        this.windows[win.xtype].show(e.target);
    }
    
    ,confirm: function(type,text) {
        var p = { action: type };
        var k = this.config.primaryKey || 'id';
        p[k] = this.menu.record[k];
        
        MODx.msg.confirm({
            title: _(type)
            ,text: _(text) || _('confirm_remove')
            ,url: this.config.url
            ,params: p
            ,listeners: {
            	'success': {fn:this.refresh,scope:this}
            }
        });
    }
    
    ,remove: function(text) {
        var r = this.menu.record;
        text = text || 'confirm_remove';
        var p = this.config.saveParams || {};
        Ext.apply(p,{ action: 'remove' });
        var k = this.config.primaryKey || 'id';
        p[k] = r[k];
        
        if (this.fireEvent('beforeRemoveRow',r)) {
            MODx.msg.confirm({
                title: _('warning')
                ,text: _(text)
                ,url: this.config.url
                ,params: p
                ,listeners: {
                	'success': {fn:function() {
                        this.removeActiveRow(r);
                    },scope:this}
                }
            });
        }
    }
    
    ,removeActiveRow: function(r) {
        if (this.fireEvent('afterRemoveRow',r)) {
            var rx = this.getSelectionModel().getSelected();
            this.getStore().remove(rx);
        }
    }
    
    ,_loadMenu: function() {
        this.menu = new Ext.menu.Menu(this.config.menuConfig);
    }

    ,_showMenu: function(g,ri,e) {
        e.stopEvent();
        e.preventDefault();
        this.menu.record = this.getStore().getAt(ri).data;
        if (!this.getSelectionModel().isSelected(ri)) {
            this.getSelectionModel().selectRow(ri);
        }
        this.menu.removeAll();
        if (this.getMenu) {
            var m = this.getMenu(g,ri,e);
            if (m && m.length && m.length > 0) {
                this.addContextMenuItem(m);
            }
        }
        if ((!m || m.length <= 0) && this.menu.record.menu) {
            this.addContextMenuItem(this.menu.record.menu);
        }
        if (this.menu.items.length > 0) {
            this.menu.showAt(e.xy);
        }
    }
    
    ,_loadStore: function() {
        if (this.config.grouping) {
            this.store = new Ext.data.GroupingStore({
                url: this.config.url
                ,baseParams: this.config.baseParams || { action: this.config.action || 'getList'}
                ,reader: new Ext.data.JsonReader({
                    totalProperty: 'total'
                    ,root: 'results'
                    ,fields: this.config.fields
                })
                ,sortInfo:{
                    field: this.config.sortBy || 'id'
                    ,direction: this.config.sortDir || 'ASC'
                }
                ,remoteSort: this.config.remoteSort != false ? true : false
                ,groupField: this.config.groupBy || 'name'
                ,storeId: this.config.storeId || Ext.id()
                ,autoDestroy: true
            });
        } else {
            this.store = new Ext.data.JsonStore({
                url: this.config.url
                ,baseParams: this.config.baseParams || { action: this.config.action || 'getList' }
                ,fields: this.config.fields
                ,root: 'results'
                ,totalProperty: 'total'
                ,remoteSort: this.config.remoteSort || false
                ,storeId: this.config.storeId || Ext.id()
                ,autoDestroy: true
            });
        }
    }
    
    ,_loadColumnModel: function() {
        if (this.config.columns) {
            var c = this.config.columns;
            for (var i=0;i<c.length;i++) {
                // if specifying custom editor/renderer
                if (typeof(c[i].editor) == 'string') {
                    c[i].editor = eval(c[i].editor);
                }
                if (typeof(c[i].renderer) == 'string') {
                    c[i].renderer = eval(c[i].renderer);
                }
                if (typeof(c[i].editor) == 'object' && c[i].editor.xtype) {
                    var r = c[i].editor.renderer;
                    c[i].editor = Ext.ComponentMgr.create(c[i].editor);
                    if (r === true) {
                        c[i].renderer = MODx.combo.Renderer(c[i].editor);
                    } else if (c[i].editor.initialConfig.xtype === 'datefield') {
                        c[i].renderer = Ext.util.Format.dateRenderer(c[i].editor.initialConfig.format || 'Y-m-d');
                    } else if (r === 'boolean') {
                        c[i].renderer = this.rendYesNo;
                    } else if (r === 'password') {
                        c[i].renderer = this.rendPassword;
                    } else if (r === 'local' && typeof(c[i].renderer) == 'string') {
                        c[i].renderer = eval(c[i].renderer);
                    }
                }
            }
            this.cm = new Ext.grid.ColumnModel(c);
        }
    }
    
    ,addContextMenuItem: function(items) {
        var a = items, l = a.length;
        for(var i = 0; i < l; i++) {
            var options = a[i];
            
            if (options == '-') {
                this.menu.add('-');
                continue;
            }
            var h = Ext.emptyFn;
            if (options.handler) {
                h = eval(options.handler);
                if (h && typeof(h) == 'object' && h.xtype) {
                    h = this.loadWindow.createDelegate(this,[h],true);
                }
            } else {
                h = function(itm,e) {
                    var o = itm.options;
                    var id = this.menu.record.id;
                    if (o.confirm) {
                        Ext.Msg.confirm('',o.confirm,function(e) {
                            if (e == 'yes') {
                                var a = Ext.urlEncode(o.params || {action: o.action});
                                var s = 'index.php?id='+id+'&'+a;
                                location.href = s;
                            }
                        },this);
                    } else {
                        var a = Ext.urlEncode(o.params || {action: o.action});
                        var s = 'index.php?id='+id+'&'+a;
                        location.href = s;
                    }
                };
            }
            this.menu.add({
                id: options.id || Ext.id()
                ,text: options.text
                ,scope: options.scope || this
                ,options: options
                ,handler: h
            });
        }
    }
    
    ,refresh: function() {
        this.getStore().reload();
    }

    ,rendPassword: function(v,md) {
        var z = ''
        for (i=0;i<v.length;i++) {
            z = z+'*';
        }
        return z;
    }
    
    ,rendYesNo: function(v,md) {
        if (v === 1 || v == '1') { v = true; }
        if (v === 0 || v == '0') { v = false; }
        switch (v) {
            case true:
            case 'true':
            case 1:
                md.css = 'green';
                return _('yes');
            case false:
            case 'false':
            case '':
            case 0:
                md.css = 'red';
                return _('no');
        }
    }

    ,getSelectedAsList: function() {
        var sels = this.getSelectionModel().getSelections();
        if (sels.length <= 0) return false;

        var cs = '';
        for (var i=0;i<sels.length;i++) {
            cs += ','+sels[i].data.id;
        }

        if (cs[0] == ',') {
            cs = cs.substr(1);
        }
        return cs;
    }
    
    ,editorYesNo: function(r) {
    	r = r || {};
    	Ext.applyIf(r,{
            store: new Ext.data.SimpleStore({
                fields: ['d','v']
                ,data: [[_('yes'),true],[_('no'),false]]
            })
            ,displayField: 'd'
            ,valueField: 'v'
            ,mode: 'local'
            ,triggerAction: 'all'
            ,editable: false
            ,selectOnFocus: false
        });
        return new Ext.form.ComboBox(r);
    }
    
    ,encodeModified: function() {
        var p = this.getStore().getModifiedRecords();
        var rs = {};
        for (var i=0;i<p.length;i++) {
            rs[p[i].data[this.config.primaryKey || 'id']] = p[i].data;
        }
        return Ext.encode(rs);
    }
    ,encode: function() {
        var p = this.getStore().getRange();
        var rs = {};
        for (var i=0;i<p.length;i++) {
            rs[p[i].data[this.config.primaryKey || 'id']] = p[i].data;
        }
        return Ext.encode(rs);
    }
    
    ,expandAll: function() {
        if (!this.exp) return false;
        
        this.exp.expandAll(); 
        this.tools['plus'].hide();
        this.tools['minus'].show();
        return true;
    }
    
    ,collapseAll: function() {
        if (!this.exp) return false;
        
        this.exp.collapseAll();
        this.tools['minus'].hide();
        this.tools['plus'].show();
        return true;
    }
});

/* local grid */
MODx.grid.LocalGrid = function(config) {
    config = config || {};
    
    if (config.grouping) {
        Ext.applyIf(config,{
          view: new Ext.grid.GroupingView({ 
            forceFit: true 
            ,scrollOffset: 0
            ,hideGroupedColumn: config.hideGroupedColumn ? true : false
            ,groupTextTpl: config.groupTextTpl || ('{text} ({[values.rs.length]} {[values.rs.length > 1 ? "'
                +(config.pluralText || _('records')) + '" : "'
                +(config.singleText || _('record'))+'"]})' )
          })
        });
    }
    if (config.tbar) {
        for (var i = 0;i<config.tbar.length;i++) {
            var itm = config.tbar[i];
            if (itm.handler && typeof(itm.handler) == 'object' && itm.handler.xtype) {
                itm.handler = this.loadWindow.createDelegate(this,[itm.handler],true);
            }
            if (!itm.scope) { itm.scope = this; }
        }
    }
    Ext.applyIf(config,{
        title: ''
        ,store: this._loadStore(config)
        ,sm: new Ext.grid.RowSelectionModel({singleSelect:false})
        ,loadMask: true
        ,collapsible: true
        ,stripeRows: true
        ,enableColumnMove: true
        ,header: false
        ,cls: 'modx-grid'
        ,viewConfig: {
            forceFit: true
            ,enableRowBody: true
            ,autoFill: true
            ,showPreview: true
            ,scrollOffset: 0
            ,emptyText: config.emptyText || _('ext_emptymsg')
        }
        ,menuConfig: { defaultAlign: 'tl-b?' ,enableScrolling: false }
    });
    
    this.menu = new Ext.menu.Menu(config.menuConfig);
    this.config = config;
    this._loadColumnModel();
    MODx.grid.LocalGrid.superclass.constructor.call(this,config);
    this.addEvents({
        beforeRemoveRow: true
        ,afterRemoveRow: true
    });
    this.on('rowcontextmenu',this._showMenu,this);
};
Ext.extend(MODx.grid.LocalGrid,Ext.grid.EditorGridPanel,{
    windows: {}
    
    ,_loadStore: function(config) {
        if (config.grouping) {
            this.store = new Ext.data.GroupingStore({
                data: config.data || []
                ,reader: new Ext.data.ArrayReader({},config.fields || [])
                ,sortInfo: config.sortInfo || {
                    field: config.sortBy || 'name'
                    ,direction: config.sortDir || 'ASC'
                }
                ,groupField: config.groupBy || 'name'
            });
        } else {
            this.store = new Ext.data.SimpleStore({
                fields: config.fields
                ,data: config.data || []
            })
        }
        return this.store;
    }
    
    ,loadWindow: function(btn,e,win,or) {
        var r = this.menu.record;
        if (!this.windows[win.xtype]) {  
            Ext.applyIf(win,{
                scope: this
                ,success: this.refresh
                ,record: win.blankValues ? {} : r
            });
            if (or) {
                Ext.apply(win,or);
            }
            this.windows[win.xtype] = Ext.ComponentMgr.create(win);
        }
        if (this.windows[win.xtype].setValues && win.blankValues !== true && r != undefined) {
            this.windows[win.xtype].setValues(r);
        }
        this.windows[win.xtype].show(e.target);
    }
    
    ,_loadColumnModel: function() {
        if (this.config.columns) {
            var c = this.config.columns;
            for (var i=0;i<c.length;i++) {
                if (typeof(c[i].editor) == 'string') {
                    c[i].editor = eval(c[i].editor);
                }
                if (typeof(c[i].renderer) == 'string') {
                    c[i].renderer = eval(c[i].renderer);
                }
                if (typeof(c[i].editor) == 'object' && c[i].editor.xtype) {
                    var r = c[i].editor.renderer;
                    c[i].editor = Ext.ComponentMgr.create(c[i].editor);
                    if (r === true) {
                        c[i].renderer = MODx.combo.Renderer(c[i].editor);
                    } else if (c[i].editor.initialConfig.xtype === 'datefield') {
                        c[i].renderer = Ext.util.Format.dateRenderer(c[i].editor.initialConfig.format || 'Y-m-d');
                    } else if (r === 'boolean') {
                        c[i].renderer = this.rendYesNo;
                    } else if (r === 'local' && typeof(c[i].renderer) == 'string') {
                        c[i].renderer = eval(c[i].renderer);
                    }
                }
            }
            this.cm = new Ext.grid.ColumnModel(c);
        }
    }
    
    ,_showMenu: function(g,ri,e) {
        e.stopEvent();
        e.preventDefault();
        this.menu.recordIndex = ri;
        this.menu.record = this.getStore().getAt(ri).data;
        if (!this.getSelectionModel().isSelected(ri)) {
            this.getSelectionModel().selectRow(ri);
        }
        this.menu.removeAll();
        var m = this.getMenu(g,ri);
        if (m) {
            this.addContextMenuItem(m);
            this.menu.showAt(e.xy);
        }
    }
    
    ,getMenu: function() {
        return this.menu.record.menu;
    }
    
    ,addContextMenuItem: function(items) {
        var a = items, l = a.length;
        for(var i = 0; i < l; i++) {
            var options = a[i];
            
            if (options == '-') {
                this.menu.add('-');
                continue;
            }
            var h = Ext.emptyFn;
            if (options.handler) {
                h = eval(options.handler);
                if (h && typeof(h) == 'object' && h.xtype) {
                    h = this.loadWindow.createDelegate(this,[h],true);
                }
            } else {
                h = function(itm,e) {
                    var o = itm.options;
                    var id = this.menu.record.id;
                    var w = Ext.get('modx_content');
                    if (o.confirm) {
                        Ext.Msg.confirm('',o.confirm,function(e) {
                            if (e == 'yes') {
                                var a = Ext.urlEncode(o.params || {action: o.action});
                                var s = 'index.php?id='+id+'&'+a;
                                if (w === null) {
                                    location.href = s;
                                } else { w.dom.src = s; }
                            }
                        },this);
                    } else {
                        var a = Ext.urlEncode(o.params || {action: o.action});
                        var s = 'index.php?id='+id+'&'+a;
                        if (w === null) {
                            location.href = s;
                        } else { w.dom.src = s; }
                    }
                };
            }
            this.menu.add({
                id: options.id || Ext.id()
                ,text: options.text
                ,scope: this
                ,options: options
                ,handler: h
            });
        }
    }
    
    
    ,remove: function(config) {
        var r = this.getSelectionModel().getSelected();
        if (this.fireEvent('beforeRemoveRow',r)) {
            Ext.Msg.confirm(config.title || '',config.text || '',function(e) {
                if (e == 'yes') {
                    this.getStore().remove(r);
                    this.fireEvent('afterRemoveRow',r);
                }
            },this);
        }
    }
    
    ,encode: function() {
        var s = this.getStore();
        var ct = s.getCount();
        var rs = this.config.encodeByPk ? {} : [];
        var r;
        for (var j=0;j<ct;j++) {
            r = s.getAt(j).data;
            r.menu = null;
            if (this.config.encodeAssoc) {
               rs[r[this.config.encodeByPk || 'id']] = r;
            } else {
               rs.push(r);
            }
        }
        
        return Ext.encode(rs);
    }
    
    
    ,expandAll: function() {
        if (!this.exp) return false;
        
        this.exp.expandAll(); 
        this.tools['plus'].hide();
        this.tools['minus'].show();
        return true;
    }
    
    ,collapseAll: function() {
        if (!this.exp) return false;
        
        this.exp.collapseAll();
        this.tools['minus'].hide();
        this.tools['plus'].show();
        return true;
    }
    ,rendYesNo: function(d,c) {
        switch(d) {
            case '':
                return '-';
            case false:
                c.css = 'red';
                return _('no');
            case true:
                c.css = 'green';
                return _('yes');
        }
    }
});
Ext.reg('grid-local',MODx.grid.LocalGrid);
Ext.reg('modx-grid-local',MODx.grid.LocalGrid);

/* grid extensions */
Ext.ns('Ext.ux.grid');Ext.ux.grid.RowExpander=Ext.extend(Ext.util.Observable,{expandOnEnter:true,expandOnDblClick:true,header:'',width:20,sortable:false,fixed:true,menuDisabled:true,dataIndex:'',id:'expander',lazyRender:true,enableCaching:false,constructor:function(a){Ext.apply(this,a);this.addEvents({beforeexpand:true,expand:true,beforecollapse:true,collapse:true});Ext.ux.grid.RowExpander.superclass.constructor.call(this);if(this.tpl){if(typeof this.tpl=='string'){this.tpl=new Ext.Template(this.tpl)}this.tpl.compile()}this.state={};this.bodyContent={}},getRowClass:function(a,b,p,c){p.cols=p.cols-1;var d=this.bodyContent[a.id];if(!d&&!this.lazyRender){d=this.getBodyContent(a,b)}if(d){p.body=d}return this.state[a.id]?'x-grid3-row-expanded':'x-grid3-row-collapsed'},init:function(a){this.grid=a;var b=a.getView();b.getRowClass=this.getRowClass.createDelegate(this);b.enableRowBody=true;a.on('render',this.onRender,this);a.on('destroy',this.onDestroy,this)},onRender:function(){var a=this.grid;var b=a.getView().mainBody;b.on('mousedown',this.onMouseDown,this,{delegate:'.x-grid3-row-expander'});if(this.expandOnEnter){this.keyNav=new Ext.KeyNav(this.grid.getGridEl(),{'enter':this.onEnter,scope:this})}if(this.expandOnDblClick){a.on('rowdblclick',this.onRowDblClick,this)}},onDestroy:function(){this.keyNav.disable();delete this.keyNav;var a=this.grid.getView().mainBody;a.un('mousedown',this.onMouseDown,this)},onRowDblClick:function(a,b,e){this.toggleRow(b)},onEnter:function(e){var g=this.grid;var a=g.getSelectionModel();var b=a.getSelections();for(var i=0,len=b.length;i<len;i++){var c=g.getStore().indexOf(b[i]);this.toggleRow(c)}},getBodyContent:function(a,b){if(!this.enableCaching){return this.tpl.apply(a.data)}var c=this.bodyContent[a.id];if(!c){c=this.tpl.apply(a.data);this.bodyContent[a.id]=c}return c},onMouseDown:function(e,t){e.stopEvent();var a=e.getTarget('.x-grid3-row');this.toggleRow(a)},renderer:function(v,p,a){p.cellAttr='rowspan="2"';if(a.data.description!==null&&a.data.description===''){return''}return'<div class="x-grid3-row-expander">&#160;</div>'},beforeExpand:function(a,b,c){if(this.fireEvent('beforeexpand',this,a,b,c)!==false){if(this.tpl&&this.lazyRender){b.innerHTML=this.getBodyContent(a,c)}return true}else{return false}},toggleRow:function(a){if(typeof a=='number'){a=this.grid.view.getRow(a)}this[Ext.fly(a).hasClass('x-grid3-row-collapsed')?'expandRow':'collapseRow'](a)},expandRow:function(a){if(typeof a=='number'){a=this.grid.view.getRow(a)}var b=this.grid.store.getAt(a.rowIndex);var c=Ext.DomQuery.selectNode('tr:nth(2) div.x-grid3-row-body',a);if(this.beforeExpand(b,c,a.rowIndex)){this.state[b.id]=true;Ext.fly(a).replaceClass('x-grid3-row-collapsed','x-grid3-row-expanded');this.fireEvent('expand',this,b,c,a.rowIndex)}},collapseRow:function(a){if(typeof a=='number'){a=this.grid.view.getRow(a)}var b=this.grid.store.getAt(a.rowIndex);var c=Ext.fly(a).child('tr:nth(1) div.x-grid3-row-body',true);if(this.fireEvent('beforecollapse',this,b,c,a.rowIndex)!==false){this.state[b.id]=false;Ext.fly(a).replaceClass('x-grid3-row-expanded','x-grid3-row-collapsed');this.fireEvent('collapse',this,b,c,a.rowIndex)}},expandAll:function(){var a=this.grid.getView().getRows();for(var i=0;i<a.length;i++){this.expandRow(a[i])}},collapseAll:function(){var a=this.grid.getView().getRows();for(var i=0;i<a.length;i++){this.collapseRow(a[i])}}});Ext.preg('rowexpander',Ext.ux.grid.RowExpander);Ext.grid.RowExpander=Ext.ux.grid.RowExpander;Ext.ns('Ext.ux.grid');Ext.ux.grid.CheckColumn=function(a){Ext.apply(this,a);if(!this.id){this.id=Ext.id()}this.renderer=this.renderer.createDelegate(this)};Ext.ux.grid.CheckColumn.prototype={init:function(b){this.grid=b;this.grid.on('render',function(){var a=this.grid.getView();a.mainBody.on('mousedown',this.onMouseDown,this)},this)},onMouseDown:function(e,t){this.grid.fireEvent('rowclick');if(t.className&&t.className.indexOf('x-grid3-cc-'+this.id)!=-1){e.stopEvent();var a=this.grid.getView().findRowIndex(t);var b=this.grid.store.getAt(a);b.set(this.dataIndex,!b.data[this.dataIndex]);this.grid.fireEvent('afteredit')}},renderer:function(v,p,a){p.css+=' x-grid3-check-col-td';return'<div class="x-grid3-check-col'+(v?'-on':'')+' x-grid3-cc-'+this.id+'">&#160;</div>'}};Ext.preg('checkcolumn',Ext.ux.grid.CheckColumn);Ext.grid.CheckColumn=Ext.ux.grid.CheckColumn;Ext.grid.PropertyColumnModel=function(a,b){var g=Ext.grid,f=Ext.form;this.grid=a;g.PropertyColumnModel.superclass.constructor.call(this,[{header:this.nameText,width:50,sortable:true,dataIndex:'name',id:'name',menuDisabled:true},{header:this.valueText,width:50,resizable:false,dataIndex:'value',id:'value',menuDisabled:true}]);this.store=b;var c=new f.Field({autoCreate:{tag:'select',children:[{tag:'option',value:'true',html:'true'},{tag:'option',value:'false',html:'false'}]},getValue:function(){return this.el.dom.value=='true'}});this.editors={'date':new g.GridEditor(new f.DateField({selectOnFocus:true})),'string':new g.GridEditor(new f.TextField({selectOnFocus:true})),'number':new g.GridEditor(new f.NumberField({selectOnFocus:true,style:'text-align:left;'})),'boolean':new g.GridEditor(c)};this.renderCellDelegate=this.renderCell.createDelegate(this);this.renderPropDelegate=this.renderProp.createDelegate(this)};Ext.extend(Ext.grid.PropertyColumnModel,Ext.grid.ColumnModel,{nameText:'Name',valueText:'Value',dateFormat:'m/j/Y',renderDate:function(a){return a.dateFormat(this.dateFormat)},renderBool:function(a){return a?'true':'false'},isCellEditable:function(a,b){return a==1},getRenderer:function(a){return a==1?this.renderCellDelegate:this.renderPropDelegate},renderProp:function(v){return this.getPropertyName(v)},renderCell:function(a){var b=a;if(Ext.isDate(a)){b=this.renderDate(a)}else if(typeof a=='boolean'){b=this.renderBool(a)}return Ext.util.Format.htmlEncode(b)},getPropertyName:function(a){var b=this.grid.propertyNames;return b&&b[a]?b[a]:a},getCellEditor:function(a,b){var p=this.store.getProperty(b),n=p.data.name,val=p.data.value;if(this.grid.customEditors[n]){return this.grid.customEditors[n]}if(Ext.isDate(val)){return this.editors.date}else if(typeof val=='number'){return this.editors.number}else if(typeof val=='boolean'){return this.editors['boolean']}else{return this.editors.string}},destroy:function(){Ext.grid.PropertyColumnModel.superclass.destroy.call(this);for(var a in this.editors){Ext.destroy(a)}}});MODx.Console = function(config) {
    config = config || {};
    Ext.Updater.defaults.showLoadIndicator = false;
    Ext.applyIf(config,{
        title: _('console')
        ,modal: Ext.isIE ? false : true
        ,shadow: true
        ,resizable: false
        ,collapsible: false
        ,closable: false
        ,maximizable: true
        ,autoScroll: true
        ,height: 400
        ,width: 650
        ,refreshRate: 2
        ,cls: 'modx-window modx-console'
        ,items: [{
            itemId: 'header'
            ,cls: 'modx-console-text'
            ,html: _('console_running')
            ,border: false
        },{
            xtype: 'panel'
            ,itemId: 'body'
            ,cls: 'x-form-text modx-console-text'
        }]
        ,buttons: [{
            text: _('console_download_output')
            ,handler: this.download
            ,scope: this
        },{
            text: _('ok')
            ,id: 'modx-console-ok'
            ,itemId: 'okBtn'
            ,disabled: true
            ,scope: this
            ,handler: this.hideConsole
        }]
    });
    MODx.Console.superclass.constructor.call(this,config);
    this.config = config;
    this.addEvents({
        'shutdown': true
        ,'complete': true
    });
    this.on('show',this.init,this);
    this.on('hide',function() {
        this.getComponent('body').el.update('');
    });
    this.on('complete',this.onComplete,this);
};
Ext.extend(MODx.Console,Ext.Window,{
    mgr: null
    ,running: false
    
    ,init: function() {
        Ext.Msg.hide();
        this.fbar.getComponent('okBtn').setDisabled(true);
        this.getComponent('body').el.dom.innerHTML = '';
        
        this.provider = new Ext.direct.PollingProvider({
            type:'polling'
            ,url: MODx.config.connectors_url+'system/index.php'
            ,interval: 1000
            ,baseParams: {
                action: 'console'
                ,register: this.config.register || ''
                ,topic: this.config.topic || ''
                ,show_filename: this.config.show_filename || 0
                ,format: this.config.format || 'html_log'
            }
        });
        Ext.Direct.addProvider(this.provider);
        Ext.Direct.on('message', function(e,p) {
            var out = this.getComponent('body');
            if (out) {
                out.el.insertHtml('beforeEnd',e.data);
                e.data = '';
                out.el.scroll('b', out.el.getHeight(), true);
            }
            if (e.complete) {
                this.fireEvent('complete');
            }
            delete e;
        },this);
    }

    ,onComplete: function() {
        this.provider.disconnect();
        this.fbar.getComponent('okBtn').setDisabled(false);
    }
    
    ,download: function() {
        var c = this.getComponent('body').getEl().dom.innerHTML || '&nbsp;';
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'system/index.php'
            ,params: {
                action: 'downloadOutput'
                ,data: c
            }
            ,listeners: {
                'success':{fn:function(r) {
                    location.href = MODx.config.connectors_url+'system/index.php?action=downloadOutput&HTTP_MODAUTH='+MODx.siteId+'&download='+r.message;
                },scope:this}
            }            
        });
    }
        
    ,setRegister: function(register,topic) {
    	this.config.register = register;
        this.config.topic = topic;
    }
    
    ,hideConsole: function() {
        if (this.provider && this.provider.disconnect) {
            try {
                this.provider.disconnect();
            } catch (e) {}
        }
        this.fireEvent('shutdown');
        this.hide();
    }
});
Ext.reg('modx-console',MODx.Console);Ext.namespace('MODx.portal');

/* ext portal code */
Ext.ux.Portal=Ext.extend(Ext.Panel,{layout:'column',cls:'x-portal',defaultType:'portalcolumn',initComponent:function(){Ext.ux.Portal.superclass.initComponent.call(this);this.addEvents({validatedrop:true,beforedragover:true,dragover:true,beforedrop:true,drop:true})},initEvents:function(){Ext.ux.Portal.superclass.initEvents.call(this);this.dd=new Ext.ux.Portal.DropZone(this,this.dropConfig)},beforeDestroy:function(){if(this.dd){this.dd.unreg()}Ext.ux.Portal.superclass.beforeDestroy.call(this)}});Ext.reg('portal',Ext.ux.Portal);Ext.ux.Portal.DropZone=function(a,b){this.portal=a;Ext.dd.ScrollManager.register(a.body);Ext.ux.Portal.DropZone.superclass.constructor.call(this,a.bwrap.dom,b);a.body.ddScrollConfig=this.ddScrollConfig};Ext.extend(Ext.ux.Portal.DropZone,Ext.dd.DropTarget,{ddScrollConfig:{vthresh:50,hthresh:-1,animate:true,increment:200},createEvent:function(a,e,b,d,c,f){return{portal:this.portal,panel:b.panel,columnIndex:d,column:c,position:f,data:b,source:a,rawEvent:e,status:this.dropAllowed}},notifyOver:function(a,e,b){var d=e.getXY(),portal=this.portal,px=a.proxy;if(!this.grid){this.grid=this.getGrid()}var f=portal.body.dom.clientWidth;if(!this.lastCW){this.lastCW=f}else if(this.lastCW!=f){this.lastCW=f;portal.doLayout();this.grid=this.getGrid()}var g=0,xs=this.grid.columnX,cmatch=false;for(var i=xs.length;g<i;g++){if(d[0]<(xs[g].x+xs[g].w)){cmatch=true;break}}if(!cmatch){g--}var p,match=false,pos=0,c=portal.items.itemAt(g),items=c.items.items,overSelf=false;for(var i=items.length;pos<i;pos++){p=items[pos];var h=p.el.getHeight();if(h===0){overSelf=true}else if((p.el.getY()+(h/2))>d[1]){match=true;break}}pos=(match&&p?pos:c.items.getCount())+(overSelf?-1:0);var j=this.createEvent(a,e,b,g,c,pos);if(portal.fireEvent('validatedrop',j)!==false&&portal.fireEvent('beforedragover',j)!==false){px.getProxy().setWidth('auto');if(p){px.moveProxy(p.el.dom.parentNode,match?p.el.dom:null)}else{px.moveProxy(c.el.dom,null)}this.lastPos={c:c,col:g,p:overSelf||(match&&p)?pos:false};this.scrollPos=portal.body.getScroll();portal.fireEvent('dragover',j);return j.status}else{return j.status}},notifyOut:function(){delete this.grid},notifyDrop:function(a,e,b){delete this.grid;if(!this.lastPos){return}var c=this.lastPos.c,col=this.lastPos.col,pos=this.lastPos.p;var f=this.createEvent(a,e,b,col,c,pos!==false?pos:c.items.getCount());if(this.portal.fireEvent('validatedrop',f)!==false&&this.portal.fireEvent('beforedrop',f)!==false){a.proxy.getProxy().remove();a.panel.el.dom.parentNode.removeChild(a.panel.el.dom);if(pos!==false){if(c==a.panel.ownerCt&&(c.items.items.indexOf(a.panel)<=pos)){pos++}c.insert(pos,a.panel)}else{c.add(a.panel)}c.doLayout();this.portal.fireEvent('drop',f);var g=this.scrollPos.top;if(g){var d=this.portal.body.dom;setTimeout(function(){d.scrollTop=g},10)}}delete this.lastPos},getGrid:function(){var a=this.portal.bwrap.getBox();a.columnX=[];this.portal.items.each(function(c){a.columnX.push({x:c.el.getX(),w:c.el.getWidth()})});return a},unreg:function(){Ext.ux.Portal.DropZone.superclass.unreg.call(this)}});

MODx.portal.Column = Ext.extend(Ext.Container,{
    layout: 'anchor'
    ,defaultType: 'portlet'
    ,cls:'x-portal-column'
    ,style:'padding:10px;'
    ,columnWidth: 1
    ,defaults: {
        collapsible: true
        ,autoHeight: true
        ,titleCollapse: true
        ,draggable: true
        ,style: 'padding: 5px 0;'
        ,bodyStyle: 'padding: 15px;'
    }
});
Ext.reg('portalcolumn', MODx.portal.Column);

MODx.portal.Portlet = Ext.extend(Ext.Panel,{
    anchor: Ext.isSafari ? '98%' : '100%'
    ,frame:true
    ,collapsible:true
    ,draggable:true
    ,cls:'x-portlet'
    ,stateful: false
    ,layout: 'form'
});
Ext.reg('portlet', MODx.portal.Portlet);MODx.TreeDrop = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-treedrop'
        ,ddGroup: 'modx-treedrop-dd'
    })
    MODx.TreeDrop.superclass.constructor.call(this,config);
    this.config = config;
    this.setup();
};
Ext.extend(MODx.TreeDrop,Ext.Component,{    
    setup: function() {
        var ddTarget = this.config.target;
        var ddTargetEl = this.config.targetEl;
        var cfg = this.config;
        
        this.targetEl = new Ext.dd.DropTarget(this.config.targetEl, {
            ddGroup: this.config.ddGroup
            
            ,notifyEnter: function(ddSource, e, data) {
                if (ddTarget.getEl) {
                    var el = ddTarget.getEl();
                    if (el) { el.frame(); }
                }
            }
            ,notifyDrop: function(ddSource, e, data) {
                if (!data.node || !data.node.attributes || !data.node.attributes.type) return false;
                if (data.node.attributes.type != 'modResource' && data.node.attributes.leaf != true) return false;
                var v = '';
                var win = false;
                switch (data.node.attributes.type) {
                    case 'modResource': v = '[[~'+data.node.attributes.pk+']]'; break;
                    case 'snippet': win = true; break;
                    case 'chunk': win = true; break;
                    case 'tv': win = true; break;
                    case 'file': v = data.node.attributes.url; break;
                    default: return false; break;
                }
                if (win) {
                    MODx.loadInsertElement({
                        pk: data.node.attributes.pk
                        ,classKey: data.node.attributes.classKey
                        ,name: data.node.attributes.name
                        ,output: v
                        ,ddTargetEl: ddTargetEl
                        ,cfg: cfg
                        ,iframe: cfg.iframe
                        ,iframeEl: cfg.iframeEl
                        ,onInsert: cfg.onInsert
                        ,panel: cfg.panel
                    });
                } else {
                    if (cfg.iframe) {
                        MODx.insertForRTE(v,cfg);
                    } else {
                        var el = Ext.get(ddTargetEl);
                        if (el.dom.id == 'modx-static-content') {
                            v = v.substring(1);
                            Ext.getCmp(el.dom.id).setValue('');
                        }
                        if (el.dom.id == 'modx-symlink-content' || el.dom.id == 'modx-weblink-content') {
                            Ext.getCmp(el.dom.id).setValue('');
                            MODx.insertAtCursor(ddTargetEl,data.node.attributes.pk,cfg.onInsert);
                        } else if (el.dom.id == 'modx-resource-parent') {
                            v = data.node.attributes.pk;
                            Ext.getCmp('modx-resource-parent').setValue(v);
                            Ext.getCmp('modx-resource-parent-hidden').setValue(v);
                            var p = Ext.getCmp('modx-panel-resource');
                            if (p) { p.markDirty(); }
                        } else {
                            MODx.insertAtCursor(ddTargetEl,v,cfg.onInsert);
                        }

                        if (cfg.panel) {
                            var p = Ext.getCmp(cfg.panel);
                            if (p) { p.markDirty(); }
                        }
                    }
                }
                return true;
            }
        });
    }    
});
Ext.reg('modx-treedrop',MODx.TreeDrop);

MODx.loadInsertElement = function(r) {
    if (MODx.InsertElementWindow) {
        MODx.InsertElementWindow.hide();
        MODx.InsertElementWindow.destroy();
    }
    MODx.InsertElementWindow = MODx.load({
        xtype: 'modx-window-insert-element'
        ,record: r
        ,listeners: {
            'success':{fn: function() {            
            },scope:this}
            ,'hide': {fn:function() { this.destroy(); }}
        }
    });
    MODx.InsertElementWindow.setValues(r);
    MODx.InsertElementWindow.show();
};

MODx.insertAtCursor = function(myField, myValue,h) {
    if (!Ext.isEmpty(h)) {
        var z = h(myValue);
        if (z != undefined) {
            myValue = z;
        }
    }
    if (document.selection) { 
        myField.focus(); 
        sel = document.selection.createRange(); 
        sel.text = myValue; 
    } else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart; 
        var endPos = myField.selectionEnd; 
        myField.value = myField.value.substring(0, startPos)+ myValue+ myField.value.substring(endPos, myField.value.length); 
    } else { 
        myField.value += myValue; 
    }
};
MODx.insertForRTE = function(v,cfg) {
    var fn = cfg.onInsert || false;
    if (fn) {
        fn(v,cfg);
    } else {
        if (typeof cfg.iframeEl == 'object') {
            var doc = cfg.iframeEl; 
        } else {
            var doc = window.frames[0].document.getElementById(cfg.iframeEl);
        }
        if (doc.value) {
            doc.value = doc.value + v;
        } else {
            doc.innerHTML = doc.innerHTML + v;
        }
    }
};

MODx.window.InsertElement = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('select_el_opts')
        ,id: 'modx-window-insert-element' 
        ,width: 600
        ,url: MODx.config.connectors_url+'element/template.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'pk'
            ,id: 'modx-dise-pk'
        },{
            xtype: 'hidden'
            ,name: 'classKey'
            ,id: 'modx-dise-classkey'
        },{
            xtype: 'xcheckbox'
            ,fieldLabel: _('cached')
            ,name: 'cached'
            ,id: 'modx-dise-cached'
            ,inputValue: 1
            ,checked: true
        },{
            xtype: 'modx-combo-property-set'
            ,fieldLabel: _('property_set')
            ,name: 'propertyset'
            ,id: 'modx-dise-propset'
            ,baseParams: {
                action: 'getList'
                ,showAssociated: true
                ,elementId: config.record.pk
                ,elementType: config.record.classKey
            }
            ,listeners: {
                'select': {fn:this.changePropertySet,scope:this}
            }
        },{
            id: 'modx-dise-proplist'
            ,autoLoad: {
                url: MODx.config.connectors_url+'element/index.php'
                ,params: {
                   'action': 'getInsertProperties'
                   ,classKey: config.record.classKey
                   ,pk: config.record.pk
                   ,propertySet: 0
                }
                ,scripts: true
                ,callback: this.onPropFormLoad
                ,scope: this
            }
            ,style: 'display: none;'
        },{
            xtype: 'fieldset'
            ,title: _('properties')
            ,autoHeight: true
            ,collapsible: true
            ,autoScroll: true
            ,items: [{
                html: '<div id="modx-iprops-form"></div>'
                ,height: 400
                ,autoScroll: true
            }]
        }]
    });
    MODx.window.InsertElement.superclass.constructor.call(this,config);
    this.on('show',function() { this.center(); },this);
};
Ext.extend(MODx.window.InsertElement,MODx.Window,{
    changePropertySet: function(cb) {
        var fp = Ext.getCmp('modx-iprops-fp');
        if (fp) fp.destroy();

        var u = Ext.getCmp('modx-dise-proplist').getUpdater();
        u.update({
            url: MODx.config.connectors_url+'element/index.php'
            ,params: {
                'action': 'getInsertProperties'
                ,classKey: this.config.record.classKey
                ,pk: this.config.record.pk
                ,propertySet: cb.getValue()
            }
            ,scripts: true
            ,callback: this.onPropFormLoad
            ,scope: this
        });
        this.modps = [];
    }
    ,createStore: function(data) {
        return new Ext.data.SimpleStore({
            fields: ["v","d"]
            ,data: data
        });
    }
    ,onPropFormLoad: function(el,s,r) {
        var vs = Ext.decode(r.responseText);
        if (!vs || vs.length <= 0) { return false; }
        for (var i=0;i<vs.length;i++) {
            if (vs[i].store) {
                vs[i].store = this.createStore(vs[i].store);
            }
        }
        MODx.load({
            xtype: 'panel'
            ,id: 'modx-iprops-fp'
            ,layout: 'form'
            ,autoHeight: true
            ,autoScroll: true
            ,labelWidth: 150
            ,border: false
            ,items: vs
            ,renderTo: 'modx-iprops-form'
        });
    }
    ,submit: function() {
        var v = '[[';
        var n = this.config.record.name;
        var f = this.fp.getForm();
        
        if (f.findField('cached').getValue() != true) {
            v = v+'!';
        }
        switch (this.config.record.classKey) {
            case 'modSnippet': v = v+n; break;
            case 'modChunk': v = v+'$'+n; break;
            case 'modTemplateVar': v = v+'*'+n; break;
        }
        var ps = f.findField('propertyset').getValue();
        if (ps !== 0 && ps !== '') {
            v = v+'@'+f.findField('propertyset').getRawValue();
        }
        v = v+'?';
        
        for (var i=0;i<this.modps.length;i++) {
            var fld = this.modps[i];
            var val = Ext.getCmp('modx-iprop-'+fld).getValue();
            if (val == true) val = 1;
            if (val == false) val = 0;
            v = v+' &'+fld+'=`'+val+'`';
        }
        v = v+']]';
        
        if (this.config.record.iframe) {
            MODx.insertForRTE(v,this.config.record.cfg);
        } else {
            MODx.insertAtCursor(this.config.record.ddTargetEl,v);
        }
        this.hide();
        return true;
    }
    ,modps: []
    ,changeProp: function(k) {
        if (this.modps.indexOf(k) == -1) {
            this.modps.push(k);
        }
    }
});
Ext.reg('modx-window-insert-element',MODx.window.InsertElement);
/** 
 * Generates the Duplicate Resource window.
 *  
 * @class MODx.window.DuplicateResource
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-resource-duplicate
 */
MODx.window.DuplicateResource = function(config) {
    config = config || {};
    this.ident = config.ident || 'dupres'+Ext.id();
    Ext.applyIf(config,{
        title: _('duplication_options')
        ,id: this.ident
        ,width: 400
    });
    MODx.window.DuplicateResource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.DuplicateResource,MODx.Window,{
    _loadForm: function() {
        if (this.checkIfLoaded(this.config.record)) {
            this.fp.getForm().baseParams = {
                action: 'duplicate'
                ,prefixDuplicate: true
                ,id: this.config.resource
            };
            return false;
        }
        var items = [];

        if (this.config.hasChildren) {
            items.push({
                xtype: 'xcheckbox'
                ,fieldLabel: _('duplicate_children')
                ,name: 'duplicate_children'
                ,id: 'modx-'+this.ident+'-duplicate-children'
                ,checked: true
                ,listeners: {
                    'check': {fn: function(cb,checked) {
                        if (checked) {
                            this.fp.getForm().findField('modx-'+this.ident+'-name').disable();
                        } else {
                            this.fp.getForm().findField('modx-'+this.ident+'-name').enable();
                        }
                    },scope:this}
                }
            });
        }
        items.push({
            xtype: 'textfield'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('resource_name_new')
            ,name: 'name'
            ,anchor: '90%'
            ,value: ''
        });

        this.fp = this.createForm({
            url: this.config.url || MODx.config.connectors_url+'resource/index.php'
            ,baseParams: this.config.baseParams || {
                action: 'duplicate'
                ,id: this.config.resource
                ,prefixDuplicate: true
            }
            ,labelWidth: 125
            ,defaultType: 'textfield'
            ,autoHeight: true
            ,items: items
        });

        this.renderForm();
    }
});
Ext.reg('modx-window-resource-duplicate',MODx.window.DuplicateResource);

MODx.window.CreateCategory = function(config) {
    config = config || {};
    this.ident = config.ident || 'ccat'+Ext.id();
    Ext.applyIf(config,{
        title: _('new_category')
        ,id: this.ident
        ,height: 150
        ,width: 350
        ,url: MODx.config.connectors_url+'element/category.php'
        ,action: 'create'
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'category'
            ,id: 'modx-'+this.ident+'-category'
            ,xtype: 'textfield'
            ,anchor: '90%'
        },{
            fieldLabel: _('parent')
            ,name: 'parent'
            ,hiddenName: 'parent'
            ,id: 'modx-'+this.ident+'-parent'
            ,xtype: 'modx-combo-category'
            ,anchor: '90%'
        }]
    });
    MODx.window.CreateCategory.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateCategory,MODx.Window);
Ext.reg('modx-window-category-create',MODx.window.CreateCategory);


MODx.window.CreateNamespace = function(config) {
    config = config || {};
    var r = config.record;
    this.ident = config.ident || 'cns'+Ext.id();
    Ext.applyIf(config,{
        title: _('namespace_create')
        ,id: this.ident
        ,width: 600
        ,url: MODx.config.connectors_url+'workspace/namespace.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,anchor: '90%'
            ,maxLength: 100
        },{
            xtype: 'textfield'
            ,fieldLabel: _('path')
            ,description: _('namespace_path_desc')
            ,name: 'path'
            ,id: 'modx-'+this.ident+'-path'
            ,anchor: '97%'
        }]
    });
    MODx.window.CreateNamespace.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateNamespace,MODx.Window);
Ext.reg('modx-window-namespace-create',MODx.window.CreateNamespace);


MODx.window.QuickCreateChunk = function(config) {
    config = config || {};
    this.ident = config.ident || 'qcc'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_create_chunk')
        ,id: this.ident
        ,width: 600
        ,url: MODx.config.connectors_url+'element/chunk.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('name')
            ,anchor: '90%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '90%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '97%'
            ,rows: 2
        },{
            xtype: 'textarea'
            ,name: 'snippet'
            ,id: 'modx-'+this.ident+'-snippet'
            ,fieldLabel: _('code')
            ,anchor: '97%'
            ,grow: true, growMax: 380
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
    });
    MODx.window.QuickCreateChunk.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreateChunk,MODx.Window);
Ext.reg('modx-window-quick-create-chunk',MODx.window.QuickCreateChunk);

MODx.window.QuickUpdateChunk = function(config) {
    config = config || {};
    this.ident = config.ident || 'quc'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_update_chunk')
        ,id: this.ident
        ,width: 600
        ,url: MODx.config.connectors_url+'element/chunk.php'
        ,action: 'update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-'+this.ident+'-id'
        },{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('name')
            ,anchor: '90%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '90%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '97%'
            ,rows: 2
        },{
            xtype: 'xcheckbox'
            ,name: 'clearCache'
            ,id: 'modx-'+this.ident+'-clearcache'
            ,fieldLabel: _('clear_cache_on_save')
            ,description: _('clear_cache_on_save_msg')
            ,inputValue: 1
            ,checked: true
        },{
            xtype: 'textarea'
            ,name: 'snippet'
            ,id: 'modx-'+this.ident+'-snippet'
            ,fieldLabel: _('code')
            ,anchor: '97%'
            ,grow: true ,growMax: 380
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: config.saveBtnText || _('save')
            ,scope: this
            ,handler: function() { this.submit(false); }
        },{
            text: config.saveBtnText || _('save_and_close')
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.window.QuickUpdateChunk.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateChunk,MODx.Window);
Ext.reg('modx-window-quick-update-chunk',MODx.window.QuickUpdateChunk);

MODx.window.QuickCreateTemplate = function(config) {
    config = config || {};
    this.ident = config.ident || 'qct'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_create_template')
        ,id: this.ident
        ,width: 600
        ,url: MODx.config.connectors_url+'element/template.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,name: 'templatename'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('name')
            ,anchor: '90%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '90%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '97%'
            ,rows: 2
        },{
            xtype: 'textarea'
            ,name: 'content'
            ,id: 'modx-'+this.ident+'-content'
            ,fieldLabel: _('code')
            ,anchor: '97%'
            ,grow: true ,growMax: 380
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
    });
    MODx.window.QuickCreateTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreateTemplate,MODx.Window);
Ext.reg('modx-window-quick-create-template',MODx.window.QuickCreateTemplate);

MODx.window.QuickUpdateTemplate = function(config) {
    config = config || {};
    this.ident = config.ident || 'qut'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_update_template')
        ,id: this.ident
        ,width: 600
        ,url: MODx.config.connectors_url+'element/template.php'
        ,action: 'update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-'+this.ident+'-id'
        },{
            xtype: 'textfield'
            ,name: 'templatename'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('name')
            ,anchor: '90%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '90%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '97%'
            ,rows: 2
        },{
            xtype: 'xcheckbox'
            ,name: 'clearCache'
            ,id: 'modx-'+this.ident+'-clearcache'
            ,fieldLabel: _('clear_cache_on_save')
            ,description: _('clear_cache_on_save_msg')
            ,inputValue: 1
            ,checked: true
        },{
            xtype: 'textarea'
            ,name: 'content'
            ,id: 'modx-'+this.ident+'-content'
            ,fieldLabel: _('code')
            ,anchor: '97%'
            ,grow: true ,growMax: 380
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: config.saveBtnText || _('save')
            ,scope: this
            ,handler: function() { this.submit(false); }
        },{
            text: config.saveBtnText || _('save_and_close')
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.window.QuickUpdateTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateTemplate,MODx.Window);
Ext.reg('modx-window-quick-update-template',MODx.window.QuickUpdateTemplate);


MODx.window.QuickCreateSnippet = function(config) {
    config = config || {};
    this.ident = config.ident || 'qcs'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_create_snippet')
        ,id: this.ident
        ,width: 600
        ,url: MODx.config.connectors_url+'element/snippet.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('name')
            ,anchor: '90%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '90%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '97%'
            ,rows: 2
        },{
            xtype: 'textarea'
            ,name: 'snippet'
            ,id: 'modx-'+this.ident+'-snippet'
            ,fieldLabel: _('code')
            ,anchor: '97%'
            ,grow: true ,growMax: 380
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
    });
    MODx.window.QuickCreateSnippet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreateSnippet,MODx.Window);
Ext.reg('modx-window-quick-create-snippet',MODx.window.QuickCreateSnippet);

MODx.window.QuickUpdateSnippet = function(config) {
    config = config || {};
    this.ident = config.ident || 'qus'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_update_snippet')
        ,id: this.ident
        ,width: 600
        ,url: MODx.config.connectors_url+'element/snippet.php'
        ,action: 'update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-'+this.ident+'-id'
        },{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('name')
            ,anchor: '90%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '90%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '97%'
            ,rows: 2
        },{
            xtype: 'xcheckbox'
            ,name: 'clearCache'
            ,id: 'modx-'+this.ident+'-clearcache'
            ,fieldLabel: _('clear_cache_on_save')
            ,description: _('clear_cache_on_save_msg')
            ,inputValue: 1
            ,checked: true
        },{
            xtype: 'textarea'
            ,name: 'snippet'
            ,id: 'modx-'+this.ident+'-snippet'
            ,fieldLabel: _('code')
            ,anchor: '97%'
            ,grow: true
            ,growMax: 380
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: config.saveBtnText || _('save')
            ,scope: this
            ,handler: function() { this.submit(false); }
        },{
            text: config.saveBtnText || _('save_and_close')
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.window.QuickUpdateSnippet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateSnippet,MODx.Window);
Ext.reg('modx-window-quick-update-snippet',MODx.window.QuickUpdateSnippet);



MODx.window.QuickCreatePlugin = function(config) {
    config = config || {};
    this.ident = config.ident || 'qcp'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_create_plugin')
        ,id: this.ident
        ,width: 600
        ,url: MODx.config.connectors_url+'element/plugin.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('name')
            ,anchor: '90%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '90%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '97%'
            ,rows: 2
        },{
            xtype: 'xcheckbox'
            ,name: 'disabled'
            ,id: 'modx-'+this.ident+'-disabled'
            ,fieldLabel: _('disabled')
            ,inputValue: 1
            ,checked: false
        },{
            xtype: 'textarea'
            ,name: 'plugincode'
            ,id: 'modx-'+this.ident+'-plugincode'
            ,fieldLabel: _('code')
            ,anchor: '97%'
            ,grow: true ,growMax: 380
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
    });
    MODx.window.QuickCreatePlugin.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreatePlugin,MODx.Window);
Ext.reg('modx-window-quick-create-plugin',MODx.window.QuickCreatePlugin);

MODx.window.QuickUpdatePlugin = function(config) {
    config = config || {};
    this.ident = config.ident || 'qup'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_update_plugin')
        ,id: this.ident
        ,width: 600
        ,url: MODx.config.connectors_url+'element/plugin.php'
        ,action: 'update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-'+this.ident+'-id'
        },{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('name')
            ,anchor: '90%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '90%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '97%'
            ,rows: 2
        },{
            xtype: 'xcheckbox'
            ,name: 'disabled'
            ,id: 'modx-'+this.ident+'-disabled'
            ,fieldLabel: _('disabled')
            ,inputValue: 1
            ,checked: false
        },{
            xtype: 'xcheckbox'
            ,name: 'clearCache'
            ,id: 'modx-'+this.ident+'-clearcache'
            ,fieldLabel: _('clear_cache_on_save')
            ,description: _('clear_cache_on_save_msg')
            ,inputValue: 1
            ,checked: true
        },{
            xtype: 'textarea'
            ,name: 'plugincode'
            ,id: 'modx-'+this.ident+'-plugincode'
            ,fieldLabel: _('code')
            ,anchor: '97%'
            ,grow: true ,growMax: 380
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: config.saveBtnText || _('save')
            ,scope: this
            ,handler: function() { this.submit(false); }
        },{
            text: config.saveBtnText || _('save_and_close')
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.window.QuickUpdatePlugin.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdatePlugin,MODx.Window);
Ext.reg('modx-window-quick-update-plugin',MODx.window.QuickUpdatePlugin);


MODx.window.QuickCreateTV = function(config) {
    config = config || {};
    this.ident = config.ident || 'qctv'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_create_tv')
        ,id: this.ident
        ,width: 600
        ,url: MODx.config.connectors_url+'element/tv.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('name')
            ,anchor: '90%'
        },{
            xtype: 'textfield'
            ,name: 'caption'
            ,id: 'modx-'+this.ident+'-caption'
            ,fieldLabel: _('caption')
            ,anchor: '90%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '90%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '97%'
            ,rows: 2
        },{
            xtype: 'modx-combo-tv-input-type'
            ,fieldLabel: _('tv_type')
            ,name: 'type'
            ,id: 'modx-'+this.ident+'-type'
            ,anchor: '70%'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('tv_elements')
            ,name: 'els'
            ,id: 'modx-'+this.ident+'-elements'
            ,anchor: '80%'
        },{
            xtype: 'textarea'
            ,fieldLabel: _('tv_default')
            ,name: 'default_text'
            ,id: 'modx-'+this.ident+'-default-text'
            ,anchor: '97%'
            ,grow: true ,growMax: 380
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
    });
    MODx.window.QuickCreateTV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreateTV,MODx.Window);
Ext.reg('modx-window-quick-create-tv',MODx.window.QuickCreateTV);

MODx.window.QuickUpdateTV = function(config) {
    config = config || {};
    this.ident = config.ident || 'qutv'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_update_tv')
        ,id: this.ident
        ,width: 600
        ,url: MODx.config.connectors_url+'element/tv.php'
        ,action: 'update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-'+this.ident+'-id'
        },{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('name')
            ,anchor: '90%'
        },{
            xtype: 'textfield'
            ,name: 'caption'
            ,id: 'modx-'+this.ident+'-caption'
            ,fieldLabel: _('caption')
            ,anchor: '90%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '90%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '97%'
            ,rows: 2
        },{
            xtype: 'xcheckbox'
            ,name: 'clearCache'
            ,id: 'modx-'+this.ident+'-clearcache'
            ,fieldLabel: _('clear_cache_on_save')
            ,description: _('clear_cache_on_save_msg')
            ,inputValue: 1
            ,checked: true
        },{
            xtype: 'modx-combo-tv-input-type'
            ,fieldLabel: _('tv_type')
            ,name: 'type'
            ,id: 'modx-'+this.ident+'-type'
            ,anchor: '70%'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('tv_elements')
            ,name: 'els'
            ,id: 'modx-'+this.ident+'-elements'
            ,anchor: '80%'
        },{
            xtype: 'textarea'
            ,fieldLabel: _('tv_default')
            ,name: 'default_text'
            ,id: 'modx-'+this.ident+'-default-text'
            ,anchor: '97%'
            ,grow: true
            ,growMax: 380
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: config.saveBtnText || _('save')
            ,scope: this
            ,handler: function() { this.submit(false); }
        },{
            text: config.saveBtnText || _('save_and_close')
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.window.QuickUpdateTV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateTV,MODx.Window);
Ext.reg('modx-window-quick-update-tv',MODx.window.QuickUpdateTV);


MODx.window.DuplicateContext = function(config) {
    config = config || {};
    this.ident = config.ident || 'dupctx'+Ext.id();
    Ext.Ajax.timeout = 0;
    Ext.applyIf(config,{
        title: _('context_duplicate')
        ,id: this.ident
        ,url: MODx.config.connectors_url+'context/index.php'
        ,action: 'duplicate'        
        ,width: 400
        ,fields: [{
            xtype: 'statictextfield'
            ,id: 'modx-'+this.ident+'-key'
            ,fieldLabel: _('old_key')
            ,name: 'key'
            ,anchor: '90%'
            ,submitValue: true
        },{
            xtype: 'textfield'
            ,id: 'modx-'+this.ident+'-newkey'
            ,fieldLabel: _('new_key')
            ,name: 'newkey'
            ,anchor: '90%'
            ,value: ''
        }]
    });
    MODx.window.DuplicateContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.DuplicateContext,MODx.Window);
Ext.reg('modx-window-context-duplicate',MODx.window.DuplicateContext);
/**
 * Generates the Resource Tree in Ext
 * 
 * @class MODx.tree.Resource
 * @extends MODx.tree.Tree
 * @param {Object} config An object of options.
 * @xtype modx-tree-resource
 */
MODx.tree.Resource = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'resource/index.php'
        ,title: ''
        ,rootVisible: false
        ,expandFirst: true
        ,enableDD: !Ext.isEmpty(MODx.config.enable_dragdrop) ? true : false
        ,ddGroup: 'modx-treedrop-dd'
        ,remoteToolbar: true
        ,sortBy: MODx.config.tree_default_sort || 'menuindex'
        ,tbarCfg: {
            id: config.id ? config.id+'-tbar' : 'modx-tree-resource-tbar'
        }
        ,baseParams: {
            action: 'getNodes'
            ,sortBy: MODx.config.tree_default_sort || 'menuindex'
            ,currentResource: MODx.request.id || 0
        }
    });
    MODx.tree.Resource.superclass.constructor.call(this,config);
    this.getLoader().baseParams.sortBy = Ext.state.Manager.get(this.treestate_id+'-sort') || (MODx.config.tree_default_sort || 'menuindex');
    this.on('render',function() {
        var el = Ext.get('modx-resource-tree');
        el.createChild({tag: 'div', id: 'modx-resource-tree_tb'});
        el.createChild({tag: 'div', id: 'modx-resource-tree_filter'});
    });
    this.addEvents('loadCreateMenus');
    this.on('afterSort',this._handleAfterDrop,this);
};
Ext.extend(MODx.tree.Resource,MODx.tree.Tree,{
    forms: {}
    ,windows: {}
    ,stores: {}

    ,_initExpand: function() {
        var treeState = Ext.state.Manager.get(this.treestate_id);
        if ((Ext.isString(treeState) || Ext.isEmpty(treeState)) && this.root) {
            if (this.root) {this.root.expand();}
            var wn = this.getNodeById('web_0');
            if (wn && this.config.expandFirst) {
                wn.select();
                wn.expand();
            }
        } else {
            for (var i=0;i<treeState.length;i++) {
                this.expandPath(treeState[i]);
            }
        }
    }


    /**
     * Shows the current context menu.
     * @param {Ext.tree.TreeNode} n The current node
     * @param {Ext.EventObject} e The event object run.
     */
    ,_showContextMenu: function(n,e) {
        n.select();
        this.cm.activeNode = n;
        this.cm.removeAll();
        if (n.attributes.menu && n.attributes.menu.items) {
            this.addContextMenuItem(n.attributes.menu.items);
            this.cm.show(n.getUI().getEl(),'t?');
        } else {
            var m = [];
            switch (n.attributes.type) {
                case 'modResource':
                    m = this._getModResourceMenu(n);
                    break;
                case 'modContext':
                    m = this._getModContextMenu(n);
                    break;
            }
            
            this.addContextMenuItem(m);
            this.cm.showAt(e.xy);
        }
        e.stopEvent();
    }

    ,duplicateResource: function(item,e) {
        var node = this.cm.activeNode;
        var id = node.id.split('_');id = id[1];

        var r = {
            resource: id
            ,is_folder: node.getUI().hasClass('folder')
        };
        var w = MODx.load({
            xtype: 'modx-window-resource-duplicate'
            ,resource: id
            ,hasChildren: node.attributes.hasChildren
            ,listeners: {
                'success': {fn:function() {this.refreshNode(node.id);},scope:this}
            }
        });
        console.log(node.attributes);
        w.config.hasChildren = node.attributes.hasChildren;
        w.setValues(r);
        w.show(e.target);
    }

    ,duplicateContext: function(itm,e) {
        var node = this.cm.activeNode;
        var key = node.attributes.pk;
        
        var r = { 
            key: key
            ,newkey: ''
        };
        if (!this.windows.duplicateContext) {
            this.windows.duplicateContext = MODx.load({
                xtype: 'modx-window-context-duplicate'
                ,record: r
                ,listeners: {
                    'success': {fn:function() {this.refresh();},scope:this}
                }
            });
        }
        this.windows.duplicateContext.setValues(r);
        this.windows.duplicateContext.show(e.target);
    }
    ,removeContext: function(itm,e) {
        var node = this.cm.activeNode;
        var key = node.attributes.pk;
        MODx.msg.confirm({
            title: _('context_remove')
            ,text: _('context_remove_confirm')
            ,url: MODx.config.connectors_url+'context/index.php'
            ,params: {
                action: 'remove'
                ,key: key
            }
            ,listeners: {
                'success': {fn:function() {this.refresh();},scope:this}
            }
        });
    }
    	
    ,preview: function() {
        window.open(this.cm.activeNode.attributes.preview_url);
    }
    
    ,deleteDocument: function(itm,e) {
        var node = this.cm.activeNode;
        var id = node.id.split('_');id = id[1];
        MODx.msg.confirm({
            title: _('resource_delete')
            ,text: _('resource_delete_confirm')
            ,url: MODx.config.connectors_url+'resource/index.php'
            ,params: {
                action: 'delete'
                ,id: id
            }
            ,listeners: {
                'success': {fn:function() {
                    var n = this.cm.activeNode;
                    var ui = n.getUI();
                    
                    ui.addClass('deleted');
                    n.cascade(function(nd) {
                        nd.getUI().addClass('deleted');
                    },this);
                    Ext.get(ui.getEl()).frame();
                },scope:this}
            }
        });
    }

    ,undeleteDocument: function(itm,e) {
        var node = this.cm.activeNode;
        var id = node.id.split('_');id = id[1];
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'resource/index.php'
            ,params: {
                action: 'undelete'
                ,id: id
            }
            ,listeners: {
                'success': {fn:function() {
                    var n = this.cm.activeNode;
                    var ui = n.getUI();

                    ui.removeClass('deleted');
                    n.cascade(function(nd) {
                        nd.getUI().removeClass('deleted');
                    },this);
                    Ext.get(ui.getEl()).frame();
                },scope:this}
            }
        });
    }

    ,publishDocument: function(itm,e) {
        var node = this.cm.activeNode;
        var id = node.id.split('_');id = id[1];
        MODx.msg.confirm({
            title: _('resource_publish')
            ,text: _('resource_publish_confirm')
            ,url: MODx.config.connectors_url+'resource/index.php'
            ,params: {
                action: 'publish'
                ,id: id
            }
            ,listeners: {
                'success': {fn:function() {
                    var ui = this.cm.activeNode.getUI();
                    ui.removeClass('unpublished');
                    Ext.get(ui.getEl()).frame();
                },scope:this}
            }
        });
    }
	
    ,unpublishDocument: function(itm,e) {
        var node = this.cm.activeNode;
        var id = node.id.split('_');id = id[1];
        MODx.msg.confirm({
            title: _('resource_unpublish')
            ,text: _('resource_unpublish_confirm')
            ,url: MODx.config.connectors_url+'resource/index.php'
            ,params: {
                action: 'unpublish'
                ,id: id
            }
            ,listeners: {
                'success': {fn:function() {
                    var ui = this.cm.activeNode.getUI();
                    ui.addClass('unpublished');
                    Ext.get(ui.getEl()).frame();
                },scope:this}
            }
        });
    }
	
    ,emptyRecycleBin: function() {
        MODx.msg.confirm({
            title: _('empty_recycle_bin')
            ,text: _('empty_recycle_bin_confirm')
            ,url: MODx.config.connectors_url+'resource/index.php'
            ,params: {
                action: 'emptyRecycleBin'
            }
            ,listeners: {
                'success':{fn:function() {
                    Ext.select('div.deleted',this.getRootNode()).remove();
                    MODx.msg.status({
                        title: _('success')
                        ,message: _('empty_recycle_bin_emptied')
                    })
                },scope:this}
            }
        });
    }

    ,showFilter: function(itm,e) {
        if (this._filterVisible) {return false;}

        var t = Ext.get(this.config.id+'-tbar');
        var fbd = t.createChild({tag: 'div' ,cls: 'modx-formpanel' ,autoHeight: true});
        var tb = new Ext.Toolbar({
            applyTo: fbd
            ,autoHeight: true
            ,width: '100%'
        });
        var cb = new Ext.form.ComboBox({
            store: new Ext.data.SimpleStore({
                fields: ['name','value']
                ,data: [
                    [_('menu_order'),'menuindex']
                    ,[_('page_title'),'pagetitle']
                    ,[_('publish_date'),'pub_date']
                    ,[_('createdon'),'createdon']
                    ,[_('editedon'),'editedon']
                    ,[_('publishedon'),'publishedon']
                ]
            })
            ,displayField: 'name'
            ,valueField: 'value'
            ,editable: false
            ,mode: 'local'
            ,id: 'modx-resource-tree-sortby'
            ,triggerAction: 'all'
            ,selectOnFocus: false
            ,width: 100
            ,value: this.config.sortBy || (Ext.state.Manager.get(this.treestate_id+'-sort') || MODx.config.tree_default_sort)
            ,listeners: {
                'select': {fn:this.filterSort,scope:this}
            }
        });
        tb.add(_('sort_by')+':');
        tb.addField(cb);
        tb.add('-',{
            scope: this
            ,cls: 'x-btn-text'
            ,text: _('close')
            ,handler: this.hideFilter
        });
        tb.doLayout();
        this.filterBar = tb;
        this._filterVisible = true;
        return true;
    }
	
    ,filterSort: function(cb,r,i) {
        Ext.state.Manager.set(this.treestate_id+'-sort',cb.getValue());
        this.config.sortBy = cb.getValue();
        this.getLoader().baseParams = {
            action: this.config.action
            ,sortBy: this.config.sortBy
        };
        this.refresh();
    }

    ,hideFilter: function(itm,e) {
        this.filterBar.destroy();
        this._filterVisible = false;
    }
    ,_handleAfterDrop: function(o,r) {
        var targetNode = o.event.target;
        if (o.event.point == 'append' && targetNode) {
            var ui = targetNode.getUI();
            ui.addClass('haschildren');
            ui.removeClass('icon-resource');
        }
    }
	
    ,_handleDrop:  function(e){
        var dropNode = e.dropNode;
        var targetParent = e.target;

        if (targetParent.findChild('id',dropNode.attributes.id) !== null) {return false;}        
        var ap = true;
        if (targetParent.attributes.type == 'context' && e.point != 'append') {
            ap = false;
        }        
        return dropNode.attributes.text != 'root' && dropNode.attributes.text !== '' 
            && targetParent.attributes.text != 'root' && targetParent.attributes.text !== ''
            && ap;
    }
    
    ,quickCreate: function(itm,e,cls,ctx,p) {
        cls = cls || 'modResource';
        var r = {
            class_key: cls
            ,context_key: ctx || 'web'
            ,'parent': p || 0
        };
        
        var w = MODx.load({
            xtype: 'modx-window-quick-create-modResource'
            ,record: r
            ,listeners: {
                'success':{fn:function() { 
                    var node = this.getNodeById(this.cm.activeNode.id);
                    if (node) {
                        var n = node.parentNode ? node.parentNode : node;
                        this.getLoader().load(n,function() {
                            n.expand();
                        },this);
                    }
                },scope:this}
                ,'hide':{fn:function() {this.destroy();}}
                ,'show':{fn:function() {this.center();}}
            }
        });
        w.setValues(r);
        w.show(e.target,function() {
            Ext.isSafari ? w.setPosition(null,30) : w.center();
        },this);
    }
    
    ,quickUpdate: function(itm,e,cls) {        
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'resource/index.php'
            ,params: {
                action: 'get'
                ,id: this.cm.activeNode.attributes.pk
            }
            ,listeners: {
                'success': {fn:function(r) {
                    var pr = r.object;
                    pr.class_key = cls;
                    
                    var w = MODx.load({
                        xtype: 'modx-window-quick-update-modResource'
                        ,record: pr
                        ,listeners: {
                            'success':{fn:function() { 
                                this.refreshNode(this.cm.activeNode.id);
                            },scope:this}
                            ,'hide':{fn:function() {this.destroy();}}
                        }
                    });
                    w.setValues(r.object);
                    w.show(e.target,function() {
                        Ext.isSafari ? w.setPosition(null,30) : w.center();
                    },this);
                },scope:this}
            }
        });
    }

    ,_getModContextMenu: function(n) {
        var a = n.attributes;
        var ui = n.getUI();
        var m = [];

        m.push({
            text: '<b>'+a.text+'</b>'
            ,handler: function() {return false;}
            ,header: true
        });
        m.push('-');
        if (ui.hasClass('pedit')) {
            m.push({
                text: _('edit_context')
                ,handler: function() {
                    var at = this.cm.activeNode.attributes;
                    this.loadAction('a='+MODx.action['context/update']+'&key='+at.pk);
                }
            });
        }
        m.push({
            text: _('context_refresh')
            ,handler: function() {
                this.refreshNode(this.cm.activeNode.id,true);
            }
        });
        if (ui.hasClass('pnewdoc')) {
            m.push('-');
            this._getCreateMenus(m,'0',ui);
        }
        if (ui.hasClass('pnew')) {
            m.push({
                text: _('context_duplicate')
                ,handler: this.duplicateContext
            });
        }
        if (ui.hasClass('pdelete')) {
            m.push('-');
            m.push({
                text: _('context_remove')
                ,handler: this.removeContext
            });
        }
        return m;
    }

    ,_getModResourceMenu: function(n) {
        var a = n.attributes;
        var ui = n.getUI();
        var m = [];
        m.push({
            text: '<b>'+a.text+'</b>'
            ,handler: function() {return false;}
            ,header: true
        });
        m.push('-');
        if (ui.hasClass('pview')) {
            m.push({
                text: _('resource_overview')
                ,handler: function() {this.loadAction('a='+MODx.action['resource/data'])}
            });
        }
        if (ui.hasClass('pedit')) {
            m.push({
                text: _('resource_edit')
                ,handler: function() {this.loadAction('a='+MODx.action['resource/update']);}
            });
        }
        if (ui.hasClass('pqupdate')) {
            m.push({
                text: _('quick_update_resource')
                ,classKey: a.classKey
                ,handler: function(itm,e) {
                    Ext.getCmp("modx-resource-tree").quickUpdate(itm,e,itm.classKey);
                }
            });
        }
        if (ui.hasClass('pnew')) {
            m.push({
                text: _('resource_duplicate')
                ,handler: this.duplicateResource
            });
        }
        m.push({
            text: _('resource_refresh')
            ,handler: function() {
                this.refreshNode(this.cm.activeNode.id);
            }
            ,scope: this
        })

        if (ui.hasClass('pnew')) {
            m.push('-');
            this._getCreateMenus(m,null,ui);
        }

        if (ui.hasClass('psave')) {
            m.push('-');
            if (ui.hasClass('ppublish') && ui.hasClass('unpublished')) {
                m.push({
                    text: _('resource_publish')
                    ,handler: this.publishDocument
                });
            } else if (ui.hasClass('punpublish')) {
                m.push({
                    text: _('resource_unpublish')
                    ,handler: this.unpublishDocument
                });
            }
            if (ui.hasClass('pundelete') && ui.hasClass('deleted')) {
                m.push({
                    text: _('resource_undelete')
                    ,handler: this.undeleteDocument
                });
            } else if (ui.hasClass('pdelete') && !ui.hasClass('deleted')) {
                m.push({
                    text: _('resource_delete')
                    ,handler: this.deleteDocument
                });
            }
        }
        if (ui.hasClass('pview')) {
            m.push('-');
            m.push({
                text: _('resource_view')
                ,handler: this.preview
            });
        }
        return m;
    }

    ,_getCreateMenus: function(m,pk,ui) {
        var types = MODx.resourceTypes || {
            'document': 'modDocument'
            ,'weblink': 'modWebLink'
            ,'symlink': 'modSymLink'
            ,'static_resource': 'modStaticResource'
        };
        if (MODx.config.custom_resource_classes) {
            var crcs = MODx.config.custom_resource_classes;
            if (!Ext.isEmpty(crcs)) {
                for (var k in crcs) {
                    types[k] = crcs[k];
                }
            }
        }
        var o = this.fireEvent('loadCreateMenus',types);
        if (Ext.isObject(o)) {
            Ext.apply(types,o);
        }
        var ct = [];
        var qct = [];
        for (var k in types) {
            ct.push({
                text: _(k+'_create_here')
                ,classKey: types[k]
                ,usePk: pk ? pk : false
                ,handler: function(itm) {
                    var at = this.cm.activeNode.attributes;
                    var p = itm.usePk ? itm.usePk : at.pk;
                    Ext.getCmp('modx-resource-tree').loadAction(
                        'a='+MODx.action['resource/create']
                        + '&class_key='+itm.classKey
                        + '&parent='+p
                        + (at.ctx ? '&context_key='+at.ctx : '')
                    );
                }
                ,scope: this
            });
            if (ui && ui.hasClass('pqcreate')) {
                qct.push({
                    text: _(k)
                    ,classKey: types[k]
                    ,handler: function(itm,e) {
                        var at = this.cm.activeNode.attributes;
                        var p = itm.usePk ? itm.usePk : at.pk;
                        Ext.getCmp('modx-resource-tree').quickCreate(itm,e,itm.classKey,at.ctx,p);
                    }
                    ,scope: this
                });
            }
        }
        m.push({
            text: _('create')
            ,handler: function() {return false;}
            ,menu: {items: ct}
        });
        if (ui && ui.hasClass('pqcreate')) {
            m.push({
               text: _('quick_create')
               ,handler: function() {return false;}
               ,menu: {items: qct}
            });
        }
        return m;
    }
});
Ext.reg('modx-tree-resource',MODx.tree.Resource);



MODx.window.QuickCreateResource = function(config) {
    config = config || {};
    this.ident = config.ident || 'qcr'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_create_resource')
        ,id: this.ident
        ,width: 620
        ,url: MODx.config.connectors_url+'resource/index.php'
        ,action: 'create'
        ,shadow: false
        ,fields: [{
            xtype: 'modx-tabs'
            ,bodyStyle: { background: 'transparent' }
            ,deferredRender: false
            ,autoHeight: true
            ,items: [{
                title: _('resource')
                ,layout: 'form'
                ,cls: 'modx-panel'
                ,bodyStyle: { background: 'transparent', padding: '10px' }
                ,autoHeight: true
                ,labelWidth: 100
                ,items: [{
                    xtype: 'modx-combo-template'
                    ,name: 'template'
                    ,id: 'modx-'+this.ident+'-template'
                    ,fieldLabel: _('template')
                    ,editable: false
                    ,anchor: '100%'
                    ,baseParams: {
                        action: 'getList'
                        ,combo: '1'
                    }
                    ,value: MODx.config.default_template
                },{
                    xtype: 'textfield'
                    ,name: 'pagetitle'
                    ,id: 'modx-'+this.ident+'-pagetitle'
                    ,fieldLabel: _('pagetitle')
                    ,anchor: '100%'
                },{
                    xtype: 'textfield'
                    ,name: 'longtitle'
                    ,id: 'modx-'+this.ident+'-longtitle'
                    ,fieldLabel: _('long_title')
                    ,anchor: '100%'
                },{
                    xtype: 'textarea'
                    ,name: 'description'
                    ,id: 'modx-'+this.ident+'-description'
                    ,fieldLabel: _('description')
                    ,anchor: '100%'
                    ,grow: false
                    ,height: 50
                },{
                    xtype: 'textfield'
                    ,name: 'alias'
                    ,id: 'modx-'+this.ident+'-alias'
                    ,fieldLabel: _('alias')
                    ,anchor: '100%'
                },{
                    xtype: 'textarea'
                    ,name: 'introtext'
                    ,id: 'modx-'+this.ident+'-introtext'
                    ,fieldLabel: _('introtext')
                    ,anchor: '100%'
                    ,height: 50
                },{
                    xtype: 'textfield'
                    ,name: 'menutitle'
                    ,id: 'modx-'+this.ident+'-menutitle'
                    ,fieldLabel: _('resource_menutitle')
                    ,anchor: '100%'
                },
                MODx.getQRContentField(this.ident,config.record.class_key)]
            },{
                id: 'modx-'+this.ident+'-settings'
                ,title: _('settings')
                ,layout: 'form'
                ,cls: 'modx-panel'
                ,autoHeight: true
                ,forceLayout: true
                ,labelWidth: 100
                ,defaults: {autoHeight: true ,border: false}
                ,style: 'background: transparent;'
                ,bodyStyle: { background: 'transparent', padding: '10px' }
                ,items: MODx.getQRSettings(this.ident,config.record)
            }]
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
    });
    MODx.window.QuickCreateResource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreateResource,MODx.Window);
Ext.reg('modx-window-quick-create-modResource',MODx.window.QuickCreateResource);

MODx.window.QuickUpdateResource = function(config) {
    config = config || {};
    this.ident = config.ident || 'qur'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_update_resource')
        ,id: this.ident
        ,width: 620
        ,url: MODx.config.connectors_url+'resource/index.php'
        ,action: 'update'
        ,autoHeight: true
        ,shadow: false
        ,fields: [{
            xtype: 'modx-tabs'
            ,bodyStyle: { background: 'transparent' }
            ,autoHeight: true
            ,deferredRender: false
            ,items: [{
                title: _('resource')
                ,layout: 'form'
                ,cls: 'modx-panel'
                ,bodyStyle: { background: 'transparent', padding: '10px' }
                ,autoHeight: true
                ,labelWidth: 100
                ,items: [{
                    xtype: 'hidden'
                    ,name: 'id'
                    ,id: 'modx-'+this.ident+'-id'
                },{
                    xtype: 'modx-combo-template'
                    ,name: 'template'
                    ,id: 'modx-'+this.ident+'-template'
                    ,fieldLabel: _('template')
                    ,editable: false
                    ,anchor: '100%'
                    ,baseParams: {
                        action: 'getList'
                        ,combo: '1'
                    }
                },{
                    xtype: 'textfield'
                    ,name: 'pagetitle'
                    ,id: 'modx-'+this.ident+'-pagetitle'
                    ,fieldLabel: _('pagetitle')
                    ,anchor: '100%'
                },{
                    xtype: 'textfield'
                    ,name: 'longtitle'
                    ,id: 'modx-'+this.ident+'-longtitle'
                    ,fieldLabel: _('long_title')
                    ,anchor: '100%'
                },{
                    xtype: 'textarea'
                    ,name: 'description'
                    ,id: 'modx-'+this.ident+'-description'
                    ,fieldLabel: _('description')
                    ,anchor: '100%'
                    ,grow: false
                    ,height: 50
                },{
                    xtype: 'textfield'
                    ,name: 'alias'
                    ,id: 'modx-'+this.ident+'-alias'
                    ,fieldLabel: _('alias')
                    ,anchor: '100%'
                },{
                    xtype: 'textfield'
                    ,name: 'menutitle'
                    ,id: 'modx-'+this.ident+'-menutitle'
                    ,fieldLabel: _('resource_menutitle')
                    ,anchor: '100%'
                },{
                    xtype: 'textarea'
                    ,name: 'introtext'
                    ,id: 'modx-'+this.ident+'-introtext'
                    ,fieldLabel: _('introtext')
                    ,anchor: '100%'
                    ,height: 50
                },
                MODx.getQRContentField(this.ident,config.record.class_key)]
            },{
                id: 'modx-'+this.ident+'-settings'
                ,title: _('settings'),layout: 'form'
                ,cls: 'modx-panel'
                ,autoHeight: true
                ,forceLayout: true
                ,labelWidth: 100
                ,defaults: {autoHeight: true ,border: false}
                ,style: 'background: transparent;'
                ,bodyStyle: { background: 'transparent', padding: '10px' }
                ,items: MODx.getQRSettings(this.ident,config.record)
            }]
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: config.saveBtnText || _('save')
            ,scope: this
            ,handler: function() { this.submit(false); }
        },{
            text: config.saveBtnText || _('save_and_close')
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.window.QuickUpdateResource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateResource,MODx.Window);
Ext.reg('modx-window-quick-update-modResource',MODx.window.QuickUpdateResource);


MODx.getQRContentField = function(id,cls) {
    id = id || 'qur';
    cls = cls || 'modResource';    
    var o = {};
    switch (cls) {
        case 'modSymLink':
            o = {
                xtype: 'textfield'
                ,fieldLabel: _('symlink')
                ,name: 'content'
                ,id: 'modx-'+id+'-content'
                ,anchor: '100%'
                ,maxLength: 255
                ,allowBlank: false
            };
            break;
        case 'modWebLink':
            o = {
                xtype: 'textfield'
                ,fieldLabel: _('weblink')
                ,name: 'content'
                ,id: 'modx-'+id+'-content'
                ,anchor: '100%'
                ,maxLength: 255
                ,value: 'http://'
                ,allowBlank: false
            };
            break;
        case 'modStaticResource':
            o = {
                xtype: 'modx-combo-browser'
                ,browserEl: 'modx-browser'
                ,prependPath: false
                ,prependUrl: false
                ,hideFiles: true
                ,fieldLabel: _('static_resource')
                ,name: 'content'
                ,id: 'modx-'+id+'-content'
                ,anchor: '100%'
                ,maxLength: 255
                ,value: ''
                ,listeners: {
                    'select':{fn:function(data) {
                        if (data.url.substring(0,1) == '/') {
                            Ext.getCmp('modx-'+id+'-content').setValue(data.url.substring(1));
                        }   
                    },scope:this}
                }
            };
            break;
        case 'modResource':
        default:
            o = {
                xtype: 'textarea'
                ,name: 'content'
                ,id: 'modx-'+id+'-content'
                ,hideLabel: true
                ,labelSeparator: ''
                ,anchor: '100%'
                ,height: 300
            };
            break;
    }
    return o;
};

MODx.getQRSettings = function(id,va) {
    id = id || 'qur';
    return [{
        xtype: 'hidden'
        ,name: 'parent'
        ,id: 'modx-'+id+'-parent'
        ,value: va['parent']
    },{
        xtype: 'hidden'
        ,name: 'context_key'
        ,id: 'modx-'+id+'-context_key'
        ,value: va['context_key']
    },{
        xtype: 'hidden'
        ,name: 'class_key'
        ,id: 'modx-'+id+'-class_key'
        ,value: va['class_key']
    },{
        xtype: 'hidden'
        ,name: 'publishedon'
        ,id: 'modx-'+id+'-publishedon'
        ,value: va['publishedon']
    },{
        xtype: 'xcheckbox'
        ,name: 'published'
        ,id: 'modx-'+id+'-published'
        ,fieldLabel: _('resource_published')
        ,description: _('resource_published_help')
        ,inputValue: 1
        ,checked: va['published'] !== undefined ? va['published'] : (MODx.config.publish_default == '1' ? 1 : 0)
    },{
        xtype: 'xcheckbox'
        ,fieldLabel: _('resource_folder')
        ,description: _('resource_folder_help')
        ,name: 'isfolder'
        ,id: 'modx-'+id+'-isfolder'
        ,inputValue: 1
        ,checked: va['isfolder'] != undefined ? va['isfolder'] : false
    },{
        xtype: 'xcheckbox'
        ,fieldLabel: _('resource_richtext')
        ,description: _('resource_richtext_help')
        ,name: 'richtext'
        ,id: 'modx-'+id+'-richtext'
        ,inputValue: 1
        ,checked: va['richtext'] !== undefined ? (va['richtext'] ? 1 : 0) : (MODx.config.richtext_default == '1' ? 1 : 0)
    },{
        xtype: 'xcheckbox'
        ,fieldLabel: _('resource_searchable')
        ,description: _('resource_searchable_help')
        ,name: 'searchable'
        ,id: 'modx-'+id+'-searchable'
        ,inputValue: 1
        ,checked: va['searchable'] != undefined ? va['searchable'] : (MODx.config.search_default == '1' ? 1 : 0)
        ,listeners: {'check': {fn:MODx.handleQUCB}}
    },{
        xtype: 'xcheckbox'
        ,fieldLabel: _('resource_hide_from_menus')
        ,description: _('resource_hide_from_menus_help')
        ,name: 'hidemenu'
        ,id: 'modx-'+id+'-hidemenu'
        ,inputValue: 1
        ,checked: va['hidemenu'] != undefined ? va['hidemenu'] : (MODx.config.hidemenu_default == '1' ? 1 : 0)
    },{
        xtype: 'xcheckbox'
        ,fieldLabel: _('resource_cacheable')
        ,description: _('resource_cacheable_help')
        ,name: 'cacheable'
        ,id: 'modx-'+id+'-cacheable'
        ,inputValue: 1
        ,checked: va['cacheable'] != undefined ? va['cacheable'] : (MODx.config.cache_default == '1' ? 1 : 0)
    },{
        xtype: 'xcheckbox'
        ,name: 'clearCache'
        ,id: 'modx-'+id+'-clearcache'
        ,fieldLabel: _('clear_cache_on_save')
        ,description: _('clear_cache_on_save_msg')
        ,inputValue: 1
        ,checked: true
    }];
};
MODx.handleQUCB = function(cb) {
    var h = Ext.getCmp(cb.id+'-hd');
    if (cb.checked && h) {
        cb.setValue(1);
        h.setValue(1);
    } else if (h) {
        cb.setValue(0);
        h.setValue(0);
    }
}
/**
 * Generates the Element Tree
 * 
 * @class MODx.tree.Element
 * @extends MODx.tree.Tree
 * @param {Object} config An object of options.
 * @xtype modx-tree-element
 */
MODx.tree.Element = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        rootVisible: false
        ,enableDD: !Ext.isEmpty(MODx.config.enable_dragdrop) ? true : false
        ,ddGroup: 'modx-treedrop-dd'
        ,title: ''
        ,url: MODx.config.connectors_url+'element/index.php'
        ,useDefaultToolbar: true
        ,tbar: [{
            icon: MODx.config.template_url+'images/restyle/icons/template.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('new')+' '+_('template')}
            ,handler: function() {
                this.redirect('index.php?a='+MODx.action['element/template/create']);
            }
            ,scope: this
            ,hidden: MODx.perm.new_template ? false : true
        },{
            icon: MODx.config.template_url+'images/restyle/icons/tv.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('new')+' '+_('tv')}
            ,handler: function() {
                this.redirect('index.php?a='+MODx.action['element/tv/create']);
            }
            ,scope: this
            ,hidden: MODx.perm.new_tv ? false : true
        },{
            icon: MODx.config.template_url+'images/restyle/icons/chunk.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('new')+' '+_('chunk')}
            ,handler: function() {
                this.redirect('index.php?a='+MODx.action['element/chunk/create']);
            }
            ,scope: this
            ,hidden: MODx.perm.new_chunk ? false : true
        },{
            icon: MODx.config.template_url+'images/restyle/icons/snippet.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('new')+' '+_('snippet')}
            ,handler: function() {
                this.redirect('index.php?a='+MODx.action['element/snippet/create']);
            }
            ,scope: this
            ,hidden: MODx.perm.new_snippet ? false : true
        },{
            icon: MODx.config.template_url+'images/restyle/icons/plugin.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('new')+' '+_('plugin')}
            ,handler: function() {
                this.redirect('index.php?a='+MODx.action['element/plugin/create']);
            }
            ,scope: this
            ,hidden: MODx.perm.new_plugin ? false : true
        }]
    });
    MODx.tree.Element.superclass.constructor.call(this,config);
    this.on('afterSort',this.afterSort);
};
Ext.extend(MODx.tree.Element,MODx.tree.Tree,{
    forms: {}
    ,windows: {}
    ,stores: {}

    ,createCategory: function(n,e) {
        var r = {};
        if (this.cm.activeNode.attributes.data) {
            r['parent'] = this.cm.activeNode.attributes.data.id;
        }

        if (!this.windows.createCategory) {
            this.windows.createCategory = MODx.load({
                xtype: 'modx-window-category-create'
                ,record: r
                ,listeners: {
                     'success': {fn:function() {
                        this.refreshNode(this.cm.activeNode.id,true);
                    },scope:this}
                }
            });
        }
        this.windows.createCategory.reset();
        this.windows.createCategory.setValues(r);
        this.windows.createCategory.show(e.target);
    }

    ,renameCategory: function(itm,e) {
        var r = this.cm.activeNode.attributes.data;
        if (!this.windows.renameCategory) {
            this.windows.renameCategory = MODx.load({
                xtype: 'modx-window-category-rename'
                ,record: r
                ,listeners: {
                    'success':{fn:function(r) {
                        var c = r.a.result.object;
                        var n = this.cm.activeNode;
                        n.setText(c.category+' ('+c.id+')');
                        Ext.get(n.getUI().getEl()).frame();
                        n.attributes.data.id = c.id;
                        n.attributes.data.category = c.category;
                    },scope:this}
                }
            });
        }
        this.windows.renameCategory.setValues(r);
        this.windows.renameCategory.show(e.target);
    }
		
    ,removeCategory: function(itm,e) {
        var id = this.cm.activeNode.attributes.data.id;
        MODx.msg.confirm({
            title: _('warning')
            ,text: _('category_confirm_delete')
            ,url: MODx.config.connectors_url+'element/category.php'
            ,params: {
                action: 'remove'
                ,id: id
            }
            ,listeners: {
                'success': {fn:function() {
                    this.cm.activeNode.remove();
                },scope:this}
            }
        });
    }
	    
    ,duplicateElement: function(itm,e,id,type) {
        var r = {
            id: id
            ,type: type
            ,name: _('duplicate_of',{name: this.cm.activeNode.attributes.name})
        };
        if (!this.windows.duplicateElement) {
            this.windows.duplicateElement = MODx.load({
                xtype: 'modx-window-element-duplicate'
                ,record: r
                ,listeners: {
                    'success': {fn:function() {this.refreshNode(this.cm.activeNode.id);},scope:this}
                }
            });
        } else {
            var u = MODx.config.connectors_url+'element/'+type+'.php';
            this.windows.duplicateElement.fp.getForm().url = u;
            var dv = this.windows.duplicateElement.fp.getForm().findField('duplicateValues');
            if (dv) {
                if (type != 'tv') {
                    dv.hide();
                    var d = dv.getEl().up('.x-form-item');
                    if (d) { d.setDisplayed(false); }
                } else {
                    dv.show();
                    var d = dv.getEl().up('.x-form-item');
                    if (d) { d.setDisplayed(true); }
                }
            }
        }
        this.windows.duplicateElement.setValues(r);
        this.windows.duplicateElement.show(e.target);
    }
	
    ,removeElement: function(itm,e) {
        var id = this.cm.activeNode.id.substr(2);
        var oar = id.split('_');
        MODx.msg.confirm({
            title: _('warning')
            ,text: _('remove_this_confirm',{
                type: oar[0]
                ,name: this.cm.activeNode.attributes.name
            })
            ,url: MODx.config.connectors_url+'element/'+oar[0]+'.php'
            ,params: {
                action: 'remove'
                ,id: oar[2]
            }
            ,listeners: {
                'success': {fn:function() {
                    this.cm.activeNode.remove();
                },scope:this}
            }
        });
    }

    ,quickCreate: function(itm,e,type) {
        var r = {
            category: this.cm.activeNode.attributes.pk || ''
        };
        var w = MODx.load({
            xtype: 'modx-window-quick-create-'+type
            ,record: r
            ,listeners: {
                'success':{fn:function() {this.refreshNode(this.cm.activeNode.id);},scope:this}
            }
        });
        w.setValues(r);
        w.show(e.target);
        w.on('hide',function() {delete w;},this);
    }
    
    ,quickUpdate: function(itm,e,type) {
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'element/'+type+'.php'
            ,params: {
                action: 'get'
                ,id: this.cm.activeNode.attributes.pk
            }
            ,listeners: {
                'success': {fn:function(r) {
                    var w = MODx.load({
                        xtype: 'modx-window-quick-update-'+type
                        ,record: r.object
                        ,listeners: {
                            'success':{fn:function(r) {
                                this.refreshNode(this.cm.activeNode.id);
                            },scope:this}
                        }
                    });
                    w.setValues(r.object);
                    w.show(e.target);
                },scope:this}
            }
        });
    }
	
    ,_createElement: function(itm,e,type) {
        var id = this.cm.activeNode.id.substr(2);
        var oar = id.split('_');
        var type = oar[0] == 'type' ? oar[1] : oar[0];
        var cat_id = oar[0] == 'type' ? 0 : (oar[1] == 'category' ? oar[2] : oar[3]);
        var a = MODx.action['element/'+type+'/create'];
        this.redirect('index.php?a='+a+'&category='+cat_id);
        this.cm.hide();
        return false;
    }
    
    ,afterSort: function(o) {
        var tn = o.event.target.attributes;
        if (tn.type == 'category') {
            var dn = o.event.dropNode.attributes;
            if (tn.id != 'n_category' && dn.type == 'category') {
                o.event.target.expand();
            } else {
                this.refreshNode(o.event.target.attributes.id,true);
                this.refreshNode('n_type_'+o.event.dropNode.attributes.type,true);
            }
        }
    }
		
    ,_handleDrop: function(e) {
        var target = e.target;
        if (e.point == 'above' || e.point == 'below') {return false;}
        if (target.attributes.classKey != 'modCategory' && target.attributes.classKey != 'root') { return false; }

        if (!this.isCorrectType(e.dropNode,target)) {return false;}
        if (target.attributes.type == 'category' && e.point == 'append') {return true;}

        return target.getDepth() > 0;
    }
    
    ,isCorrectType: function(dropNode,targetNode) {
        var r = false;
        /* types must be the same */
        if(targetNode.attributes.type == dropNode.attributes.type) {
            /* do not allow anything to be dropped on an element */
            if(!(targetNode.parentNode &&
                ((dropNode.attributes.cls == 'folder'
                    && targetNode.attributes.cls == 'folder'
                    && dropNode.parentNode.id == targetNode.parentNode.id
                ) || targetNode.attributes.cls == 'file'))) {
                r = true;
            }
        }
        return r;
    }


    /**
     * Shows the current context menu.
     * @param {Ext.tree.TreeNode} n The current node
     * @param {Ext.EventObject} e The event object run.
     */
    ,_showContextMenu: function(n,e) {
        n.select();
        this.cm.activeNode = n;
        this.cm.removeAll();
        if (n.attributes.menu && n.attributes.menu.items) {
            this.addContextMenuItem(n.attributes.menu.items);
            this.cm.show(n.getUI().getEl(),'t?');
        } else {
            var m = [];
            switch (n.attributes.classKey) {
                case 'root':
                    m = this._getRootMenu(n);
                    break;
                case 'modCategory':
                    m = this._getCategoryMenu(n);
                    break;
                default:
                    m = this._getElementMenu(n);
                    break;
            }

            this.addContextMenuItem(m);
            this.cm.showAt(e.xy);
        }
        e.stopEvent();
    }

    ,_getQuickCreateMenu: function(n,m) {
        var ui = n.getUI();
        var mn = [];
        var types = ['template','tv','chunk','snippet','plugin'];
        var t;
        for (var i=0;i<types.length;i++) {
            t = types[i];
            if (ui.hasClass('pnew_'+t)) {
                mn.push({
                    text: _(t)
                    ,scope: this
                    ,type: t
                    ,handler: function(itm,e) {
                        this.quickCreate(itm,e,itm.type);
                    }
                });
            }
        }
        m.push({
            text: _('quick_create')
            ,handler: function() {return false;}
            ,menu: {
                items: mn
            }
        });
        return m;
    }

    ,_getElementMenu: function(n) {
        var a = n.attributes;
        var ui = n.getUI();
        var m = [];
        
        m.push({
            text: '<b>'+a.text+'</b>'
            ,handler: function() { return false; }
            ,header: true
        });
        m.push('-');
        
        if (ui.hasClass('pedit')) {
            m.push({
                text: _('edit')+' '+a.elementType
                ,type: a.type
                ,pk: a.pk
                ,handler: function(itm,e) {
                    location.href = 'index.php?a='
                        + MODx.action['element/'+itm.type+'/update']
                        + '&id='+itm.pk;
                }
            });
            m.push({
                text: _('quick_update_'+a.type)
                ,type: a.type
                ,handler: function(itm,e) {
                    this.quickUpdate(itm,e,itm.type);
                }
            });
        }
        if (ui.hasClass('pnew')) {
            m.push({
                text: _('duplicate')+' '+a.elementType
                ,pk: a.pk
                ,type: a.type
                ,handler: function(itm,e) {
                    this.duplicateElement(itm,e,itm.pk,itm.type);
                }
            });
        }
        if (ui.hasClass('pdelete')) {
            m.push({
                text: _('remove')+' '+a.elementType
                ,handler: this.removeElement
            });
        }
        m.push('-');
        if (ui.hasClass('pnew')) {
            m.push({
                text: _('add_to_category_this',{type:a.elementType})
                ,handler: this._createElement
            });
        }
        if (ui.hasClass('pnewcat')) {
            m.push({
                text: _('new_category')
                ,handler: this.createCategory
            });
        }
        return m;
    }

    ,_getCategoryMenu: function(n) {
        var a = n.attributes;
        var ui = n.getUI();
        var m = [];

        m.push({
            text: '<b>'+a.text+'</b>'
            ,handler: function() { return false; }
            ,header: true
        });
        m.push('-');
        if (ui.hasClass('pnewcat')) {
            m.push({
                text: _('category_create')
                ,handler: this.createCategory
            });
        }
        if (ui.hasClass('peditcat')) {
            m.push({
                text: _('category_rename')
                ,handler: this.renameCategory
            });
        }
        if (m.length > 2) {m.push('-');}

        if (a.elementType) {
            m.push({
                text: _('add_to_category_this',{type: Ext.util.Format.capitalize(a.type)})
                ,handler: this._createElement
            });
        }
        this._getQuickCreateMenu(n,m);

        if (ui.hasClass('pdelcat')) {
            m.push('-');
            m.push({
                text: _('category_remove')
                ,handler: this.removeCategory
            });
        }
        return m;
    }
    
    ,_getRootMenu: function(n) {
        var a = n.attributes;
        var ui = n.getUI();
        var m = [];

        if (ui.hasClass('pnew')) {
            m.push({
                text: _('new'+'_'+a.type)
                ,handler: this._createElement
            });
            m.push({
                text: _('quick_create_'+a.type)
                ,type: a.type
                ,handler: function(itm,e) {
                    this.quickCreate(itm,e,itm.type);
                }
            });
        }

        if (ui.hasClass('pnewcat')) {
            if (ui.hasClass('pnew')) {m.push('-');}
            m.push({
                text: _('new_category')
                ,handler: this.createCategory
            });
        }
        
        return m;
    }
});
Ext.reg('modx-tree-element',MODx.tree.Element);


/** 
 * Generates the Duplicate Element window
 * 
 * @class MODx.window.DuplicateElement
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-element-duplicate
 */
MODx.window.DuplicateElement = function(config) {
    config = config || {};
    this.ident = config.ident || 'dupeel-'+Ext.id();
    var flds = [{
        xtype: 'hidden'
        ,name: 'id'
        ,id: 'modx-'+this.ident+'-id'
    },{
        xtype: 'textfield'
        ,fieldLabel: _('element_name_new')
        ,name: 'name'
        ,id: 'modx-'+this.ident+'-name'
        ,anchor: '90%'
    }];
    if (config.record.type == 'tv') {
        flds.push({
            xtype: 'xcheckbox'
            ,fieldLabel: _('element_duplicate_values')
            ,labelSeparator: ''
            ,name: 'duplicateValues'
            ,id: 'modx-'+this.ident+'-duplicate-values'
            ,anchor: '95%'
            ,inputValue: 1
            ,checked: false
        });
    }
    Ext.applyIf(config,{
        title: _('element_duplicate')
        ,url: MODx.config.connectors_url+'element/'+config.record.type+'.php'
        ,action: 'duplicate'
        ,fields: flds
        ,labelWidth: 150
    });
    MODx.window.DuplicateElement.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.DuplicateElement,MODx.Window);
Ext.reg('modx-window-element-duplicate',MODx.window.DuplicateElement);



/** 
 * Generates the Rename Category window.
 *  
 * @class MODx.window.RenameCategory
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-category-rename
 */
MODx.window.RenameCategory = function(config) {
    config = config || {};
    this.ident = config.ident || 'rencat-'+Ext.id();
    Ext.applyIf(config,{
        title: _('category_rename')
        ,height: 150
        ,width: 350
        ,url: MODx.config.connectors_url+'element/category.php'
        ,action: 'update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-'+this.ident+'-id'
            ,value: config.record.id
        },{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'category'
            ,id: 'modx-'+this.ident+'-category'
            ,width: 150
            ,value: config.record.category
            ,anchor: '90%'
        }]
    });
    MODx.window.RenameCategory.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.RenameCategory,MODx.Window);
Ext.reg('modx-window-category-rename',MODx.window.RenameCategory);/**
 * Generates the Directory Tree
 * 
 * @class MODx.tree.Directory
 * @extends MODx.tree.Tree
 * @param {Object} config An object of options.
 * @xtype modx-tree-directory
 */
MODx.tree.Directory = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        rootVisible: true
        ,rootName: _('files')
        ,rootId: '/'
        ,title: _('files')
        ,ddAppendOnly: true
        ,enableDrag: true
        ,enableDrop: true
        ,ddGroup: 'modx-treedrop-dd'
        ,url: MODx.config.connectors_url+'browser/directory.php'
        ,baseParams: {
            prependPath: config.prependPath || null
            ,basePath: config.basePath || ''
            ,basePathRelative: config.basePathRelative || null
            ,baseUrl: config.baseUrl || ''
            ,baseUrlRelative: config.baseUrlRelative || null
            ,hideFiles: config.hideFiles || false
            ,wctx: MODx.ctx || 'web'
        }
        ,action: 'getList'
        ,primaryKey: 'dir'
        ,useDefaultToolbar: true
        ,tbar: [{
            icon: MODx.config.template_url+'images/restyle/icons/folder.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('file_folder_create')}
            ,handler: this.createDirectory
            ,scope: this
            ,hidden: MODx.perm.directory_create ? false : true
        },{
            icon: MODx.config.template_url+'images/restyle/icons/file_upload.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('upload_files')}
            ,handler: this.uploadFiles
            ,scope: this
            ,hidden: MODx.perm.file_upload ? false : true
        },'->',{
            icon: MODx.config.template_url+'images/restyle/icons/file_manager.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('modx_browser')}
            ,handler: this.loadFileManager
            ,scope: this
            ,hidden: MODx.perm.file_manager && !MODx.browserOpen ? false : true
        }]
    });
    MODx.tree.Directory.superclass.constructor.call(this,config);
    this.addEvents({
        'beforeUpload': true
        ,'afterUpload': true
        ,'fileBrowserSelect': true
    });
    this.on('click',function(n,e) {
        n.select();
        this.cm.activeNode = n;
    },this);
};
Ext.extend(MODx.tree.Directory,MODx.tree.Tree,{
    windows: {}
    ,_initExpand: function() {
        if (!Ext.isEmpty(this.config.openTo)) {
            var treeState = Ext.state.Manager.get(this.treestate_id);
            this.selectPath('/'+_('files')+'/'+this.config.openTo,'text');
        } else {
            var treeState = Ext.state.Manager.get(this.treestate_id);
            this.selectPath(treeState,'text');
        }
    }
    ,_saveState: function(n) {
        var p = n.getPath('text');
        Ext.state.Manager.set(this.treestate_id,p);
    }
    ,_handleDrop: function(e) { return false; }
    ,_showContextMenu: function(n,e) {
        n.select();
        this.cm.activeNode = n;
        this.cm.removeAll();
        if (n.attributes.menu && n.attributes.menu.items) {
            this.addContextMenuItem(n.attributes.menu.items);
            this.cm.show(n.getUI().getEl(),'t?');
        } else {
            var m = [];
            switch (n.attributes.type) {
                case 'dir':
                    m = this._getDirectoryMenu(n);
                    break;
                default:
                    m = this._getFileMenu(n);
                    break;
            }
            if (m.length > 0) {
                this.addContextMenuItem(m);
                this.cm.showAt(e.xy);
            }
        }
        e.stopEvent();
    }

    ,_getFileMenu: function(n) {
        var a = n.attributes;
        var ui = n.getUI();
        var m = [];

        if (ui.hasClass('pupdate')) {
            if (a.page) {
                m.push({
                    text: _('file_edit')
                    ,file: a.file
                    ,handler: function(itm,e) {
                        this.loadAction('a='+MODx.action['system/file/edit']+'&file='+itm.file);
                    }
                });
            }
            m.push({
                text: _('rename')
                ,handler: this.renameFile
            });
        }
        if (ui.hasClass('premove')) {
            if (m.length > 0) { m.push('-'); }
            m.push({
                text: _('file_remove')
                ,handler: this.removeFile
            });
        }
        return m;
    }

    ,_getDirectoryMenu: function(n) {
        var ui = n.getUI();
        var m = [];
        if (ui.hasClass('pcreate')) {
            m.push({
                text: _('file_folder_create_here')
                ,handler: this.createDirectory
            });
        }
        if (ui.hasClass('pchmod')) {
            m.push({
                text: _('file_folder_chmod')
                ,handler: this.chmodDirectory
            });
        }
        if (ui.hasClass('pupdate')) {
            m.push({
                text: _('rename')
                ,handler: this.renameFile
            });
        }
        m.push({
            text: _('directory_refresh')
            ,handler: this.refreshActiveNode
        });
        if (ui.hasClass('pupload')) {
            m.push('-');
            m.push({
                text: _('upload_files')
                ,handler: this.uploadFiles
            });
        }
        if (ui.hasClass('premove')) {
            m.push('-');
            m.push({
                text: _('file_folder_remove')
                ,handler: this.removeDirectory
            });
        }
        return m;
    }

    
    ,getPath:function(node) {
        var path, p, a;

        // get path for non-root node
        if(node !== this.root) {
            p = node.parentNode;
            a = [node.text];
            while(p && p !== this.root) {
                a.unshift(p.text);
                p = p.parentNode;
            }
            a.unshift(this.root.attributes.path || '');
            path = a.join(this.pathSeparator);
        }

        // path for root node is it's path attribute
        else {
            path = node.attributes.path || '';
        }

        // a little bit of security: strip leading / or .
        // full path security checking has to be implemented on server
        path = path.replace(/^[\/\.]*/, '');
        return path+'/';
    }

    ,browser: null
    ,loadFileManager: function(btn,e) {
        if (this.browser === null) {
            this.browser = MODx.load({
                xtype: 'modx-browser'
                ,hideFiles: true
                ,rootVisible: false
                ,wctx: MODx.ctx
                ,listeners: {
                    'select': {fn: function(data) {
                        this.fireEvent('fileBrowserSelect',data);
                    },scope:this}
                }
            });
        }
        if (this.browser) {
            this.browser.show();
        }
    }
    
    ,renameNode: function(field,nv,ov) {
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'browser/index.php'
            ,params: {
                action: 'rename'
                ,new_name: nv
                ,old_name: ov
                ,prependPath: this.config.prependPath || null
                ,file: this.treeEditor.editNode.id
                ,wctx: MODx.ctx || ''
            }
            ,listeners: {
               'success': {fn:this.refreshActiveNode,scope:this}
            }
        });
    }
	
    ,renameFile: function(item,e) {
        var node = this.cm.activeNode;
        var r = {
            oldname: node.text
            ,newname: node.text
            ,path: node.id
        };
        if (!this.windows.rename) {
            this.windows.rename = MODx.load({
                xtype: 'modx-window-file-rename'
                ,record: r
                ,listeners: {
                    'success':{fn:this.refreshParentNode,scope:this}
                }
            });
        }
        this.windows.rename.setValues(r);
        this.windows.rename.show(e.target);
    }
    
    ,createDirectory: function(item,e) {
        var node = this.cm && this.cm.activeNode ? this.cm.activeNode : false;
        var r = {parent: node && node.attributes.type == 'dir' ? node.id : '/'};
        if (!this.windows.create) {
            this.windows.create = MODx.load({
                xtype: 'modx-window-directory-create'
                ,record: r
                ,prependPath: this.config.prependPath || null
                ,listeners: {
                    'success':{fn:this.refreshActiveNode,scope:this}
                }
            });
        }
        this.windows.create.setValues(r);
        this.windows.create.show(e ? e.target : Ext.getBody());
    }
	
    ,chmodDirectory: function(item,e) {
        var node = this.cm.activeNode;
        var r = {dir: node.id,mode: node.attributes.perms};
        if (!this.windows.chmod) {
            this.windows.chmod = MODx.load({
                xtype: 'modx-window-directory-chmod'
                ,record: r
                ,prependPath: this.config.prependPath || null
                ,listeners: {
                    'success':{fn:this.refreshActiveNode,scope:this}
                }
            });
        }
        this.windows.chmod.setValues(r);
        this.windows.chmod.show(e.target);
    }

    ,removeDirectory: function(item,e) {
        var node = this.cm.activeNode;
        MODx.msg.confirm({
            text: _('file_folder_remove_confirm')
            ,url: MODx.config.connectors_url+'browser/directory.php'
            ,params: {
                action: 'remove'
                ,dir: node.id
                ,prependPath: this.config.prependPath || null
                ,wctx: MODx.ctx || ''
            }
            ,listeners: {
                'success':{fn:this.refreshParentNode,scope:this}
            }
        });
    }

    ,removeFile: function(item,e) {
        var node = this.cm.activeNode;
        MODx.msg.confirm({
            text: _('file_confirm_remove')
            ,url: MODx.config.connectors_url+'browser/file.php'
            ,params: {
                action: 'remove'
                ,file: node.id
                ,prependPath: this.config.prependPath || null
                ,wctx: MODx.ctx || ''
            }
            ,listeners: {
                'success':{fn:this.refreshParentNode,scope:this}
            }
        });
    }
    
    ,uploadFiles: function(btn,e) {
        if (!this.uploader) {
            this.uploader = new Ext.ux.UploadDialog.Dialog({
                url: MODx.config.connectors_url+'browser/file.php'
                ,base_params: {
                    action: 'upload'
                    ,prependPath: this.config.prependPath || null
                    ,prependUrl: this.config.prependUrl || null
                    ,basePath: this.config.basePath || ''
                    ,basePathRelative: this.config.basePathRelative || null
                    ,baseUrl: this.config.baseUrl || ''
                    ,baseUrlRelative: this.config.baseUrlRelative || null
                    ,wctx: MODx.ctx || ''
                }
                ,reset_on_hide: true
                ,width: 550
                ,cls: 'ext-ux-uploaddialog-dialog modx-upload-window'
            });
            this.uploader.on('show',this.beforeUpload,this);
            this.uploader.on('uploadsuccess',this.uploadSuccess,this);
            this.uploader.on('uploaderror',this.uploadError,this);
            this.uploader.on('uploadfailed',this.uploadFailed,this);
        }
        this.uploader.show(btn);
    }
    ,uploadError: function(dlg,file,data,rec) {}
    ,uploadFailed: function(dlg,file,rec) {}
    
    ,uploadSuccess:function() {
        if (this.cm.activeNode) {
            var node = this.cm.activeNode;
            if (node.isLeaf) {
                var pn = (node.isLeaf() ? node.parentNode : node);
                if (pn) {
                    pn.reload();
                } else {
                    this.refreshActiveNode();
                }
                this.fireEvent('afterUpload',node);
            } else {
                this.refreshActiveNode();
            }
        } else {
            this.refresh();
        }
    }    
    ,beforeUpload: function() {
        var path;
        if (this.cm.activeNode) {
            path = this.getPath(this.cm.activeNode);
            if(this.cm.activeNode.isLeaf()) {
                path = this.getPath(this.cm.activeNode.parentNode);
            }
        } else { path = '/'; }

        this.uploader.setBaseParams({
            action: 'upload'
            ,prependPath: this.config.prependPath || null
            ,prependUrl: this.config.prependUrl || null
            ,basePath: this.config.basePath || ''
            ,basePathRelative: this.config.basePathRelative || null
            ,baseUrl: this.config.baseUrl || ''
            ,baseUrlRelative: this.config.baseUrlRelative || null
            ,path: path
            ,wctx: MODx.ctx || ''
        });
        this.fireEvent('beforeUpload',this.cm.activeNode);
    }


    
});
Ext.reg('modx-tree-directory',MODx.tree.Directory);

/** 
 * Generates the Create Directory window
 * 
 * @class MODx.window.CreateDirectory
 * @extends MODx.Window
 * @param {Object} config An object of configuration options.
 * @xtype modx-window-directory-create
 */
MODx.window.CreateDirectory = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        width: 430
        ,height: 200
        ,title: _('file_folder_create')
        ,url: MODx.config.connectors_url+'browser/directory.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'wctx'
            ,value: MODx.ctx || ''
        },{
            xtype: 'hidden'
            ,name: 'prependPath'
            ,value: config.prependPath || null
        },{
            fieldLabel: _('name')
            ,name: 'name'
            ,xtype: 'textfield'
            ,anchor: '90%'
            ,allowBlank: false
        },{
            fieldLabel: _('file_folder_parent')
            ,name: 'parent'
            ,xtype: 'textfield'
            ,anchor: '95%'
        }]
    });
    MODx.window.CreateDirectory.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateDirectory,MODx.Window);
Ext.reg('modx-window-directory-create',MODx.window.CreateDirectory);

/** 
 * Generates the Chmod Directory window
 * 
 * @class MODx.window.ChmodDirectory
 * @extends MODx.Window
 * @param {Object} config An object of configuration options.
 * @xtype modx-window-directory-chmod
 */
MODx.window.ChmodDirectory = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('file_folder_chmod')
        ,width: 430
        ,height: 200
        ,url: MODx.config.connectors_url+'browser/directory.php'
        ,action: 'chmod'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'wctx'
            ,value: MODx.ctx || ''
        },{
            xtype: 'hidden'
            ,name: 'prependPath'
            ,value: config.prependPath || null
        },{
            fieldLabel: _('mode')
            ,name: 'mode'
            ,xtype: 'textfield'
            ,anchor: '90%'
            ,allowBlank: false
        },{
            name: 'dir'
            ,xtype: 'hidden'
        }]
    });
    MODx.window.ChmodDirectory.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.ChmodDirectory,MODx.Window);
Ext.reg('modx-window-directory-chmod',MODx.window.ChmodDirectory);


MODx.window.RenameFile = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('rename')
        ,width: 430
        ,height: 200
        ,url: MODx.config.connectors_url+'browser/index.php'
        ,action: 'rename'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'wctx'
            ,value: MODx.ctx || ''
        },{
            xtype: 'hidden'
            ,name: 'prependPath'
            ,value: config.prependPath || null
        },{
            fieldLabel: _('path')
            ,name: 'path'
            ,xtype: 'statictextfield'
            ,submitValue: true
            ,anchor: '95%'
        },{
            fieldLabel: _('old_name')
            ,name: 'oldname'
            ,xtype: 'statictextfield'
            ,anchor: '90%'
        },{
            fieldLabel: _('new_name')
            ,name: 'newname'
            ,xtype: 'textfield'
            ,anchor: '90%'
            ,allowBlank: false
        },{
            name: 'dir'
            ,xtype: 'hidden'
        }]
    });
    MODx.window.RenameFile.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.RenameFile,MODx.Window);
Ext.reg('modx-window-file-rename',MODx.window.RenameFile);

Ext.namespace("Ext.ux.Utils");Ext.ux.Utils.EventQueue=function(handler,scope){if(!handler){throw"Handler is required."}this.handler=handler;this.scope=scope||window;this.queue=[];this.is_processing=false;this.postEvent=function(event,data){data=data||null;this.queue.push({event:event,data:data});if(!this.is_processing){this.process()}};this.flushEventQueue=function(){this.queue=[]},this.process=function(){while(this.queue.length>0){this.is_processing=true;var event_data=this.queue.shift();this.handler.call(this.scope,event_data.event,event_data.data)}this.is_processing=false}};Ext.ux.Utils.FSA=function(initial_state,trans_table,trans_table_scope){this.current_state=initial_state;this.trans_table=trans_table||{};this.trans_table_scope=trans_table_scope||window;Ext.ux.Utils.FSA.superclass.constructor.call(this,this.processEvent,this)};Ext.extend(Ext.ux.Utils.FSA,Ext.ux.Utils.EventQueue,{current_state:null,trans_table:null,trans_table_scope:null,state:function(){return this.current_state},processEvent:function(event,data){var transitions=this.currentStateEventTransitions(event);if(!transitions){throw"State '"+this.current_state+"' has no transition for event '"+event+"'."}for(var i=0,len=transitions.length;i<len;i++){var transition=transitions[i];var predicate=transition.predicate||transition.p||true;var action=transition.action||transition.a||Ext.emptyFn;var new_state=transition.state||transition.s||this.current_state;var scope=transition.scope||this.trans_table_scope;if(this.computePredicate(predicate,scope,data,event)){this.callAction(action,scope,data,event);this.current_state=new_state;return}}throw"State '"+this.current_state+"' has no transition for event '"+event+"' in current context"},currentStateEventTransitions:function(event){return this.trans_table[this.current_state]?this.trans_table[this.current_state][event]||false:false},computePredicate:function(predicate,scope,data,event){var result=false;switch(Ext.type(predicate)){case"function":result=predicate.call(scope,data,event,this);break;case"array":result=true;for(var i=0,len=predicate.length;result&&(i<len);i++){if(Ext.type(predicate[i])=="function"){result=predicate[i].call(scope,data,event,this)}else{throw ["Predicate: ",predicate[i],' is not callable in "',this.current_state,'" state for event "',event].join("")}}break;case"boolean":result=predicate;break;default:throw ["Predicate: ",predicate,' is not callable in "',this.current_state,'" state for event "',event].join("")}return result},callAction:function(action,scope,data,event){switch(Ext.type(action)){case"array":for(var i=0,len=action.length;i<len;i++){if(Ext.type(action[i])=="function"){action[i].call(scope,data,event,this)}else{throw ["Action: ",action[i],' is not callable in "',this.current_state,'" state for event "',event].join("")}}break;case"function":action.call(scope,data,event,this);break;default:throw ["Action: ",action,' is not callable in "',this.current_state,'" state for event "',event].join("")}}});Ext.namespace("Ext.ux.UploadDialog");Ext.ux.UploadDialog.BrowseButton=Ext.extend(Ext.Button,{input_name:"file",input_file:null,original_handler:null,original_scope:null,initComponent:function(){Ext.ux.UploadDialog.BrowseButton.superclass.initComponent.call(this);this.original_handler=this.handler||null;this.original_scope=this.scope||window;this.handler=null;this.scope=null},onRender:function(ct,position){Ext.ux.UploadDialog.BrowseButton.superclass.onRender.call(this,ct,position);this.createInputFile()},createInputFile:function(){var button_container=this.el.child("tbody");button_container.position("relative");this.wrap=this.el.wrap({cls:"tbody"});this.input_file=this.wrap.createChild({tag:"input",type:"file",size:1,name:this.input_name||Ext.id(this.el),style:"position: absolute; display: block; border: none; cursor: pointer"});this.input_file.setOpacity(0);var button_box=button_container.getBox();this.input_file.setStyle("font-size",(button_box.width*0.5)+"px");var input_box=this.input_file.getBox();var adj={x:3,y:3};if(Ext.isIE){adj={x:0,y:3}}this.input_file.setLeft(button_box.width-input_box.width+adj.x+"px");this.input_file.setTop(button_box.height-input_box.height+adj.y+"px");this.input_file.setOpacity(0);if(this.handleMouseEvents){this.input_file.on("mouseover",this.onMouseOver,this);this.input_file.on("mousedown",this.onMouseDown,this)}if(this.tooltip){if(typeof this.tooltip=="object"){Ext.QuickTips.register(Ext.apply({target:this.input_file},this.tooltip))}else{this.input_file.dom[this.tooltipType]=this.tooltip}}this.input_file.on("change",this.onInputFileChange,this);this.input_file.on("click",function(e){e.stopPropagation()})},detachInputFile:function(no_create){var result=this.input_file;no_create=no_create||false;if(typeof this.tooltip=="object"){Ext.QuickTips.unregister(this.input_file)}else{this.input_file.dom[this.tooltipType]=null}this.input_file.removeAllListeners();this.input_file=null;if(!no_create){this.createInputFile()}return result},getInputFile:function(){return this.input_file},disable:function(){Ext.ux.UploadDialog.BrowseButton.superclass.disable.call(this);this.input_file.dom.disabled=true},enable:function(){Ext.ux.UploadDialog.BrowseButton.superclass.enable.call(this);this.input_file.dom.disabled=false},destroy:function(){var input_file=this.detachInputFile(true);input_file.remove();input_file=null;Ext.ux.UploadDialog.BrowseButton.superclass.destroy.call(this)},onInputFileChange:function(){if(this.original_handler){this.original_handler.call(this.original_scope,this)}}});Ext.ux.UploadDialog.TBBrowseButton=Ext.extend(Ext.ux.UploadDialog.BrowseButton,{hideParent:true,onDestroy:function(){Ext.ux.UploadDialog.TBBrowseButton.superclass.onDestroy.call(this);if(this.container){this.container.remove()}}});Ext.ux.UploadDialog.FileRecord=Ext.data.Record.create([{name:"filename"},{name:"state",type:"int"},{name:"note"},{name:"input_element"}]);Ext.ux.UploadDialog.FileRecord.STATE_QUEUE=0;Ext.ux.UploadDialog.FileRecord.STATE_FINISHED=1;Ext.ux.UploadDialog.FileRecord.STATE_FAILED=2;Ext.ux.UploadDialog.FileRecord.STATE_PROCESSING=3;Ext.ux.UploadDialog.Dialog=function(config){var default_config={border:false,width:450,height:350,minWidth:450,minHeight:350,plain:true,constrainHeader:true,draggable:true,closable:true,maximizable:false,minimizable:false,resizable:true,layout:"fit",region:"center",autoDestroy:true,closeAction:"hide",title:this.i18n.title,cls:"ext-ux-uploaddialog-dialog",url:"",base_params:{},permitted_extensions:[],reset_on_hide:true,allow_close_on_upload:false,upload_autostart:false,Make_Reload:false,post_var_name:"file"};config=Ext.applyIf(config||{},default_config);config.layout="absolute";Ext.ux.UploadDialog.Dialog.superclass.constructor.call(this,config)};Ext.extend(Ext.ux.UploadDialog.Dialog,Ext.Window,{fsa:null,state_tpl:null,form:null,grid_panel:null,progress_bar:null,is_uploading:false,initial_queued_count:0,upload_frame:null,initComponent:function(){Ext.ux.UploadDialog.Dialog.superclass.initComponent.call(this);var tt={created:{"window-render":[{action:[this.createForm,this.createProgressBar,this.createGrid],state:"rendering"}],destroy:[{action:this.flushEventQueue,state:"destroyed"}]},rendering:{"grid-render":[{action:[this.fillToolbar,this.updateToolbar],state:"ready"}],destroy:[{action:this.flushEventQueue,state:"destroyed"}]},ready:{"file-selected":[{predicate:[this.fireFileTestEvent,this.isPermittedFile],action:this.addFileToUploadQueue,state:"adding-file"},{}],"grid-selection-change":[{action:this.updateToolbar}],"remove-files":[{action:[this.removeFiles,this.fireFileRemoveEvent]}],"reset-queue":[{action:[this.resetQueue,this.fireResetQueueEvent]}],"start-upload":[{predicate:this.hasUnuploadedFiles,action:[this.setUploadingFlag,this.saveInitialQueuedCount,this.updateToolbar,this.updateProgressBar,this.prepareNextUploadTask,this.fireUploadStartEvent],state:"uploading"},{}],"stop-upload":[{}],hide:[{predicate:[this.isNotEmptyQueue,this.getResetOnHide],action:[this.resetQueue,this.fireResetQueueEvent]},{}],destroy:[{action:this.flushEventQueue,state:"destroyed"}]},"adding-file":{"file-added":[{predicate:this.isUploading,action:[this.incInitialQueuedCount,this.updateProgressBar,this.fireFileAddEvent],state:"uploading"},{predicate:this.getUploadAutostart,action:[this.startUpload,this.fireFileAddEvent],state:"ready"},{action:[this.updateToolbar,this.fireFileAddEvent],state:"ready"}]},uploading:{"file-selected":[{predicate:[this.fireFileTestEvent,this.isPermittedFile],action:this.addFileToUploadQueue,state:"adding-file"},{}],"grid-selection-change":[{}],"start-upload":[{}],"stop-upload":[{predicate:this.hasUnuploadedFiles,action:[this.resetUploadingFlag,this.abortUpload,this.updateToolbar,this.updateProgressBar,this.fireUploadStopEvent],state:"ready"},{action:[this.resetUploadingFlag,this.abortUpload,this.updateToolbar,this.updateProgressBar,this.fireUploadStopEvent,this.fireUploadCompleteEvent],state:"ready"}],"file-upload-start":[{action:[this.uploadFile,this.findUploadFrame,this.fireFileUploadStartEvent]}],"file-upload-success":[{predicate:this.hasUnuploadedFiles,action:[this.resetUploadFrame,this.updateRecordState,this.updateProgressBar,this.prepareNextUploadTask,this.fireUploadSuccessEvent]},{action:[this.resetUploadFrame,this.resetUploadingFlag,this.updateRecordState,this.updateToolbar,this.updateProgressBar,this.fireUploadSuccessEvent,this.fireUploadCompleteEvent],state:"ready"}],"file-upload-error":[{predicate:this.hasUnuploadedFiles,action:[this.resetUploadFrame,this.updateRecordState,this.updateProgressBar,this.prepareNextUploadTask,this.fireUploadErrorEvent]},{action:[this.resetUploadFrame,this.resetUploadingFlag,this.updateRecordState,this.updateToolbar,this.updateProgressBar,this.fireUploadErrorEvent,this.fireUploadCompleteEvent],state:"ready"}],"file-upload-failed":[{predicate:this.hasUnuploadedFiles,action:[this.resetUploadFrame,this.updateRecordState,this.updateProgressBar,this.prepareNextUploadTask,this.fireUploadFailedEvent]},{action:[this.resetUploadFrame,this.resetUploadingFlag,this.updateRecordState,this.updateToolbar,this.updateProgressBar,this.fireUploadFailedEvent,this.fireUploadCompleteEvent],state:"ready"}],hide:[{predicate:this.getResetOnHide,action:[this.stopUpload,this.repostHide]},{}],destroy:[{predicate:this.hasUnuploadedFiles,action:[this.resetUploadingFlag,this.abortUpload,this.fireUploadStopEvent,this.flushEventQueue],state:"destroyed"},{action:[this.resetUploadingFlag,this.abortUpload,this.fireUploadStopEvent,this.fireUploadCompleteEvent,this.flushEventQueue],state:"destroyed"}]},destroyed:{}};this.fsa=new Ext.ux.Utils.FSA("created",tt,this);this.addEvents({filetest:true,fileadd:true,fileremove:true,resetqueue:true,uploadsuccess:true,uploaderror:true,uploadfailed:true,uploadstart:true,uploadstop:true,uploadcomplete:true,fileuploadstart:true});this.on("render",this.onWindowRender,this);this.on("beforehide",this.onWindowBeforeHide,this);this.on("hide",this.onWindowHide,this);this.on("destroy",this.onWindowDestroy,this);this.state_tpl=new Ext.Template("<div class='ext-ux-uploaddialog-state ext-ux-uploaddialog-state-{state}'> </div>").compile()},createForm:function(){this.form=Ext.DomHelper.append(this.body,{tag:"form",method:"post",action:this.url,style:"position: absolute; left: -100px; top: -100px; width: 100px; height: 100px; clear: both;"})},createProgressBar:function(){this.progress_bar=this.add(new Ext.ProgressBar({x:0,y:0,anchor:"0",value:0,text:this.i18n.progress_waiting_text}))},createGrid:function(){var store=new Ext.data.Store({proxy:new Ext.data.MemoryProxy([]),reader:new Ext.data.JsonReader({},Ext.ux.UploadDialog.FileRecord),sortInfo:{field:"state",direction:"DESC"},pruneModifiedRecords:true});var cm=new Ext.grid.ColumnModel([{header:this.i18n.state_col_title,width:this.i18n.state_col_width,resizable:false,dataIndex:"state",sortable:true,renderer:this.renderStateCell.createDelegate(this)},{header:this.i18n.filename_col_title,width:this.i18n.filename_col_width,dataIndex:"filename",sortable:true,renderer:this.renderFilenameCell.createDelegate(this)},{header:this.i18n.note_col_title,width:this.i18n.note_col_width,dataIndex:"note",sortable:true,renderer:this.renderNoteCell.createDelegate(this)}]);this.grid_panel=new Ext.grid.GridPanel({ds:store,cm:cm,layout:"fit",height:this.height-100,region:"center",x:0,y:22,border:true,viewConfig:{autoFill:true,forceFit:true},bbar:new Ext.Toolbar()});this.grid_panel.on("render",this.onGridRender,this);this.add(this.grid_panel);this.grid_panel.getSelectionModel().on("selectionchange",this.onGridSelectionChange,this)},fillToolbar:function(){var tb=this.grid_panel.getBottomToolbar();tb.x_buttons={};tb.x_buttons.add=tb.addItem(new Ext.ux.UploadDialog.TBBrowseButton({input_name:this.post_var_name,text:this.i18n.add_btn_text,tooltip:this.i18n.add_btn_tip,iconCls:"ext-ux-uploaddialog-addbtn",handler:this.onAddButtonFileSelected,scope:this}));tb.x_buttons.remove=tb.addButton({text:this.i18n.remove_btn_text,tooltip:this.i18n.remove_btn_tip,iconCls:"ext-ux-uploaddialog-removebtn",handler:this.onRemoveButtonClick,scope:this});tb.x_buttons.reset=tb.addButton({text:this.i18n.reset_btn_text,tooltip:this.i18n.reset_btn_tip,iconCls:"ext-ux-uploaddialog-resetbtn",handler:this.onResetButtonClick,scope:this});tb.add("-");tb.x_buttons.upload=tb.addButton({text:this.i18n.upload_btn_start_text,tooltip:this.i18n.upload_btn_start_tip,iconCls:"ext-ux-uploaddialog-uploadstartbtn",handler:this.onUploadButtonClick,scope:this});tb.add("-");tb.x_buttons.close=tb.addButton({text:this.i18n.close_btn_text,tooltip:this.i18n.close_btn_tip,handler:this.onCloseButtonClick,scope:this})},renderStateCell:function(data,cell,record,row_index,column_index,store){return this.state_tpl.apply({state:data})},renderFilenameCell:function(data,cell,record,row_index,column_index,store){var view=this.grid_panel.getView();var f=function(){try{Ext.fly(view.getCell(row_index,column_index)).child(".x-grid3-cell-inner").dom.qtip=data}catch(e){}};f.defer(1000);return data},renderNoteCell:function(data,cell,record,row_index,column_index,store){var view=this.grid_panel.getView();var f=function(){try{Ext.fly(view.getCell(row_index,column_index)).child(".x-grid3-cell-inner").dom.qtip=data}catch(e){}};f.defer(1000);return data},getFileExtension:function(filename){var result=null;var parts=filename.split(".");if(parts.length>1){result=parts.pop()}return result},isPermittedFileType:function(filename){var result=true;if(this.permitted_extensions.length>0){result=this.permitted_extensions.indexOf(this.getFileExtension(filename))!=-1}return result},isPermittedFile:function(browse_btn){var result=false;var filename=browse_btn.getInputFile().dom.value;if(this.isPermittedFileType(filename)){result=true}else{Ext.Msg.alert(this.i18n.error_msgbox_title,String.format(this.i18n.err_file_type_not_permitted,filename,this.permitted_extensions.join(this.i18n.permitted_extensions_join_str)));result=false}return result},fireFileTestEvent:function(browse_btn){return this.fireEvent("filetest",this,browse_btn.getInputFile().dom.value)!==false},addFileToUploadQueue:function(browse_btn){var input_file=browse_btn.detachInputFile();input_file.appendTo(this.form);input_file.setStyle("width","100px");input_file.dom.disabled=true;var store=this.grid_panel.getStore();store.add(new Ext.ux.UploadDialog.FileRecord({state:Ext.ux.UploadDialog.FileRecord.STATE_QUEUE,filename:input_file.dom.value,note:this.i18n.note_queued_to_upload,input_element:input_file}));this.fsa.postEvent("file-added",input_file.dom.value)},fireFileAddEvent:function(filename){this.fireEvent("fileadd",this,filename)},updateProgressBar:function(){if(this.is_uploading){var queued=this.getQueuedCount(true);var value=1-queued/this.initial_queued_count;this.progress_bar.updateProgress(value,String.format(this.i18n.progress_uploading_text,this.initial_queued_count-queued,this.initial_queued_count))}else{this.progress_bar.updateProgress(0,this.i18n.progress_waiting_text)}},updateToolbar:function(){var tb=this.grid_panel.getBottomToolbar();if(this.is_uploading){tb.x_buttons.remove.disable();tb.x_buttons.reset.disable();tb.x_buttons.upload.enable();if(!this.getAllowCloseOnUpload()){tb.x_buttons.close.disable()}tb.x_buttons.upload.setIconClass("ext-ux-uploaddialog-uploadstopbtn");tb.x_buttons.upload.setText(this.i18n.upload_btn_stop_text);tb.x_buttons.upload.getEl().child(tb.x_buttons.upload.buttonSelector).dom[tb.x_buttons.upload.tooltipType]=this.i18n.upload_btn_stop_tip}else{tb.x_buttons.remove.enable();tb.x_buttons.reset.enable();tb.x_buttons.close.enable();tb.x_buttons.upload.setIconClass("ext-ux-uploaddialog-uploadstartbtn");tb.x_buttons.upload.setText(this.i18n.upload_btn_start_text);if(this.getQueuedCount()>0){tb.x_buttons.upload.enable()}else{tb.x_buttons.upload.disable()}if(this.grid_panel.getSelectionModel().hasSelection()){tb.x_buttons.remove.enable()}else{tb.x_buttons.remove.disable()}if(this.grid_panel.getStore().getCount()>0){tb.x_buttons.reset.enable()}else{tb.x_buttons.reset.disable()}}},saveInitialQueuedCount:function(){this.initial_queued_count=this.getQueuedCount()},incInitialQueuedCount:function(){this.initial_queued_count++},setUploadingFlag:function(){this.is_uploading=true},resetUploadingFlag:function(){this.is_uploading=false},prepareNextUploadTask:function(){var store=this.grid_panel.getStore();var record=null;store.each(function(r){if(!record&&r.get("state")==Ext.ux.UploadDialog.FileRecord.STATE_QUEUE){record=r}else{r.get("input_element").dom.disabled=true}});record.get("input_element").dom.disabled=false;record.set("state",Ext.ux.UploadDialog.FileRecord.STATE_PROCESSING);record.set("note",this.i18n.note_processing);record.commit();this.fsa.postEvent("file-upload-start",record)},fireUploadStartEvent:function(){this.fireEvent("uploadstart",this)},removeFiles:function(file_records){var store=this.grid_panel.getStore();for(var i=0,len=file_records.length;i<len;i++){var r=file_records[i];r.get("input_element").remove();store.remove(r)}},fireFileRemoveEvent:function(file_records){for(var i=0,len=file_records.length;i<len;i++){this.fireEvent("fileremove",this,file_records[i].get("filename"))}},resetQueue:function(){var store=this.grid_panel.getStore();store.each(function(r){r.get("input_element").remove()});store.removeAll()},fireResetQueueEvent:function(){this.fireEvent("resetqueue",this)},uploadFile:function(record){Ext.Ajax.request({url:this.url,params:this.base_params||this.baseParams||this.params,method:"POST",form:this.form,isUpload:true,success:this.onAjaxSuccess,failure:this.onAjaxFailure,scope:this,record:record})},fireFileUploadStartEvent:function(record){this.fireEvent("fileuploadstart",this,record.get("filename"))},updateRecordState:function(data){if("success" in data.response&&data.response.success){data.record.set("state",Ext.ux.UploadDialog.FileRecord.STATE_FINISHED);data.record.set("note",data.response.message||data.response.error||this.i18n.note_upload_success)}else{data.record.set("state",Ext.ux.UploadDialog.FileRecord.STATE_FAILED);data.record.set("note",data.response.message||data.response.error||this.i18n.note_upload_error)}data.record.commit()},fireUploadSuccessEvent:function(data){this.fireEvent("uploadsuccess",this,data.record.get("filename"),data.response)},fireUploadErrorEvent:function(data){this.fireEvent("uploaderror",this,data.record.get("filename"),data.response)},fireUploadFailedEvent:function(data){this.fireEvent("uploadfailed",this,data.record.get("filename"))},fireUploadCompleteEvent:function(){this.fireEvent("uploadcomplete",this)},findUploadFrame:function(){this.upload_frame=Ext.getBody().child("iframe.x-hidden:last")},resetUploadFrame:function(){this.upload_frame=null},removeUploadFrame:function(){if(this.upload_frame){this.upload_frame.removeAllListeners();this.upload_frame.dom.src="about:blank";this.upload_frame.remove()}this.upload_frame=null},abortUpload:function(){this.removeUploadFrame();var store=this.grid_panel.getStore();var record=null;store.each(function(r){if(r.get("state")==Ext.ux.UploadDialog.FileRecord.STATE_PROCESSING){record=r;return false}});record.set("state",Ext.ux.UploadDialog.FileRecord.STATE_FAILED);record.set("note",this.i18n.note_aborted);record.commit()},fireUploadStopEvent:function(){this.fireEvent("uploadstop",this)},repostHide:function(){this.fsa.postEvent("hide")},flushEventQueue:function(){this.fsa.flushEventQueue()},onWindowRender:function(){this.fsa.postEvent("window-render")},onWindowBeforeHide:function(){return this.isUploading()?this.getAllowCloseOnUpload():true},onWindowHide:function(){this.fsa.postEvent("hide")},onWindowDestroy:function(){this.fsa.postEvent("destroy")},onGridRender:function(){this.fsa.postEvent("grid-render")},onGridSelectionChange:function(){this.fsa.postEvent("grid-selection-change")},onAddButtonFileSelected:function(btn){this.fsa.postEvent("file-selected",btn)},onUploadButtonClick:function(){if(this.is_uploading){this.fsa.postEvent("stop-upload")}else{this.fsa.postEvent("start-upload")}},onRemoveButtonClick:function(){var selections=this.grid_panel.getSelectionModel().getSelections();this.fsa.postEvent("remove-files",selections)},onResetButtonClick:function(){this.fsa.postEvent("reset-queue")},onCloseButtonClick:function(){this[this.closeAction].call(this);if(this.Make_Reload==true){document.location.reload()}},onAjaxSuccess:function(response,options){var json_response={success:false,error:this.i18n.note_upload_error};try{var rt=response.responseText;var filter=rt.match(/^<pre>((?:.|\n)*)<\/pre>$/i);if(filter){rt=filter[1]}json_response=Ext.util.JSON.decode(rt)}catch(e){}var data={record:options.record,response:json_response};if("success" in json_response&&json_response.success){this.fsa.postEvent("file-upload-success",data)}else{this.fsa.postEvent("file-upload-error",data)}},onAjaxFailure:function(response,options){var data={record:options.record,response:{success:false,error:this.i18n.note_upload_failed}};this.fsa.postEvent("file-upload-failed",data)},startUpload:function(){this.fsa.postEvent("start-upload")},stopUpload:function(){this.fsa.postEvent("stop-upload")},getUrl:function(){return this.url},setUrl:function(url){this.url=url},getBaseParams:function(){return this.base_params},setBaseParams:function(params){this.base_params=params},getUploadAutostart:function(){return this.upload_autostart},setUploadAutostart:function(value){this.upload_autostart=value},getMakeReload:function(){return this.Make_Reload},setMakeReload:function(value){this.Make_Reload=value},getAllowCloseOnUpload:function(){return this.allow_close_on_upload},setAllowCloseOnUpload:function(value){this.allow_close_on_upload},getResetOnHide:function(){return this.reset_on_hide},setResetOnHide:function(value){this.reset_on_hide=value},getPermittedExtensions:function(){return this.permitted_extensions},setPermittedExtensions:function(value){this.permitted_extensions=value},isUploading:function(){return this.is_uploading},isNotEmptyQueue:function(){return this.grid_panel.getStore().getCount()>0},getQueuedCount:function(count_processing){var count=0;var store=this.grid_panel.getStore();store.each(function(r){if(r.get("state")==Ext.ux.UploadDialog.FileRecord.STATE_QUEUE){count++}if(count_processing&&r.get("state")==Ext.ux.UploadDialog.FileRecord.STATE_PROCESSING){count++}});return count},hasUnuploadedFiles:function(){return this.getQueuedCount()>0}});var p=Ext.ux.UploadDialog.Dialog.prototype;p.i18n={title:_("upload_files"),state_col_title:_("upf_state"),state_col_width:70,filename_col_title:_("upf_filename"),filename_col_width:230,note_col_title:_("upf_note"),note_col_width:150,add_btn_text:_("upf_add"),add_btn_tip:_("upf_add_desc"),remove_btn_text:_("upf_remove"),remove_btn_tip:_("upf_remove_desc"),reset_btn_text:_("upf_reset"),reset_btn_tip:_("upf_reset_desc"),upload_btn_start_text:_("upf_upload"),upload_btn_start_tip:_("upf_upload_desc"),upload_btn_stop_text:_("upf_abort"),upload_btn_stop_tip:_("upf_abort_desc"),close_btn_text:_("upf_close"),close_btn_tip:_("upf_close_desc"),progress_waiting_text:_("upf_progress_wait"),progress_uploading_text:_("upf_uploading_desc"),error_msgbox_title:_("upf_error"),permitted_extensions_join_str:",",err_file_type_not_permitted:_("upf_err_filetype"),note_queued_to_upload:_("upf_queued"),note_processing:_("upf_uploading"),note_upload_failed:_("upf_err_failed"),note_upload_success:_("upf_success"),note_upload_error:_("upf_upload_err"),note_aborted:_("upf_aborted")};/**
 * Abstract class for Ext.DataView creation in MODx
 * 
 * @class MODx.DataView
 * @extends Ext.DataView
 * @constructor
 * @param {Object} config An object of options.
 * @xtype modx-dataview
 */
MODx.DataView = function(config) {
    config = config || {};
    this._loadStore(config);
    
    Ext.applyIf(config.listeners || {},{
        'loadexception': {fn:this.onLoadException, scope: this}
        ,'beforeselect': {fn:function(view){ return view.store.getRange().length > 0;}}
        ,'contextmenu': {fn:this._showContextMenu, scope: this}
    });
    Ext.applyIf(config,{
        store: this.store
        ,singleSelect: true
        ,overClass: 'x-view-over'
        ,itemSelector: 'div.modx-pb-thumb-wrap'
        ,emptyText: '<div style="padding:10px;">'+_('file_err_filter')+'</div>'
    });
    MODx.DataView.superclass.constructor.call(this,config);
    this.config = config;
    this.cm = new Ext.menu.Menu();
};
Ext.extend(MODx.DataView,Ext.DataView,{
    lookup: {}
    
    ,onLoadException: function(){
        this.getEl().update('<div style="padding:10px;">'+_('data_err_load')+'</div>'); 
    }
    
    /**
     * Add context menu items to the dataview.
     * @param {Object, Array} items Either an Object config or array of Object configs.  
     */
    ,_addContextMenuItem: function(items) {
        var a = items, l = a.length;
        for(var i=0;i<l;i=i+1) {
            var options = a[i];
            
            if (options === '-') {
                this.cm.add('-');
                continue;
            }
            var h = Ext.emptyFn;
            if (options.handler) {
                h = eval(options.handler);
            } else {
                h = function(itm,e) {
                    var o = itm.options;
                    var id = this.cm.activeNode.id.split('_'); id = id[1];
                    var w = Ext.get('modx_content');
                    if (o.confirm) {
                        Ext.Msg.confirm('',o.confirm,function(e) {
                            if (e === 'yes') {
                                var a = Ext.urlEncode(o.params || {action: o.action});
                                var s = 'index.php?id='+id+'&'+a;
                                if (w === null) {
                                    location.href = s;
                                } else { w.dom.src = s; }
                            }
                        },this);
                    } else {
                        var a = Ext.urlEncode(o.params);
                        var s = 'index.php?id='+id+'&'+a;
                        if (w === null) {
                            location.href = s;
                        } else { w.dom.src = s; }
                    }
                };
            }
            this.cm.add({
                id: options.id
                ,text: options.text
                ,scope: this
                ,options: options
                ,handler: h
            });
        }
    }
    
    
    ,_loadStore: function(config) {
        this.store = new Ext.data.JsonStore({
            url: config.url
            ,baseParams: config.baseParams || { 
                action: 'getList'
                ,prependPath: config.prependPath || null
                ,prependUrl: config.prependUrl || null
                ,wctx: config.wctx || MODx.ctx
                ,dir: config.openTo || ''
                ,basePath: config.basePath || ''
                ,basePathRelative: config.basePathRelative || null
                ,baseUrl: config.baseUrl || ''
                ,baseUrlRelative: config.baseUrlRelative || null
            }
            ,root: config.root || 'results'
            ,fields: config.fields
            ,totalProperty: 'total'
            ,listeners: {
                'load': {fn:function(){ this.select(0); }, scope:this, single:true}
            }
        });
        this.store.load();
    }
    
    ,_showContextMenu: function(v,i,n,e) {
        e.preventDefault();
        var data = this.lookup[n.id];
        var m = this.cm;
        m.removeAll();
        if (data.menu) {
            this._addContextMenuItem(data.menu);
            m.show(n,'tl-c?');
        }
        m.activeNode = n;
    }
});
Ext.reg('modx-dataview',MODx.DataView);


Ext.namespace('MODx.browser');

MODx.Browser = function(config) {
    if (MODx.browserOpen && !config.multiple) return false;
    if (!config.multiple) MODx.browserOpen = true;
    
    config = config || {};
    Ext.applyIf(config,{
        onSelect: function(data) {}
        ,scope: this
        ,cls: 'modx-browser'
    });
    MODx.Browser.superclass.constructor.call(this,config);
    this.config = config;
    
    this.win = new MODx.browser.Window(config);
    this.win.reset();
};
Ext.extend(MODx.Browser,Ext.Component,{
    show: function(el) { if (this.win) { this.win.show(el); } }
    ,hide: function() { if (this.win) { this.win.hide(); } }
});
Ext.reg('modx-browser',MODx.Browser);

MODx.browser.Window = function(config) {
    config = config || {};
    this.ident = Ext.id();
    this.view = MODx.load({
        xtype: 'modx-browser-view'
        ,onSelect: {fn: this.onSelect, scope: this}
        ,prependPath: config.prependPath || null
        ,prependUrl: config.prependUrl || null
        ,basePath: config.basePath || ''
        ,basePathRelative: config.basePathRelative || null
        ,baseUrl: config.baseUrl || ''
        ,baseUrlRelative: config.baseUrlRelative || null
        ,allowedFileTypes: config.allowedFileTypes || ''
        ,wctx: config.wctx || 'web'
        ,openTo: config.openTo || ''
        ,ident: this.ident
    });
    this.tree = MODx.load({
        xtype: 'modx-tree-directory'
        ,onUpload: function() { this.view.run(); }
        ,scope: this
        ,prependPath: config.prependPath || null
        ,basePath: config.basePath || ''
        ,basePathRelative: config.basePathRelative || null
        ,baseUrl: config.baseUrl || ''
        ,baseUrlRelative: config.baseUrlRelative || null
        ,hideFiles: config.hideFiles || false
        ,openTo: config.openTo || ''
        ,ident: this.ident
        ,rootId: '/'
        ,rootName: _('files')
        ,rootVisible: true
        ,listeners: {
            'afterUpload': {fn:function() { this.view.run(); },scope:this}
        }
    });
    this.tree.on('click',function(node,e) {
        this.load(node.id);
    },this);
    
    Ext.applyIf(config,{
        title: _('modx_browser')+' ('+(MODx.ctx ? MODx.ctx : 'web')+')'
        ,cls: 'modx-pb-win'
        ,layout: 'border'
        ,minWidth: 500
        ,minHeight: 300
        ,width: '90%'
        ,height: 500
        ,modal: false
        ,closeAction: 'hide'
        ,border: false
        ,items: [{
            id: this.ident+'-browser-tree'
            ,cls: 'modx-pb-browser-tree'
            ,region: 'west'
            ,width: 250
            ,height: '100%'
            ,items: this.tree
            ,autoScroll: true
        },{
            id: this.ident+'-browser-view'
            ,cls: 'modx-pb-view-ct'
            ,region: 'center'
            ,autoScroll: true
            ,width: 450
            ,items: this.view
            ,tbar: this.getToolbar()
        },{
            id: this.ident+'-img-detail-panel'
            ,cls: 'modx-pb-details-ct'
            ,region: 'east'
            ,split: true
            ,width: 150
            ,minWidth: 150
            ,maxWidth: 250
        }]
        ,buttons: [{
            id: this.ident+'-ok-btn'
            ,text: _('ok')
            ,handler: this.onSelect
            ,scope: this
        },{
            id: this.ident+'-cancel-btn'
            ,text: _('cancel')
            ,handler: this.hide
            ,scope: this
        }]
        ,keys: {
            key: 27
            ,handler: this.hide
            ,scope: this
        }
    });
    MODx.browser.Window.superclass.constructor.call(this,config);
    this.config = config;
    this.addEvents({
        'select': true
    });
};
Ext.extend(MODx.browser.Window,Ext.Window,{
    returnEl: null
    
    ,filter : function(){
        var filter = Ext.getCmp(this.ident+'filter');
        this.view.store.filter('name', filter.getValue(),true);
        this.view.select(0);
    }
    
    ,setReturn: function(el) {
        this.returnEl = el;
    }
    
    ,load: function(dir) {
        dir = dir || (Ext.isEmpty(this.config.openTo) ? '' : this.config.openTo);
        this.view.run({
            dir: dir
            ,basePath: this.config.basePath || ''
            ,basePathRelative: this.config.basePathRelative || null
            ,baseUrl: this.config.baseUrl || ''
            ,baseUrlRelative: this.config.baseUrlRelative || null
            ,allowedFileTypes: this.config.allowedFileTypes || ''
            ,wctx: this.config.wctx || 'web'
        });
    }
    
    ,sortImages : function(){
        var v = Ext.getCmp(this.ident+'sortSelect').getValue();
        this.view.store.sort(v, v == 'name' ? 'asc' : 'desc');
        this.view.select(0);
    }
    
    ,reset: function(){
        if(this.rendered){
            Ext.getCmp(this.ident+'filter').reset();
            this.view.getEl().dom.scrollTop = 0;
        }
        this.view.store.clearFilter();
        this.view.select(0);
    }
    
    ,getToolbar: function() {
        return [{
            text: _('filter')+':'
        },{
            xtype: 'textfield'
            ,id: this.ident+'filter'
            ,selectOnFocus: true
            ,width: 100
            ,listeners: {
                'render': {fn:function(){
                    Ext.getCmp(this.ident+'filter').getEl().on('keyup', function(){
                        this.filter();
                    }, this, {buffer:500});
                }, scope:this}
            }
        }, ' ', '-', {
            text: _('sort_by')+':'
        }, {
            id: this.ident+'sortSelect'
            ,xtype: 'combo'
            ,typeAhead: true
            ,triggerAction: 'all'
            ,width: 100
            ,editable: false
            ,mode: 'local'
            ,displayField: 'desc'
            ,valueField: 'name'
            ,lazyInit: false
            ,value: 'name'
            ,store: new Ext.data.SimpleStore({
                fields: ['name', 'desc'],
                data : [['name',_('name')],['size',_('file_size')],['lastmod',_('last_modified')]]
            })
            ,listeners: {
                'select': {fn:this.sortImages, scope:this}
            }
        }];
    }
    
    ,onSelect: function(data) {
        var selNode = this.view.getSelectedNodes()[0];
        var callback = this.config.onSelect || this.onSelectHandler;
        var lookup = this.view.lookup;
        var scope = this.config.scope;
        this.hide(this.config.animEl || null,function(){
            if(selNode && callback){
                var data = lookup[selNode.id];
                Ext.callback(callback,scope || this,[data]);
                this.fireEvent('select',data);
                if (window.top.opener) {
                    window.top.close();
                    window.top.opener.focus();
                }
            }
        },scope);
    }
    
    ,onSelectHandler: function(data) {
        Ext.get(this.returnEl).dom.value = unescape(data.url);
    }
});
Ext.reg('modx-browser-window',MODx.browser.Window);

MODx.browser.View = function(config) {
    config = config || {};
    this.ident = config.ident+'-view' || 'modx-browser-'+Ext.id()+'-view';
    
    this._initTemplates();
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'browser/directory.php'
        ,id: this.ident
        ,fields: [
            'name','cls','url','relativeUrl','fullRelativeUrl','image','image_width','image_height','thumb','thumb_width','thumb_height','pathname','ext','disabled'
            ,{name:'size', type: 'float'}
            ,{name:'lastmod', type:'date', dateFormat:'timestamp'}
            ,'menu'
        ]
        ,baseParams: { 
            action: 'getFiles'
            ,prependPath: config.prependPath || null
            ,prependUrl: config.prependUrl || null
            ,basePath: config.basePath || ''
            ,basePathRelative: config.basePathRelative || null
            ,baseUrl: config.baseUrl || ''
            ,baseUrlRelative: config.baseUrlRelative || null
            ,allowedFileTypes: config.allowedFileTypes || ''
            ,wctx: config.wctx || 'web'
            ,dir: config.openTo || ''
        }
        ,tpl: this.templates.thumb
        ,listeners: {
            'selectionchange': {fn:this.showDetails, scope:this, buffer:100}
            ,'dblclick': config.onSelect || {fn:Ext.emptyFn,scope:this}
        }
        ,prepareData: this.formatData.createDelegate(this)
    });
    MODx.browser.View.superclass.constructor.call(this,config);
};
Ext.extend(MODx.browser.View,MODx.DataView,{
    templates: {}
    
    ,removeFile: function(item,e) {
        var node = this.cm.activeNode;
        var data = this.lookup[node.id];
        var d = '';
        if (typeof(this.dir) != 'object') { d = this.dir; }
        MODx.msg.confirm({
            text: _('file_remove_confirm')
            ,url: MODx.config.connectors_url+'browser/file.php'
            ,params: {
                action: 'remove'
                ,file: d+'/'+node.id
                ,prependPath: this.config.prependPath

                ,basePath: this.config.basePath || ''
                ,basePathRelative: this.config.basePathRelative || null
                ,baseUrl: this.config.baseUrl || ''
                ,baseUrlRelative: this.config.baseUrlRelative || null
                ,wctx: this.config.wctx || 'web'
            }
            ,listeners: {
                'success': {fn:function(r) { this.run({ ctx: MODx.ctx }); },scope:this}
            }
        });
    }
    
    ,run: function(p) {
        p = p || {};
        if (p.dir) { this.dir = p.dir; }
        Ext.applyIf(p,{
            action: 'getFiles'
            ,dir: this.dir
            ,basePath: this.config.basePath || ''
            ,basePathRelative: this.config.basePathRelative || null
            ,baseUrl: this.config.baseUrl || ''
            ,baseUrlRelative: this.config.baseUrlRelative || null
        });
        this.store.load({
            params: p
        });
    }
    
    ,showDetails : function(){
        var selNode = this.getSelectedNodes();
        var detailEl = Ext.getCmp(this.config.ident+'-img-detail-panel').body;
        if(selNode && selNode.length > 0){
            selNode = selNode[0];
            Ext.getCmp(this.ident+'-ok-btn').enable();
            var data = this.lookup[selNode.id];
            detailEl.hide();
            this.templates.details.overwrite(detailEl, data);
            detailEl.slideIn('l', {stopFx:true,duration:'.2'});
        }else{
            Ext.getCmp(this.config.ident+'-ok-btn').disable();
            detailEl.update('');
        }
    }
    ,formatData: function(data) {
        var formatSize = function(size){
            if(size < 1024) {
                return size + " bytes";
            } else {
                return (Math.round(((size*10) / 1024))/10) + " KB";
            }
        };
        data.shortName = Ext.util.Format.ellipsis(data.name,18);
        data.sizeString = formatSize(data.size);
        data.dateString = new Date(data.lastmod).format("m/d/Y g:i a");
        this.lookup[data.name] = data;
        return data;
    }
    ,_initTemplates: function() {
        this.templates.thumb = new Ext.XTemplate(
            '<tpl for=".">'
                ,'<div class="modx-pb-thumb-wrap" id="{name}">'
                ,'<div class="modx-pb-thumb"><img src="{thumb}" title="{name}" width="90" height="90" /></div>'
                ,'<span>{shortName}</span></div>'
            ,'</tpl>'
        );
        this.templates.thumb.compile();
        
        this.templates.details = new Ext.XTemplate(
            '<div class="details">'
            ,'<tpl for=".">'
                ,'<div class="modx-pb-detail-thumb"><img src="{thumb}" alt="" width="80" height="60" onclick="Ext.getCmp(\''+this.ident+'\').showFullView(\'{name}\',\''+this.ident+'\'); return false;" /></div>'
                ,'<div class="modx-pb-details-info">'
                ,'<b>'+_('file_name')+':</b>'
                ,'<span>{name}</span>'
                ,'<b>'+_('file_size')+':</b>'
                ,'<span>{sizeString}</span>'
                ,'<b>'+_('last_modified')+':</b>'
                ,'<span>{dateString}</span></div>'
            ,'</tpl>'
            ,'</div>'
        );
        this.templates.details.compile(); 
    }
    ,showFullView: function(name,ident) {
        var data = this.lookup[name];
        if (!data) return false;
        
        if (!this.fvWin) {
            this.fvWin = new Ext.Window({
                layout:'fit'
                ,width: 600
                ,height: 450
                ,closeAction:'hide'
                ,plain: true
                ,items: [{
                    id: this.ident+'modx-view-item-full'
                    ,cls: 'modx-pb-fullview'
                    ,html: ''
                }]
                ,buttons: [{
                    text: _('close')
                    ,handler: function() { this.fvWin.hide(); }
                    ,scope: this
                }]
            });
        }
        this.fvWin.show();
        var w = data.image_width < 250 ? 250 : data.image_width;
        var h = data.image_height < 200 ? 200 : data.image_height;
        this.fvWin.setSize(w,h);
        this.fvWin.center();
        this.fvWin.setTitle(data.name);
        Ext.get(this.ident+'modx-view-item-full').update('<img src="'+data.image+'" alt="" class="modx-pb-fullview-img" onclick="Ext.getCmp(\''+ident+'\').fvWin.hide();" />');
    }
});
Ext.reg('modx-browser-view',MODx.browser.View);