/*
	FormHandler
	Automatically sends forms through AJAX calls, returns the result
	(and parses any JS script within response), and if not TRUE, then
	outputs that response to an 'errormsg' div. Also allows you to
	specify the ?action= parameter in _GET, which utilitizes
	PHP connectors to access their respective processor files.
	
	@author Shaun McCormick
*/

// override toQueryString() to fix bug with passing arrays in the POST (see mootools rev. 504)
// this override can be removed if mootools is version 1.2 or greater
Element.extend({
	getFormElements: function(){
		return $$(this.getElementsByTagName('input'), this.getElementsByTagName('select'), this.getElementsByTagName('textarea'));
	},

	toQueryString: function(){
			var queryString = [];
			this.getFormElements().each(function(el){
				var name = el.name;
				var value = el.getValue();
				if (value === false || !name || el.disabled) return;
				var qs = function(val){
					queryString.push(name + '=' + encodeURIComponent(val));
				};
				if ($type(value) == 'array') value.each(qs);
				else qs(value);
			});
			return queryString.join('&');
		}
	});

var FormHandlerClass = new Class({
	fields: [],
		
	initialize: function() { 
		fields = [];
	},
	
	send: function(Frm,action,handler) {
		this.unhighlightFields();
		xhr = new Ajax(Frm.action+'?action='+action,{
			postBody: Frm.toQueryString(),
			method: 'post',
			onComplete: handler == null ? FormHandler.handle : handler,
			evalScripts: false
		}).request();
		
		return false;
	},
	handle: function(e) {
		if (e == '') return false;
		if (e != true) this.showError(e);
		return false;
	},
	
	highlightField: function(field) {
		if (field.name != 'undefined' && field.name != 'forEach' && field.name != '') {
			$(field.name).style.border = '1px solid red';
			$(field.name+'_error').innerHTML = field.error;
			this.fields.push(field.name);
		}
	},
	
	unhighlightFields: function() {
		for (i=0;i<this.fields.length;i++) {
			$(this.fields[i]).style.border = '';
			$(this.fields[i]+'_error').innerHTML = '';
		}
		this.fields = [];
	},
	
	errorJSON: function(response) {
		if (response == '') return this.showError(response);
		error = this.unescapeJson(Json.evaluate(response));
		message = error.message;
	
		if (error.fields != null) {
			for (p=0;p<error.fields.length;p++) {
				this.highlightField(error.fields[p]);
			}
		}

		this.showError(message);
	},
	
	unescapeJson: function(obj) {
		for (var prop in obj) {
			if ($type(obj[prop]) == 'object')
				for (var p in obj[prop]) obj[prop][p] = unescape(obj[prop][p]);
			else if ($type(obj[prop]) == 'string')
				obj[prop] = unescape(obj[prop]);
			else if ($type(obj[prop]) == 'array')
				for (var i = 0; i < obj[prop].length; i++)
					for (var p in obj[prop][i]) obj[prop][i] = this.unescapeJson(obj[prop][i]);
		}
		return obj;
	},
	
	showError: function(e) {
		e == ''
			? Ext.Msg.hide()
			: Ext.Msg.alert('Error',e);
	},
	
	closeError: function() {
		Ext.Msg.hide();
	}

});
var FormHandler = new FormHandlerClass();