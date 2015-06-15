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
            iconMarkup = '<i class="icon'+(a.icon ? " x-tree-node-inline-icon" : "")+(a.iconCls ? " "+a.iconCls : "")+'" unselectable="on"></i>',
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
    ,getChildIndent : function(){
        if(!this.childIndent){
            var buf = [],
                p = this.node;
            while(p){
                if((!p.isRoot || (p.isRoot && p.ownerTree.rootVisible)) && !p.attributes.pseudoroot){
                    if(!p.isLast()) {
                        buf.unshift('<img alt="" src="'+this.emptyIcon+'" class="x-tree-elbow-line" />');
                    } else {
                        buf.unshift('<img alt="" src="'+this.emptyIcon+'" class="x-tree-icon" />');
                    }
                }
                p = p.parentNode;
            }
            this.childIndent = buf.join("");
        }
        return this.childIndent;
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
