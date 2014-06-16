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
    Ext.apply(Ext.Updater.prototype,{
        text: _('loading')
    });
    Ext.apply(Ext.LoadMask.prototype,{
        msg : _('loading')
    });
    Ext.apply(Ext.layout.BorderLayout.SplitRegion.prototype,{
        splitTip: _('ext_splittip')
    });
    Ext.apply(Ext.form.BasicForm.prototype,{
        waitTitle: _('please_wait')
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
});
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
    ,renderElements : function(n, a, targetNode, bulkRender){
        
        this.indentMarkup = n.parentNode ? n.parentNode.ui.getChildIndent() : '';

        var cb = Ext.isBoolean(a.checked),
            nel,
            href = this.getHref(a.href),
            iconMarkup = '<i class="icon-large'+(a.icon ? " x-tree-node-inline-icon" : "")+(a.iconCls ? " "+a.iconCls : "")+'" unselectable="on"></i>',
            elbowMarkup = n.attributes.pseudoroot ?
                '<i class="icon-sort-down expanded-icon"></i>' :
                //'<img alt="" src="'+ this.emptyIcon+ '" class="x-tree-ec-icon x-tree-elbow" />',
                '<i class="x-tree-ec-icon x-tree-elbow"></i>',

            buf =  ['<li class="x-tree-node"><div ext:tree-node-id="',n.id,'" class="x-tree-node-el x-tree-node-leaf x-unselectable ', a.cls,'" unselectable="on">',
                    '<span class="x-tree-node-indent">',this.indentMarkup,"</span>",
                    elbowMarkup,
                    iconMarkup,
                    cb ? ('<input class="x-tree-node-cb" type="checkbox" ' + (a.checked ? 'checked="checked" />' : '/>')) : '',
                    '<a hidefocus="on" class="x-tree-node-anchor" href="',href,'" tabIndex="1" ',
                    a.hrefTarget ? ' target="'+a.hrefTarget+'"' : "", '><span unselectable="on">',n.text,"</span></a></div>",
                    '<ul class="x-tree-node-ct" style="display:none;"></ul>',
                    "</li>"].join('');

        if(bulkRender !== true && n.nextSibling && (nel = n.nextSibling.ui.getEl())){
            this.wrap = Ext.DomHelper.insertHtml("beforeBegin", nel, buf);
        }else{
            this.wrap = Ext.DomHelper.insertHtml("beforeEnd", targetNode, buf);
        }

        this.elNode = this.wrap.childNodes[0];
        this.ctNode = this.wrap.childNodes[1];
        var cs = this.elNode.childNodes;
        this.indentNode = cs[0];
        this.ecNode = cs[1];
        this.iconNode = cs[2];
        var index = 3;
        if(cb){
            this.checkbox = cs[3];
            
            this.checkbox.defaultChecked = this.checkbox.checked;
            index++;
        }
        this.anchor = cs[index];
        this.textNode = cs[index].firstChild;
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


Ext.Button.buttonTemplate = new Ext.Template(
    '<span id="{4}" class="x-btn {1} {3}" unselectable="on"><em class="{2}"><button type="{0}"></button></em></span>'
);
Ext.Button.buttonTemplate.compile();

Ext.TabPanel.prototype.itemTpl = new Ext.Template(
     '<li class="{cls}" id="{id}"><a class="x-tab-strip-close"></a>',
     '<span class="x-tab-strip-text {iconCls}">{text}</span></li>'
);
Ext.TabPanel.prototype.itemTpl.disableFormats = true;
Ext.TabPanel.prototype.itemTpl.compile();

/**
 * This namespace should be in another file but I dicided to put it here for consistancy.
 */
Ext.namespace('Ext.ux.Utils');

/**
 * This class implements event queue behaviour.
 *
 * @class Ext.ux.Utils.EventQueue
 * @param function  handler  Event handler.
 * @param object    scope    Handler scope.
 */
Ext.ux.Utils.EventQueue = function(handler, scope) {
  if (!handler) {
    throw 'Handler is required.';
  }
  this.handler = handler;
  this.scope = scope || window;
  this.queue = [];
  this.is_processing = false;
  
  /**
   * Posts event into the queue.
   * 
   * @access public
   * @param mixed event Event identificator.
   * @param mixed data  Event data.
   */
  this.postEvent = function(event, data) {
    data = data || null;
    this.queue.push({event: event, data: data});
    if (!this.is_processing) {
      this.process();
    }
  }
  
  this.flushEventQueue = function() {
    this.queue = [];
  },
  
  /**
   * @access private
   */
  this.process = function() {
    while (this.queue.length > 0) {
      this.is_processing = true;
      var event_data = this.queue.shift();
      this.handler.call(this.scope, event_data.event, event_data.data);
    }
    this.is_processing = false;
  }
};

/**
 * This class implements Mili's finite state automata behaviour.
 *  
 *  Transition / output table format:
 *  {
 *    'state_1' : {
 *      'event_1' : [
 *        {
 *          p|predicate: function,    // Transition predicate, optional, default to true.
 *                                    // If array then conjunction will be applyed to the operands.
 *                                    // Predicate signature is (data, event, this).
 *          a|action: function|array, // Transition action, optional, default to Ext.emptyFn.
 *                                    // If array then methods will be called sequentially.
 *                                    // Action signature is (data, event, this).
 *          s|state: 'state_x',       // New state - transition destination, optional, default to 
 *                                    // current state.
 *          scope: object             // Predicate and action scope, optional, default to 
 *                                    // trans_table_scope or window.
 *        }
 *      ]
 *    },
 *
 *    'state_2' : {
 *      ...
 *    }
 *    ...
 *  }
 *
 *  @param  mixed initial_state Initial state.
 *  @param  object trans_table Transition / output table.
 *  @param  trans_table_scope Transition / output table's methods scope.
 */
Ext.ux.Utils.FSA = function(initial_state, trans_table, trans_table_scope) {
  this.current_state = initial_state;
  this.trans_table = trans_table || {};
  this.trans_table_scope = trans_table_scope || window;
  Ext.ux.Utils.FSA.superclass.constructor.call(this, this.processEvent, this);
};
Ext.extend(Ext.ux.Utils.FSA, Ext.ux.Utils.EventQueue,{
  current_state : null,
  trans_table : null,  
  trans_table_scope : null,
  
  /**
   * Returns current state
   * 
   * @access public
   * @return mixed Current state.
   */
  state : function() {
    return this.current_state;
  },
  
  /**
   * @access public
   */
  processEvent : function(event, data) {
    var transitions = this.currentStateEventTransitions(event);
    if (!transitions) {
      throw "State '" + this.current_state + "' has no transition for event '" + event + "'.";
    }
    for (var i = 0, len = transitions.length; i < len; i++) {
      var transition = transitions[i];

      var predicate = transition.predicate || transition.p || true;
      var action = transition.action || transition.a || Ext.emptyFn;
      var new_state = transition.state || transition.s || this.current_state;
      var scope = transition.scope || this.trans_table_scope;
      
      if (this.computePredicate(predicate, scope, data, event)) {
        this.callAction(action, scope, data, event);
        this.current_state = new_state; 
        return;
      }
    }
    
    throw "State '" + this.current_state + "' has no transition for event '" + event + "' in current context";
  },
  
  /**
   * @access private
   */
  currentStateEventTransitions : function(event) {
    return this.trans_table[this.current_state] ? 
      this.trans_table[this.current_state][event] || false
      :
      false;
  },
  
  /**
   * @access private
   */
  computePredicate : function(predicate, scope, data, event) {
    var result = false; 
    
    switch (Ext.type(predicate)) {
     case 'function':
       result = predicate.call(scope, data, event, this);
       break;
     case 'array':
       result = true;
       for (var i = 0, len = predicate.length; result && (i < len); i++) {
         if (Ext.type(predicate[i]) == 'function') {
           result = predicate[i].call(scope, data, event, this);
         }
         else {
           throw [
             'Predicate: ',
             predicate[i],
             ' is not callable in "',
             this.current_state,
             '" state for event "',
             event
           ].join('');
         }
       }
       break;
     case 'boolean':
       result = predicate;
       break;
     default:
       throw [
         'Predicate: ',
         predicate,
         ' is not callable in "',
         this.current_state,
         '" state for event "',
         event
       ].join('');
    }
    return result;
  },
  
  /**
   * @access private
   */
  callAction : function(action, scope, data, event)
  {
    switch (Ext.type(action)) {
       case 'array':
       for (var i = 0, len = action.length; i < len; i++) {
         if (Ext.type(action[i]) == 'function') {
           action[i].call(scope, data, event, this);
         }
         else {
           throw [
             'Action: ',
             action[i],
             ' is not callable in "',
             this.current_state,
             '" state for event "',
             event
           ].join('');
         }
       }
         break;
     case 'function':
       action.call(scope, data, event, this);
       break;
     default:
       throw [
         'Action: ',
         action,
         ' is not callable in "',
         this.current_state,
         '" state for event "',
         event
       ].join('');
    }
  }
});

// ---------------------------------------------------------------------------------------------- //

/**
 * Ext.ux.UploadDialog namespace.
 */
Ext.namespace('Ext.ux.UploadDialog');

/**
 * File upload browse button.
 *
 * @class Ext.ux.UploadDialog.BrowseButton
 */ 
Ext.ux.UploadDialog.BrowseButton = Ext.extend(Ext.Button,{
  input_name : 'file',
  
  input_file : null,
  
  original_handler : null,
  
  original_scope : null,
  
  /**
   * @access private
   */
  initComponent : function()
  {
    Ext.ux.UploadDialog.BrowseButton.superclass.initComponent.call(this);
    this.original_handler = this.handler || null;
    this.original_scope = this.scope || window;
    this.handler = null;
    this.scope = null;
  },
  
  /**
   * @access private
   */
  onRender : function(ct, position)
  {
    Ext.ux.UploadDialog.BrowseButton.superclass.onRender.call(this, ct, position);
    this.createInputFile();
  },
  
  /**
   * @access private
   */
  createInputFile : function()
  {
    var button_container = this.el;
        button_container.position('relative');
       this.wrap = this.el.wrap({cls:'tbody'});    
       this.input_file = this.wrap.createChild({
           tag: 'input',
            type: 'file',
            size: 1,
            name: this.input_name || Ext.id(this.el),
            style: "position: absolute; display: block; border: none; cursor: pointer"
        });
        this.input_file.setOpacity(0.0);
    
    var button_box = button_container.getBox();
    this.input_file.setStyle('font-size', (button_box.width * 0.5) + 'px');

    var input_box = this.input_file.getBox();
    var adj = {x: 3, y: 3}
    if (Ext.isIE) {
      adj = {x: 0, y: 3}
    }
    
    this.input_file.setLeft(button_box.width - input_box.width + adj.x + 'px');
    this.input_file.setTop(button_box.height - input_box.height + adj.y + 'px');
    this.input_file.setOpacity(0.0);
        
    if (this.handleMouseEvents) {
      this.input_file.on('mouseover', this.onMouseOver, this);
        this.input_file.on('mousedown', this.onMouseDown, this);
    }
    
    if(this.tooltip){
      if(typeof this.tooltip == 'object'){
        Ext.QuickTips.register(Ext.apply({target: this.input_file}, this.tooltip));
      } 
      else {
        this.input_file.dom[this.tooltipType] = this.tooltip;
        }
      }
    
    this.input_file.on('change', this.onInputFileChange, this);
    this.input_file.on('click', function(e) { e.stopPropagation(); }); 
  },
  
  /**
   * @access public
   */
  detachInputFile : function(no_create)
  {
    var result = this.input_file;
    
    no_create = no_create || false;
    
    if (typeof this.tooltip == 'object') {
      Ext.QuickTips.unregister(this.input_file);
    }
    else {
      this.input_file.dom[this.tooltipType] = null;
    }
    this.input_file.removeAllListeners();
    this.input_file = null;
    
    if (!no_create) {
      this.createInputFile();
    }
    return result;
  },
  
  /**
   * @access public
   */
  getInputFile : function()
  {
    return this.input_file;
  },
  
  /**
   * @access public
   */
  disable : function()
  {
    Ext.ux.UploadDialog.BrowseButton.superclass.disable.call(this);  
    this.input_file.dom.disabled = true;
  },
  
  /**
   * @access public
   */
  enable : function()
  {
    Ext.ux.UploadDialog.BrowseButton.superclass.enable.call(this);
    this.input_file.dom.disabled = false;
  },
  
  /**
   * @access public
   */
  destroy : function()
  {
    var input_file = this.detachInputFile(true);
    input_file.remove();
    input_file = null;
    Ext.ux.UploadDialog.BrowseButton.superclass.destroy.call(this);      
  },
  
  /**
   * @access private
   */
  onInputFileChange : function()
  {
    if (this.original_handler) {
      this.original_handler.call(this.original_scope, this);
    }
  }  
});

/**
 * Toolbar file upload browse button.
 *
 * @class Ext.ux.UploadDialog.TBBrowseButton
 */
Ext.ux.UploadDialog.TBBrowseButton = Ext.extend(Ext.ux.UploadDialog.BrowseButton,{
  hideParent : true
  ,onDestroy : function() {
    Ext.ux.UploadDialog.TBBrowseButton.superclass.onDestroy.call(this);
    if(this.container) {
      this.container.remove();
      }
  }
});

/**
 * Record type for dialogs grid.
 *
 * @class Ext.ux.UploadDialog.FileRecord 
 */
Ext.ux.UploadDialog.FileRecord = Ext.data.Record.create([
  {name: 'filename'},
  {name: 'state', type: 'int'},
  {name: 'note'},
  {name: 'input_element'}
]);

Ext.ux.UploadDialog.FileRecord.STATE_QUEUE = 0;
Ext.ux.UploadDialog.FileRecord.STATE_FINISHED = 1;
Ext.ux.UploadDialog.FileRecord.STATE_FAILED = 2;
Ext.ux.UploadDialog.FileRecord.STATE_PROCESSING = 3;

/**
 * Dialog class.
 *
 * @class Ext.ux.UploadDialog.Dialog
 */
Ext.ux.UploadDialog.Dialog = function(config) {
  var default_config = {
    border: false,
    width: 450,
    height: 350,
    minWidth: 450,
    minHeight: 350,
    plain: true,
    constrainHeader: true,
    draggable: true,
    closable: true,
    maximizable: false,
    minimizable: false,
    resizable: true,
    
        layout:'fit',
        region:'center',
    autoDestroy: true,
    closeAction: 'hide',
    title: this.i18n.title,
    cls: 'ext-ux-uploaddialog-dialog',
    // --------
    url: '',
    base_params: {},
    permitted_extensions: [],
    reset_on_hide: true,
    allow_close_on_upload: false,
    upload_autostart: false,
    Make_Reload: false,
    post_var_name: 'file'
  };
  config = Ext.applyIf(config || {}, default_config);
  config.layout = 'absolute';
  
  Ext.ux.UploadDialog.Dialog.superclass.constructor.call(this, config);
};

Ext.extend(Ext.ux.UploadDialog.Dialog, Ext.Window,{
  fsa : null,
  
  state_tpl : null,
  
  form : null,
  
  grid_panel : null,
  
  progress_bar : null,
  
  is_uploading : false,
  
  initial_queued_count : 0,
  
  upload_frame : null,
  
  /**
   * @access private
   */
  //--------------------------------------------------------------------------------------------- //
  initComponent : function() {
    Ext.ux.UploadDialog.Dialog.superclass.initComponent.call(this);
    
    // Setting automata protocol
    var tt = {
      // --------------
      'created' : {
      // --------------
        'window-render' : [
          {
            action: [this.createForm, this.createProgressBar, this.createGrid],
            state: 'rendering'
          }
        ],
        'destroy' : [
          {
            action: this.flushEventQueue,
            state: 'destroyed'
          }
        ]
      },
      // --------------
      'rendering' : {
      // --------------
        'grid-render' : [
          {
            action: [this.fillToolbar, this.updateToolbar],
            state: 'ready'
          }
        ],
        'destroy' : [
          {
            action: this.flushEventQueue,
            state: 'destroyed'
          }
        ]
      },
      // --------------
      'ready' : {
      // --------------
        'file-selected' : [
          {
            predicate: [this.fireFileTestEvent, this.isPermittedFile],
            action: this.addFileToUploadQueue,
            state: 'adding-file'
          },
          {
            // If file is not permitted then do nothing.
          }
        ],
        'grid-selection-change' : [
          {
            action: this.updateToolbar
          }
        ],
        'remove-files' : [
          {
            action: [this.removeFiles, this.fireFileRemoveEvent]
          }
        ],
        'reset-queue' : [
          {
            action: [this.resetQueue, this.fireResetQueueEvent]
          }
        ],
        'start-upload' : [
          {
            predicate: this.hasUnuploadedFiles,
            action: [
              this.setUploadingFlag, this.saveInitialQueuedCount, this.updateToolbar, 
              this.updateProgressBar, this.prepareNextUploadTask, this.fireUploadStartEvent
            ],
            state: 'uploading'
          },
          {
            // Has nothing to upload, do nothing.
          }
        ],
        'stop-upload' : [
          {
            // We are not uploading, do nothing. Can be posted by user only at this state. 
          }
        ],
        'hide' : [
          {
            predicate: [this.isNotEmptyQueue, this.getResetOnHide],
            action: [this.resetQueue, this.fireResetQueueEvent]
          },
          {
            // Do nothing
          }
        ],
        'destroy' : [
          {
            action: this.flushEventQueue,
            state: 'destroyed'
          }
        ]
      },
      // --------------
      'adding-file' : {
      // --------------
        'file-added' : [
          {
            predicate: this.isUploading,
            action: [this.incInitialQueuedCount, this.updateProgressBar, this.fireFileAddEvent],
            state: 'uploading' 
          },
          {
            predicate: this.getUploadAutostart,
            action: [this.startUpload, this.fireFileAddEvent],
            state: 'ready'
          },
          {
            action: [this.updateToolbar, this.fireFileAddEvent],
            state: 'ready'
          }
        ]
      },
      // --------------
      'uploading' : {
      // --------------
        'file-selected' : [
          {
            predicate: [this.fireFileTestEvent, this.isPermittedFile],
            action: this.addFileToUploadQueue,
            state: 'adding-file'
          },
          {
            // If file is not permitted then do nothing.
          }
        ],
        'grid-selection-change' : [
          {
            // Do nothing.
          }
        ],
        'start-upload' : [
          {
            // Can be posted only by user in this state. 
          }
        ],
        'stop-upload' : [
          {
            predicate: this.hasUnuploadedFiles,
            action: [
              this.resetUploadingFlag, this.abortUpload, this.updateToolbar, 
              this.updateProgressBar, this.fireUploadStopEvent
            ],
            state: 'ready'
          },
          {
            action: [
              this.resetUploadingFlag, this.abortUpload, this.updateToolbar, 
              this.updateProgressBar, this.fireUploadStopEvent, this.fireUploadCompleteEvent
            ],
            state: 'ready'
          }
        ],
        'file-upload-start' : [
          {
            action: [this.uploadFile, this.findUploadFrame, this.fireFileUploadStartEvent]
          }
        ],
        'file-upload-success' : [
          {
            predicate: this.hasUnuploadedFiles,
            action: [
              this.resetUploadFrame, this.updateRecordState, this.updateProgressBar, 
              this.prepareNextUploadTask, this.fireUploadSuccessEvent
            ]
          },
          {
            action: [
              this.resetUploadFrame, this.resetUploadingFlag, this.updateRecordState, 
              this.updateToolbar, this.updateProgressBar, this.fireUploadSuccessEvent, 
              this.fireUploadCompleteEvent
            ],
            state: 'ready'
          }
        ],
        'file-upload-error' : [
          {
            predicate: this.hasUnuploadedFiles,
            action: [
              this.resetUploadFrame, this.updateRecordState, this.updateProgressBar, 
              this.prepareNextUploadTask, this.fireUploadErrorEvent
            ]
          },
          {
            action: [
              this.resetUploadFrame, this.resetUploadingFlag, this.updateRecordState, 
              this.updateToolbar, this.updateProgressBar, this.fireUploadErrorEvent, 
              this.fireUploadCompleteEvent
            ],
            state: 'ready'
          }
        ],
        'file-upload-failed' : [
          {
            predicate: this.hasUnuploadedFiles,
            action: [
              this.resetUploadFrame, this.updateRecordState, this.updateProgressBar, 
              this.prepareNextUploadTask, this.fireUploadFailedEvent
            ]
          },
          {
            action: [
              this.resetUploadFrame, this.resetUploadingFlag, this.updateRecordState, 
              this.updateToolbar, this.updateProgressBar, this.fireUploadFailedEvent, 
              this.fireUploadCompleteEvent
            ],
            state: 'ready'
          }
        ],
        'hide' : [
          {
            predicate: this.getResetOnHide,
            action: [this.stopUpload, this.repostHide]
          },
          {
            // Do nothing.
          }
        ],
        'destroy' : [
          {
            predicate: this.hasUnuploadedFiles,
            action: [
              this.resetUploadingFlag, this.abortUpload,
              this.fireUploadStopEvent, this.flushEventQueue
            ],
            state: 'destroyed'
          },
          {
            action: [
              this.resetUploadingFlag, this.abortUpload,
              this.fireUploadStopEvent, this.fireUploadCompleteEvent, this.flushEventQueue
            ], 
            state: 'destroyed'
          }
        ]
      },
      // --------------
      'destroyed' : {
      // --------------
      }
    };
    this.fsa = new Ext.ux.Utils.FSA('created', tt, this);
    
    // Registering dialog events.
    this.addEvents({
      'filetest': true
      ,'fileadd' : true
      ,'fileremove' : true
      ,'resetqueue' : true
      ,'uploadsuccess' : true
      ,'uploaderror' : true
      ,'uploadfailed' : true
      ,'uploadstart' : true
      ,'uploadstop' : true
      ,'uploadcomplete' : true
      ,'fileuploadstart' : true
    });
    
    // Attaching to window events.
    this.on('render', this.onWindowRender, this);
    this.on('beforehide', this.onWindowBeforeHide, this);
    this.on('hide', this.onWindowHide, this);
    this.on('destroy', this.onWindowDestroy, this);
    
    // Compiling state template.
    this.state_tpl = new Ext.Template(
      "<div class='ext-ux-uploaddialog-state ext-ux-uploaddialog-state-{state}'> </div>"
    ).compile();
  },
  
  createForm : function()
  {
    this.form = Ext.DomHelper.append(this.body, {
      tag: 'form',
      method: 'post',
      action: this.url,
      style: 'position: absolute; left: -100px; top: -100px; width: 100px; height: 100px; clear: both;'
    });
  },
  
  createProgressBar : function()
  {
    this.progress_bar = this.add(
      new Ext.ProgressBar({
        x: 0,
        y: 0,
        anchor: '0',
        value: 0.0,
        text: this.i18n.progress_waiting_text
      })
    );
  },
  
  createGrid : function()
  {
    var store = new Ext.data.Store({
      proxy: new Ext.data.MemoryProxy([]),
      reader: new Ext.data.JsonReader({}, Ext.ux.UploadDialog.FileRecord),
      sortInfo: {field: 'state', direction: 'DESC'},
      pruneModifiedRecords: true
    });
    
    var cm = new Ext.grid.ColumnModel([
      {
        header: this.i18n.state_col_title,
        width: this.i18n.state_col_width,
        resizable: false,
        dataIndex: 'state',
        sortable: true,
        renderer: this.renderStateCell.createDelegate(this)
      },
      {
        header: this.i18n.filename_col_title,
        width: this.i18n.filename_col_width,
        dataIndex: 'filename',
        sortable: true,
        renderer: this.renderFilenameCell.createDelegate(this)
      },
      {
        header: this.i18n.note_col_title,
        width: this.i18n.note_col_width, 
        dataIndex: 'note',
        sortable: true,
        renderer: this.renderNoteCell.createDelegate(this)
      }
    ]);
      this.grid_panel = new Ext.grid.GridPanel({
      ds: store,
      cm: cm,
        layout:'fit',
        height: this.height-100,
        region:'center',
      x: 0,
      y: 22,
      border: true,
      
        viewConfig: {
        autoFill: true,
          forceFit: true
        },
      
      bbar : new Ext.Toolbar()
    });
    this.grid_panel.on('render', this.onGridRender, this);
    
    this.add(this.grid_panel);
    
    this.grid_panel.getSelectionModel().on('selectionchange', this.onGridSelectionChange, this);
  },
  
  fillToolbar : function()
  {
    var tb = this.grid_panel.getBottomToolbar();
    tb.x_buttons = {}
    
    tb.x_buttons.add = tb.addItem(new Ext.ux.UploadDialog.TBBrowseButton({
      input_name: this.post_var_name,
      text: this.i18n.add_btn_text,
      tooltip: this.i18n.add_btn_tip,
      iconCls: 'ext-ux-uploaddialog-addbtn',
      handler: this.onAddButtonFileSelected,
      scope: this
    }));
//    
    tb.x_buttons.remove = tb.addButton({
      text: this.i18n.remove_btn_text,
      tooltip: this.i18n.remove_btn_tip,
      iconCls: 'ext-ux-uploaddialog-removebtn',
      handler: this.onRemoveButtonClick,
      scope: this
    });
//    
    tb.x_buttons.reset = tb.addButton({
      text: this.i18n.reset_btn_text,
      tooltip: this.i18n.reset_btn_tip,
      iconCls: 'ext-ux-uploaddialog-resetbtn',
      handler: this.onResetButtonClick,
      scope: this
    });
    tb.add('-');
    tb.x_buttons.upload = tb.addButton({
      text: this.i18n.upload_btn_start_text,
      tooltip: this.i18n.upload_btn_start_tip,
      iconCls: 'ext-ux-uploaddialog-uploadstartbtn',
      handler: this.onUploadButtonClick,
      scope: this
    });
    tb.add('-');
    tb.x_buttons.close = tb.addButton({
      text: this.i18n.close_btn_text,
      tooltip: this.i18n.close_btn_tip,
      handler: this.onCloseButtonClick,
      scope: this
    });
  },
  
  renderStateCell : function(data, cell, record, row_index, column_index, store)
  {
    return this.state_tpl.apply({state: data});
  },
  
  renderFilenameCell : function(data, cell, record, row_index, column_index, store)
  {
    var view = this.grid_panel.getView();
    var f = function() {
      try {
        Ext.fly(
          view.getCell(row_index, column_index)
        ).child('.x-grid3-cell-inner').dom['qtip'] = data;
      }
      catch (e)
      {}
    }
    f.defer(1000);
    return data;
  },
  
  renderNoteCell : function(data, cell, record, row_index, column_index, store)
  {
    var view = this.grid_panel.getView();
    var f = function() {
      try {
        Ext.fly(
          view.getCell(row_index, column_index)
        ).child('.x-grid3-cell-inner').dom['qtip'] = data;
      }
      catch (e)
      {}
      }
    f.defer(1000);
    return data;
  },
  
  getFileExtension : function(filename)
  {
    var result = null;
    var parts = filename.split('.');
    if (parts.length > 1) {
      result = parts.pop();
    }
    return result;
  },
  
  isPermittedFileType : function(filename)
  {
    var result = true;
    if (this.permitted_extensions.length > 0) {
      result = this.permitted_extensions.indexOf(this.getFileExtension(filename)) != -1;
    }
    return result;
  },

  isPermittedFile : function(browse_btn)
  {
    var result = false;
    var filename = browse_btn.getInputFile().dom.value;
    
    if (this.isPermittedFileType(filename)) {
      result = true;
    }
    else {
      Ext.Msg.alert(
        this.i18n.error_msgbox_title, 
        String.format(
          this.i18n.err_file_type_not_permitted,
          filename,
          this.permitted_extensions.join(this.i18n.permitted_extensions_join_str)
        )
      );
      result = false;
    }
    
    return result;
  },
  
  fireFileTestEvent : function(browse_btn)
  {
    return this.fireEvent('filetest', this, browse_btn.getInputFile().dom.value) !== false;
  },
  
  addFileToUploadQueue : function(browse_btn)
  {
    var input_file = browse_btn.detachInputFile();
    
    input_file.appendTo(this.form);
    input_file.setStyle('width', '100px');
    input_file.dom.disabled = true;
    
    var store = this.grid_panel.getStore();
    var fileApi = input_file.dom.files;
    var filename = (typeof fileApi != 'undefined') ? fileApi[0].name : input_file.dom.value.replace("C:\\fakepath\\", "");
    store.add(new Ext.ux.UploadDialog.FileRecord({
          state: Ext.ux.UploadDialog.FileRecord.STATE_QUEUE
          ,filename: filename
          ,note: this.i18n.note_queued_to_upload
          ,input_element: input_file
    }));
    this.fsa.postEvent('file-added', input_file.dom.value);
  },
  
  fireFileAddEvent : function(filename)
  {
    this.fireEvent('fileadd', this, filename);
  },
  
  updateProgressBar : function()
  {
    if (this.is_uploading) {
      var queued = this.getQueuedCount(true);
      var value = 1 - queued / this.initial_queued_count;
      this.progress_bar.updateProgress(value,String.format(this.i18n.progress_uploading_text,this.initial_queued_count - queued,this.initial_queued_count));
    }
    else {
      this.progress_bar.updateProgress(0, this.i18n.progress_waiting_text);
    }
  },
  
  updateToolbar : function() {
    var tb = this.grid_panel.getBottomToolbar();
    if (this.is_uploading) {
      tb.x_buttons.remove.disable();
      tb.x_buttons.reset.disable();
      tb.x_buttons.upload.enable();
      if (!this.getAllowCloseOnUpload()) {
        tb.x_buttons.close.disable();
      }
      tb.x_buttons.upload.setIconClass('ext-ux-uploaddialog-uploadstopbtn');
      tb.x_buttons.upload.setText(this.i18n.upload_btn_stop_text);
      tb.x_buttons.upload.getEl()
        .child(tb.x_buttons.upload.buttonSelector)
        .dom[tb.x_buttons.upload.tooltipType] = this.i18n.upload_btn_stop_tip;
    }
    else {
      tb.x_buttons.remove.enable();
      tb.x_buttons.reset.enable();
      tb.x_buttons.close.enable();
      tb.x_buttons.upload.setIconClass('ext-ux-uploaddialog-uploadstartbtn');
      tb.x_buttons.upload.setText(this.i18n.upload_btn_start_text);
      
      if (this.getQueuedCount() > 0) {
        tb.x_buttons.upload.enable();
      }
      else {
        tb.x_buttons.upload.disable();      
      }
      
      if (this.grid_panel.getSelectionModel().hasSelection()) {
        tb.x_buttons.remove.enable();
      }
      else {
        tb.x_buttons.remove.disable();
      }
      
      if (this.grid_panel.getStore().getCount() > 0) {
        tb.x_buttons.reset.enable();
      }
      else {
        tb.x_buttons.reset.disable();
      }
    }
  },
  
  saveInitialQueuedCount : function()
  {
    this.initial_queued_count = this.getQueuedCount();
  },
  
  incInitialQueuedCount : function()
  {
    this.initial_queued_count++;
  },
  
  setUploadingFlag : function()
  {
    this.is_uploading = true;
  }, 
  
  resetUploadingFlag : function()
  {
    this.is_uploading = false;
  },

  prepareNextUploadTask : function()
  {
    // Searching for first unuploaded file.
    var store = this.grid_panel.getStore();
    var record = null;
    
    store.each(function(r) {
      if (!record && r.get('state') == Ext.ux.UploadDialog.FileRecord.STATE_QUEUE) {
        record = r;
      }
      else {
        r.get('input_element').dom.disabled = true;
      }
    });
    
    record.get('input_element').dom.disabled = false;
    record.set('state', Ext.ux.UploadDialog.FileRecord.STATE_PROCESSING);
    record.set('note', this.i18n.note_processing);
    record.commit();
    
    this.fsa.postEvent('file-upload-start', record);
  },
   
  fireUploadStartEvent : function()
  {
    this.fireEvent('uploadstart', this);
  },
  
  removeFiles : function(file_records)
  {
    var store = this.grid_panel.getStore();
    for (var i = 0, len = file_records.length; i < len; i++) {
      var r = file_records[i];
      r.get('input_element').remove();
      store.remove(r);
    }
  },
  
  fireFileRemoveEvent : function(file_records)
  {
    for (var i = 0, len = file_records.length; i < len; i++) {
      this.fireEvent('fileremove', this, file_records[i].get('filename'));
    }
  },
  
  resetQueue : function() {
    var store = this.grid_panel.getStore();
    store.each(function(r) {
        r.get('input_element').remove();
    });
    store.removeAll();
  },
  
  fireResetQueueEvent : function() {
    this.fireEvent('resetqueue', this);
  }
  
  ,uploadFile : function(record) {
    Ext.Ajax.request({
      url: this.url
      ,params: this.base_params || this.baseParams || this.params
      ,method: 'POST'
      ,form: this.form
      ,isUpload: true
      ,success: this.onAjaxSuccess
      ,failure: this.onAjaxFailure
      ,scope: this
      ,record: record
    });
  },
   
  fireFileUploadStartEvent : function(record)
  {
    this.fireEvent('fileuploadstart', this, record.get('filename'));
  },
  
  updateRecordState : function(data)
  {
    if ('success' in data.response && data.response.success) {
      data.record.set('state', Ext.ux.UploadDialog.FileRecord.STATE_FINISHED);
      data.record.set(
        'note', data.response.message || data.response.error || this.i18n.note_upload_success
      );
    }
    else {
      data.record.set('state', Ext.ux.UploadDialog.FileRecord.STATE_FAILED);
      data.record.set(
        'note', data.response.message || data.response.error || this.i18n.note_upload_error
      );
    }
    
    data.record.commit();
  },
  
  fireUploadSuccessEvent : function(data)
  {
    this.fireEvent('uploadsuccess', this, data.record.get('filename'), data.response);
  },
  
  fireUploadErrorEvent : function(data)
  {
    this.fireEvent('uploaderror', this, data.record.get('filename'), data.response);
  },
  
  fireUploadFailedEvent : function(data)
  {
    this.fireEvent('uploadfailed', this, data.record.get('filename'));
  },
  
  fireUploadCompleteEvent : function()
  {
    this.fireEvent('uploadcomplete', this);
  },
  
  findUploadFrame : function() 
  {
    this.upload_frame = Ext.getBody().child('iframe.x-hidden:last');
  },
  
  resetUploadFrame : function()
  {
    this.upload_frame = null;
  },
  
  removeUploadFrame : function()
  {
    if (this.upload_frame) {
      this.upload_frame.removeAllListeners();
      this.upload_frame.dom.src = 'about:blank';
      this.upload_frame.remove();
    }
    this.upload_frame = null;
  },
  
  abortUpload : function()
  {
    this.removeUploadFrame();
    
    var store = this.grid_panel.getStore();
    var record = null;
    store.each(function(r) {
      if (r.get('state') == Ext.ux.UploadDialog.FileRecord.STATE_PROCESSING) {
        record = r;
        return false;
      }
    });
    
    record.set('state', Ext.ux.UploadDialog.FileRecord.STATE_FAILED);
    record.set('note', this.i18n.note_aborted);
    record.commit();
  },
  
  fireUploadStopEvent : function()
  {
    this.fireEvent('uploadstop', this);
  },
  
  repostHide : function()
  {
    this.fsa.postEvent('hide');
  },
  
  flushEventQueue : function()
  {
    this.fsa.flushEventQueue();
  },
  
  /**
   * @access private
   */
  // -------------------------------------------------------------------------------------------- //
  onWindowRender : function()
  {
    this.fsa.postEvent('window-render');
  },
  
  onWindowBeforeHide : function()
  {
    return this.isUploading() ? this.getAllowCloseOnUpload() : true;
  },
  
  onWindowHide : function()
  {
    this.fsa.postEvent('hide');
  },
  
  onWindowDestroy : function()
  {
    this.fsa.postEvent('destroy');
  },
  
  onGridRender : function()
  {
    this.fsa.postEvent('grid-render');
  },
  
  onGridSelectionChange : function()
  {
    this.fsa.postEvent('grid-selection-change');
  },
  
  onAddButtonFileSelected : function(btn)
  {
    this.fsa.postEvent('file-selected', btn);
  },
  
  onUploadButtonClick : function()
  {
    if (this.is_uploading) {
      this.fsa.postEvent('stop-upload');
    }
    else {
      this.fsa.postEvent('start-upload');
    }
  },
  
  onRemoveButtonClick : function()
  {
    var selections = this.grid_panel.getSelectionModel().getSelections();
    this.fsa.postEvent('remove-files', selections);
  },
  
  onResetButtonClick : function()
  {
    this.fsa.postEvent('reset-queue');
  },
  
  onCloseButtonClick : function()
  {
    this[this.closeAction].call(this);
    if(this.Make_Reload == true){
        document.location.reload();
   }
  },
  
  onAjaxSuccess : function(response, options) {
    var json_response = {
      'success' : false,
      'error' : this.i18n.note_upload_error
    }
    try { 
        var rt = response.responseText;
        var filter = rt.match(/^<pre>((?:.|\n)*)<\/pre>$/i);
        if (filter) {
            rt = filter[1];
        }
        json_response = Ext.util.JSON.decode(rt); 
    } 
    catch (e) {}
    
    var data = {
      record: options.record,
      response: json_response
    }
    
    if ('success' in json_response && json_response.success) {
      this.fsa.postEvent('file-upload-success', data);
    }
    else {
      this.fsa.postEvent('file-upload-error', data);
    }
  },
  
  onAjaxFailure : function(response, options)
  {
    var data = {
      record : options.record,
      response : {
        'success' : false,
        'error' : this.i18n.note_upload_failed
      }
    }

    this.fsa.postEvent('file-upload-failed', data);
  },
  
  /**
   * @access public
   */
  // -------------------------------------------------------------------------------------------- //
  startUpload : function()
  {
    this.fsa.postEvent('start-upload');
  },
  
  stopUpload : function()
  {
    this.fsa.postEvent('stop-upload');
  },
  
  getUrl : function()
  {
    return this.url;
  },
  
  setUrl : function(url)
  {
    this.url = url;
  },
  
  getBaseParams : function()
  {
    return this.base_params;
  },
  
  setBaseParams : function(params)
  {
    this.base_params = params;
  },
  
  getUploadAutostart : function()
  {
    return this.upload_autostart;
  },
  
  setUploadAutostart : function(value)
  {
    this.upload_autostart = value;
  },
  
  ///////////EIGENE ERWEITERUNG RELOAD EXT//////////////////////
  
  getMakeReload : function()
  {
    return this.Make_Reload;
  },
  
  setMakeReload : function(value)
  {
    this.Make_Reload = value;
  }
  
  ///////////EIGENE ERWEITERUNG RELOAD EXT//////////////////////
   
  ,getAllowCloseOnUpload : function() {
    return this.allow_close_on_upload;
  }
  
  ,setAllowCloseOnUpload : function(value) {
    this.allow_close_on_upload;
  }
  
  ,getResetOnHide : function() {
    return this.reset_on_hide;
  }
  
  ,setResetOnHide : function(value) {
    this.reset_on_hide = value;
  }
  
  ,getPermittedExtensions : function() {
    return this.permitted_extensions;
  }
  
  ,setPermittedExtensions : function(value) {
    this.permitted_extensions = value;
  }
  
  ,isUploading : function() {
    return this.is_uploading;
  }
  
  ,isNotEmptyQueue : function() {
    return this.grid_panel.getStore().getCount() > 0;
  }
  
  ,getQueuedCount : function(count_processing) {
    var count = 0;
    var store = this.grid_panel.getStore();
    store.each(function(r) {
      if (r.get('state') == Ext.ux.UploadDialog.FileRecord.STATE_QUEUE) {
        count++;
      }
      if (count_processing && r.get('state') == Ext.ux.UploadDialog.FileRecord.STATE_PROCESSING) {
        count++;
      }
    });
    return count;
  }
  
  ,hasUnuploadedFiles : function() {
    return this.getQueuedCount() > 0;
  }
});

// ---------------------------------------------------------------------------------------------- //

var p = Ext.ux.UploadDialog.Dialog.prototype;
p.i18n = {
  title: _('upload_files')
  ,state_col_title: _('upf_state')
  ,state_col_width: 70
  ,filename_col_title: _('upf_filename')
  ,filename_col_width: 230
  ,note_col_title: _('upf_note')
  ,note_col_width: 150
  ,add_btn_text: _('upf_add')
  ,add_btn_tip: _('upf_add_desc')
  ,remove_btn_text: _('upf_remove')
  ,remove_btn_tip: _('upf_remove_desc')
  ,reset_btn_text: _('upf_reset')
  ,reset_btn_tip: _('upf_reset_desc')
  ,upload_btn_start_text: _('upf_upload')
  ,upload_btn_start_tip: _('upf_upload_desc')
  ,upload_btn_stop_text: _('upf_abort')
  ,upload_btn_stop_tip: _('upf_abort_desc')
  ,close_btn_text: _('upf_close')
  ,close_btn_tip: _('upf_close_desc')
  ,progress_waiting_text: _('upf_progress_wait')
  ,progress_uploading_text: _('upf_uploading_desc')
  ,error_msgbox_title: _('upf_error')
  ,permitted_extensions_join_str: ','
  ,err_file_type_not_permitted: _('upf_err_filetype')
  ,note_queued_to_upload: _('upf_queued')
  ,note_processing: _('upf_uploading')
  ,note_upload_failed: _('upf_err_failed')
  ,note_upload_success: _('upf_success')
  ,note_upload_error: _('upf_upload_err')
  ,note_aborted: _('upf_aborted')
};

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


MODx.Component = function(config) {
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
        actions: { 'close': 'welcome' }
        ,formpanel: false
        ,id: 'modx-action-buttons'
        ,params: {}
        ,items: []
        ,renderTo: 'modx-container'
    });
    if (config.formpanel) {
        this.setupDirtyButtons(config.formpanel);
    }
    this.checkDirtyBtns = [];
    MODx.toolbar.ActionButtons.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx.toolbar.ActionButtons,Ext.Toolbar,{
    id: ''
    ,buttons: []
    ,options: { a_close: 'welcome' }

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

            if (el.keys) {
                el.keyMap = new Ext.KeyMap(Ext.get(document));
                for (var j = 0; j < el.keys.length; j++) {
                    var key = el.keys[j];
                    Ext.applyIf(key,{
                        scope: this
                        ,stopEvent: true
                        ,fn: function(e) {
                            var b = Ext.getCmp(id);
                            if (b) this.checkConfirm(b,e);
                        }
                    });
                    el.keyMap.addBinding(key);
                }
                el.listeners['destroy'] = {fn:function(btn) {
                    btn.keyMap.disable();
                },scope:this}
            }

            /* add button to toolbar */
            MODx.toolbar.ActionButtons.superclass.add.call(this,el);
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
                });

                Ext.apply(f.baseParams,o.params);

                o.form.on('success',function(r) {
                    if (o.form.clearDirty) o.form.clearDirty();
                    /* allow for success messages */
                    MODx.msg.status({
                        title: _('success')
                        ,message: r.result.message || _('save_successful')
                        ,dontHide: r.result.message != '' ? true : false
                    });
                    Ext.callback(this.redirect,this,[o,itm,r.result],1000);

                    this.resetDirtyButtons(r.result);
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
        } else {
            // if just doing a URL redirect
            var params = itm.params || {};
            Ext.applyIf(params, o.baseParams || {});
            MODx.loadPage('?' + Ext.urlEncode(params));
        }
        return false;
    }

    ,resetDirtyButtons: function(r) {
        for (var i=0;i<this.checkDirtyBtns.length;i=i+1) {
            var btn = this.checkDirtyBtns[i];
            btn.setDisabled(true);
        }
    }

    ,redirect: function(o,itm,res) {
        o = this.config;
        itm.params = itm.params || {};
        Ext.applyIf(itm.params,o.baseParams);
        var url;

        var process = itm.process.substr(itm.process.lastIndexOf('/') + 1);
        if ((process === 'create' || process === 'duplicate' || itm.reload) && res.object.id) {
            itm.params.id = res.object.id;
            if (MODx.request.parent) { itm.params.parent = MODx.request.parent; }
            if (MODx.request.context_key) { itm.params.context_key = MODx.request.context_key; }
            url = Ext.urlEncode(itm.params);
            var action;
            if (o.actions && o.actions.edit) {
                // If an edit action is given, use it (BC)
                action = o.actions.edit;
            } else {
                // Else assume we want the 'update' controller
                action = itm.process.replace('create', 'update');
            }
            MODx.loadPage(action, url);

        } else if (process === 'delete') {
            itm.params.a = o.actions.cancel;
            url = Ext.urlEncode(itm.params);
            MODx.loadPage('?'+url);
        }
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
Ext.reg('modx-actionbuttons',MODx.toolbar.ActionButtons);

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
            if ((f.xtype == 'checkbox' || f.xtype == 'xcheckbox' || f.xtype == 'radio') && flds[i] == 'published') {
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
						+'<tpl if="typeof pnl == \'undefined\'"><span class="text{[values.root ? \' root\' : \'\' ]}">{text}</span></tpl>'
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
	init: function(){
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
		// Automagically the trail root
		if(data.hasOwnProperty('trail')){
			var trail = data.trail;
			trail.unshift(this.root);
		}
		this._updatePanel(data);
	}

	,reset: function(msg){
		if(typeof(this.resetText) == "undefined"){
			this.resetText = this.getResetText(this.root);
		}
		var data = { text : msg ,trail : [this.resetText] };
		this._updatePanel(data);
	}

	,onClick: function(e){
		var target = e.getTarget();
		elm = target.className.split(' ')[0];
		if(elm != "" && elm == 'controlBtn'){
			// Don't use "pnl" shorthand, it make the breadcrumb fail
			var panel = target.className.split(' ')[1];
			Ext.getCmp(panel).activate();
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

MODx.Tabs = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        enableTabScroll: true
        ,layoutOnTabChange: true
        ,plain: true
        ,deferredRender: true
        ,hideMode: 'offsets'
        ,defaults: {
            autoHeight: true
            ,hideMode: 'offsets'
            ,border: true
            ,autoWidth: true
            ,bodyCssClass: 'tab-panel-wrapper'
        }
        ,activeTab: 0
        ,border: false
        ,autoScroll: true
        ,autoHeight: true
        ,cls: 'modx-tabs'
    });
    MODx.Tabs.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx.Tabs,Ext.TabPanel);
Ext.reg('modx-tabs',MODx.Tabs);

MODx.VerticalTabs = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        cls: 'vertical-tabs-panel'
        ,headerCfg: { tag: 'div', cls: 'x-tab-panel-header vertical-tabs-header' }
        ,bwrapCfg: { tag: 'div', cls: 'x-tab-panel-bwrap vertical-tabs-bwrap' }
        ,defaults: {
            bodyCssClass: 'vertical-tabs-body'
            ,autoScroll: true
            ,autoHeight: true
            ,autoWidth: true
            ,layout: 'form'
        }
    });
    MODx.VerticalTabs.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx.VerticalTabs, MODx.Tabs);
Ext.reg('modx-vtabs',MODx.VerticalTabs);
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
            ,handler: function() { config.closeAction !== 'close' ? this.hide() : this.close(); }
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
    this.on('afterrender', function() {
        this.originalHeight = this.el.getHeight();
        this.toolsHeight = this.originalHeight - this.body.getHeight() + 50;
        this.resizeWindow();
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
                    if (close) { this.config.closeAction !== 'close' ? this.hide() : this.close(); }
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
            }
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
});
Ext.reg('modx-window',MODx.Window);

Ext.namespace('MODx.combo');
/* fixes combobox value loading issue */
Ext.override(Ext.form.ComboBox, {
    loaded: false
    ,setValue: Ext.form.ComboBox.prototype.setValue.createSequence(function(v) {
        var a = this.store.find(this.valueField, v);
        if (v && v !== 0 && this.mode == 'remote' && a == -1 && !this.loaded) {
            var p = {};
            p[this.valueField] = v;
            this.loaded = true;
            this.store.load({
                scope: this
                ,params: p
                ,callback: function() {
                    this.setValue(v);
                    this.collapse()
                }
            })
        }
    })
});

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
        ,tries: 0
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
            ,listeners: {
                'loadexception': {fn: function(o,trans,resp) {
                    var status = _('code') + ': ' + resp.status + ' ' + resp.statusText + '<br/>';
                    MODx.msg.alert(_('error'), status + resp.responseText);
                }}
            }
        })
    });
    if (getStore === true) {
       config.store.load();
       return config.store;
    }
    MODx.combo.ComboBox.superclass.constructor.call(this,config);
    this.config = config;
    this.store.on('load', function() {
        // Workaround to let the combobox know the store is loaded (to help hide/display the pagination if required)
        this.loaded = true;
    }, this, {
        single: true
    });
    return this;
};
Ext.extend(MODx.combo.ComboBox,Ext.form.ComboBox, {
    expand : function(){
        if(this.isExpanded() || !this.hasFocus){
            return;
        }

        if (this.mode == 'remote' && !this.loaded && this.tries < 4) {
            // Store not yet loaded, let's wait a little bit
            this.tries += 1;
            Ext.defer(this.expand, 250, this);
            return false;
        }
        this.tries = 0;

        if(this.title || this.pageSize){
            this.assetHeight = 0;
            if(this.title){
                this.assetHeight += this.header.getHeight();
            }
            if(this.pageSize < this.store.getTotalCount()){
                this.assetHeight += this.footer.getHeight();
            } else {
                this.list.setHeight(this.list.getHeight() - this.footer.getHeight());
                this.pageTb.hide();
            }
        }

        if(this.bufferSize){
            this.doResize(this.bufferSize);
            delete this.bufferSize;
        }
        this.list.alignTo.apply(this.list, [this.el].concat(this.listAlign));

        // zindex can change, re-check it and set it if necessary
        this.list.setZIndex(this.getZIndex());
        this.list.show();
        if(Ext.isGecko2){
            this.innerList.setOverflow('auto'); // necessary for FF 2.0/Mac
        }
        this.mon(Ext.getDoc(), {
            scope: this,
            mousewheel: this.collapseIf,
            mousedown: this.collapseIf
        });
        this.fireEvent('expand', this);
    }
});
Ext.reg('modx-combo',MODx.combo.ComboBox);

Ext.util.Format.comboRenderer = function (combo,val) {
    return function (v,md,rec,ri,ci,s) {
        if (!s) return v;
        if (!combo.findRecord) return v;
        var record = combo.findRecord(combo.valueField, v);
        return record ? record.get(combo.displayField) : val;
    }
};

/** @deprecated MODX 2.2 */
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
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/user/getlist'
        }
        ,typeAhead: true
        ,editable: true
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
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/group/getlist'
        }
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
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/role/getlist'
        }
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
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/resourcegroup/getlist'
        }
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
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'context/getlist'
        }
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
        ,fields: ['id','name','permissions']
        ,allowBlank: false
        ,editable: false
        ,pageSize: 20
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/access/policy/getlist'
        }
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
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'element/template/getlist'
        }
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
        ,pageSize: 20
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'element/category/getlist'
            ,showNone: true
        }
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
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/language/getlist'
        }
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
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/charset/getlist'
        }
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
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/rte/getlist'
        }
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
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/role/getlist'
            ,addNone: true
        }
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
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/contenttype/getlist'
        }
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
Ext.extend(MODx.combo.ContentDisposition,MODx.combo.ComboBox);
Ext.reg('modx-combo-content-disposition',MODx.combo.ContentDisposition);

MODx.combo.ClassMap = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'class'
        ,hiddenName: 'class'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/classmap/getlist'
        }
        ,displayField: 'class'
        ,valueField: 'class'
        ,fields: ['class']
        ,editable: false
        ,pageSize: 20
    });
    MODx.combo.ClassMap.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.ClassMap,MODx.combo.ComboBox);
Ext.reg('modx-combo-class-map',MODx.combo.ClassMap);

MODx.combo.ClassDerivatives = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'class'
        ,hiddenName: 'class'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/derivatives/getList'
            ,skip: 'modXMLRPCResource'
            ,'class': 'modResource'
        }
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['id','name']
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        ,listWidth: 300
        ,pageSize: 20
    });
    MODx.combo.ClassDerivatives.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.ClassDerivatives,MODx.combo.ComboBox);
Ext.reg('modx-combo-class-derivatives',MODx.combo.ClassDerivatives);

MODx.combo.Object = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'object'
        ,hiddenName: 'object'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'workspace/builder/getAssocObject'
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
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'workspace/namespace/getlist'
        }
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
       ,source: config.source || 1
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

        //if (this.browser === null) {
            this.browser = MODx.load({
                xtype: 'modx-browser'
                ,closeAction: 'close'
                ,id: Ext.id()
                ,multiple: true
                ,source: this.config.source || 1
                ,hideFiles: this.config.hideFiles || false
                ,rootVisible: this.config.rootVisible || false
                ,allowedFileTypes: this.config.allowedFileTypes || ''
                ,wctx: this.config.wctx || 'web'
                ,openTo: this.config.openTo || ''
                ,rootId: this.config.rootId || '/'
                ,hideSourceCombo: this.config.hideSourceCombo || false
                ,listeners: {
                    'select': {fn: function(data) {
                        this.setValue(data.relativeUrl);
                        this.fireEvent('select',data);
                    },scope:this}
                }
            });
        //}
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
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/country/getlist'
        }
        ,displayField: 'value'
        ,valueField: 'value'
        ,fields: ['value']
        ,editable: true
        ,value: 0
        ,typeAhead: true
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
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'element/propertyset/getlist'
        }
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['id','name']
        ,editable: false
        ,value: 0
        ,pageSize: 20
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
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'element/tv/renders/getOutputs'
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
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'element/tv/renders/getInputs'
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
        ,fields: ['id','controller','namespace']
        ,pageSize: 20
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/action/getlist'
        }
        ,tpl: new Ext.XTemplate('<tpl for="."><div class="x-combo-list-item"><tpl if="namespace">{namespace} - </tpl>{controller}</div></tpl>')
    });
    MODx.combo.Action.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Action,MODx.combo.ComboBox);
Ext.reg('modx-combo-action',MODx.combo.Action);

MODx.combo.Dashboard = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'dashboard'
        ,hiddenName: 'dashboard'
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['id','name','description']
        ,listWidth: 400
        ,pageSize: 20
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/dashboard/getlist'
        }
        ,tpl: new Ext.XTemplate('<tpl for=".">'
            ,'<div class="x-combo-list-item">'
            ,'<h4 class="modx-combo-title">{name}</h4>'
            ,'<p class="modx-combo-desc">{description}</p>'
            ,'</div></tpl>')
    });
    MODx.combo.Dashboard.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Dashboard,MODx.combo.ComboBox);
Ext.reg('modx-combo-dashboard',MODx.combo.Dashboard);

MODx.combo.MediaSource = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'source'
        ,hiddenName: 'source'
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['id','name','description']
        ,listWidth: 400
        ,pageSize: 20
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'source/getlist'
        }
        ,tpl: new Ext.XTemplate('<tpl for=".">'
            ,'<div class="x-combo-list-item">'
            ,'<h4 class="modx-combo-title">{name}</h4>'
            ,'<p class="modx-combo-desc">{description}</p>'
            ,'</div></tpl>')
    });
    MODx.combo.MediaSource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.MediaSource,MODx.combo.ComboBox);
Ext.reg('modx-combo-source',MODx.combo.MediaSource);

MODx.combo.MediaSourceType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'class_key'
        ,hiddenName: 'class_key'
        ,displayField: 'name'
        ,valueField: 'class'
        ,fields: ['id','class','name','description']
        ,listWidth: 400
        ,pageSize: 20
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'source/type/getlist'
        }
        ,tpl: new Ext.XTemplate('<tpl for=".">'
            ,'<div class="x-combo-list-item">'
            ,'<h4 class="modx-combo-title">{name}</h4>'
            ,'<p class="modx-combo-desc">{description}</p>'
            ,'</div></tpl>')
    });
    MODx.combo.MediaSourceType.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.MediaSourceType,MODx.combo.ComboBox);
Ext.reg('modx-combo-source-type',MODx.combo.MediaSourceType);


MODx.combo.Authority = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'authority'
        ,hiddenName: 'authority'
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        ,listWidth: 300
        ,pageSize: 20
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/role/getAuthorityList'
            ,addNone: true
        }
    });
    MODx.combo.Authority.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Authority,MODx.combo.ComboBox);
Ext.reg('modx-combo-authority',MODx.combo.Authority);

MODx.combo.ManagerTheme = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'theme'
        ,hiddenName: 'theme'
        ,displayField: 'theme'
        ,valueField: 'theme'
        ,fields: ['theme']
        ,pageSize: 0
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'workspace/theme/getlist'
        }
        ,typeAhead: false
        ,editable: false
    });
    MODx.combo.ManagerTheme.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.ManagerTheme,MODx.combo.ComboBox);
Ext.reg('modx-combo-manager-theme',MODx.combo.ManagerTheme);
