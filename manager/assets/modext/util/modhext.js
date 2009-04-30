/**
 * modHExt extensions
 * 
 * Generates Ext-styled forms through HTML namespaced attributes
 */
var _hourfields,_minfields,_ampmfields,_datefields,_textfields,_comboboxes,_textareas,_radios,_checkboxes,_hiddens;
Ext.onReady(function() {
	// auto-render form elements to Ext
	var dh = Ext.DomHelper;	
	
	// hourfields
	_hourfields = {};
	var hourStore = new Ext.data.SimpleStore({
		fields: ['hour']
		,data: [[1],[2],[3],[4],[5],[6],[7],[8],[9],[10],[11],[12]]
	});
	var els = Ext.get(Ext.query('select.hourfield'));
	els.each(function(el){
		var span = dh.insertBefore(el,{tag:'span'});
		var d = new Ext.form.ComboBox({
			el: el.dom
			,store: hourStore
			,displayField: 'hour'
			,mode: 'local'
			,triggerAction: 'all'
			,value: el.dom.value || 1
			,forceSelection: true
			,selectOnFocus: true
			,editable: false
			,hiddenName: el.dom.name
			,typeAhead: false
			,width: 50
			,validateOnBlur: false
			,transform: el.dom.id
		});
		var oc = el.dom.onchange;
		if (oc && oc !== undefined) { d.on('change',oc); }
		
		d.render(span);
		_hourfields[el.dom.id] = d;
	});	
	
	// minutefields
	_minfields = {};
	var minStore = new Ext.data.SimpleStore({
		fields: ['min']
		,data: [['00'],['15'],['30'],['45']]
	});
	els = Ext.get(Ext.query('select.minutefield'));
	els.each(function(el){
		var span = dh.insertBefore(el,{tag:'span'});
		var d = new Ext.form.ComboBox({
			el: el.dom
			,store: minStore
			,displayField: 'min'
			,mode: 'local'
			,triggerAction: 'all'
			,rowHeight: false
			,value: el.dom.value || '00'
			,forceSelection: true
			,editable: false
			,transform: el.dom.id
			,hiddenName: el.dom.name
			,typeAhead: false
			,width: 50
		});
		var oc = el.dom.onchange;
		if (oc && oc !== undefined) { d.on('change',oc); }
		
		d.render(span);
		_minfields[el.dom.id] = d;
	});	
	
	// ampmfields
	_ampmfields = {};
	var ampmStore = new Ext.data.SimpleStore({
		fields: ['min']
		,data: [['am'],['pm']]
	});
	els = Ext.get(Ext.query('select.ampmfield'));
	els.each(function(el){
		var span = dh.insertBefore(el,{tag:'span'});
		var d = new Ext.form.ComboBox({
			el: el.dom
			,store: ampmStore
			,displayField: 'min'
			,mode: 'local'
			,triggerAction: 'all'
			,rowHeight: false
			,value: el.dom.value || 'am'
			,forceSelection: true
			,editable: false
			,transform: el.dom.id
			,hiddenName: el.dom.name
			,typeAhead: false
			,width: 50
		});
		var oc = el.dom.onchange;
		if (oc && oc !== undefined) { d.on('change',oc); }
		
		d.render(span);
		_ampmfields[el.dom.id] = d;
	});	
	
	// textfields
	_textfields = {};
	els = Ext.get(Ext.query('input.textfield'));
	els.each(function(el){
		var span = dh.insertBefore(el,{tag:'span'});
		var allb = el.getAttributeNS('modx','allowblank');
		var d = new Ext.form.TextField({
			width: el.getAttributeNS('modx','width') || 300
			,maxLength: el.getAttributeNS('modx','maxlength')
			,inputType: el.getAttributeNS('modx','inputtype') || 'text'
			,allowBlank: allb && allb === false ? false : true
			,applyTo: el.dom.id
		});
		var oc = el.dom.onchange;
		if (oc && oc !== undefined) { d.on('change',oc); }
		
		_textfields[el.dom.id] = d;
	});
	
	// comboboxes
	_comboboxes = {};
	els = Ext.get(Ext.query('select.combobox'));
	els.each(function(el){
		var span = dh.insertBefore(el,{tag:'span'});
		var ed = el.getAttributeNS('modx','editable');
		var fs = el.getAttributeNS('modx','forceselection');
		var d = new Ext.form.ComboBox({
			el: el.dom
			,value: el.dom.value
			,forceSelection: fs && fs === false ? false : true
			,typeAhead: el.getAttributeNS('modx','typeahead') ? true : false
			,editable: ed && ed === false ? false : true
			,triggerAction: 'all'
			,transform: el.dom.id
			,hiddenName: el.dom.name
			,width: el.getAttributeNS('modx','width')
            ,listWidth: el.getAttributeNS('modx','listwidth') || 300
		});
		var oc = el.dom.onchange;
		if (oc && oc !== undefined) {
			d.on('blur',oc);
		}
		
		d.render(span);
		_comboboxes[el.dom.id] = d;
	});	
	
	// textareas
	_textareas = {};
	els = Ext.get(Ext.query('textarea.textarea'));
	els.each(function(el){
		var span = dh.insertBefore(el,{tag:'span'});
		var d = new Ext.form.TextArea({
			grow: el.getAttributeNS('modx','grow') ? true : false
			,width: el.getAttributeNS('modx','width')
			,applyTo: el.dom.id
		});
		var oc = el.dom.onchange;
		if (oc && oc !== undefined) { d.on('change',oc); }
		
		_textareas[el.dom.id] = d;
	});
	
	// datefields
	_datefields = {};
	els = Ext.get(Ext.query('input.datefield'));
	els.each(function(el){
		var span = dh.insertBefore(el, {tag:'span'});
		var f = el.getAttributeNS('modx','format');
		var d = new Ext.form.DateField({
			el: el.dom
			,value: el.dom.value
			,allowBlank: el.getAttributeNS('modx','allowblank') ? true : false
			,format: f && f !== undefined ? f : 'd-m-Y H:i:s'
		});
		var oc = el.dom.onchange;
		if (oc && oc !== undefined) { d.on('change',oc); }
		
		d.render(span);
		_datefields[el.dom.id] = d;
	});
	
	
	// radios
	_radios = {};
	els = Ext.get(Ext.query('input.radio'));
	els.each(function(el){
		var d = new Ext.form.Radio({
			name: el.dom.name
			,value: el.dom.value
			,boxLabel: el.dom.title
			,checked: el.dom.checked
			,disabled: el.dom.disabled ? true : false
			,inputType: 'radio'
			,applyTo: el.dom.id
		});
		var oc = el.dom.onchange;
		if (oc && oc !== undefined) { d.on('change',oc); }
		
		_radios[el.dom.id] = d;
	});
	
	// checkboxes
	_checkboxes = {};
	els = Ext.get(Ext.query('input.checkbox'));
	els.each(function(el){
		var d = new Ext.form.Radio({
			name: el.dom.name
			,value: el.dom.value
			,boxLabel: el.dom.title
			,checked: el.dom.checked
			,disabled: el.dom.disabled ? true : false
			,inputType: 'checkbox'
			,applyTo: el.dom.id
		});
		var oc = el.dom.onchange;
		if (oc && oc !== undefined) { d.on('change',oc); }
		
		_checkboxes[el.dom.id] = d;
	});
	
	// hiddens
	_hiddens = {};
	els = Ext.get(Ext.query('input.hidden'));
	els.each(function(el){
		var d = new Ext.form.Field({
			name: el.dom.name
			,value: el.dom.value
			,inputType: 'hidden'
			,width: 0
			,labelSeparator: ''
			,applyTo: el.dom.id
		});
		_hiddens[el.dom.id] = d;
	});					 
});