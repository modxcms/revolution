Ext.onReady(function() {
	setTimeout('showParameters();',10);
});

function setTextWrap(ctrl,b){
	if(!ctrl) return;
	ctrl.wrap = (b)? "soft":"off";
};

// Current Params
var currentParams = {};

function showParameters(ctrl) {
	var c,p,df,cp;
	var ar,desc,value,key,dt;

	currentParams = {}; // reset;

	if (ctrl) {
		f = ctrl.form;
	} else {
		f= $('mutate_snippet');
		if(!f) return;
	}

	// setup parameters
	tr = $('displayparamrow');
	dp = (f.properties.value) ? f.properties.value.split("&"):"";
	if(!dp) tr.style.display='none';
	else {
		t='<table width="300" style="margin-bottom:3px;margin-left:14px;background-color:#EEEEEE" cellpadding="2" cellspacing="1"><thead><tr><td width="50%">'+_('parameter')+
		'</td><td width="50%">'+_('value')+'</td></tr></thead>';
		for(p = 0; p < dp.length; p++) {
			dp[p]=(dp[p]+'').replace(/^\s|\s$/,''); // trim
			ar = dp[p].split("=");
			key = ar[0]		// param
			ar = (ar[1]+'').split(';');
			desc = ar[0];	// description
			dt = ar[1];		// data type
			value = decode((ar[2])? ar[2]:'');

			// store values for later retrieval
			if (key && dt=='list') currentParams[key] = [desc,dt,value,ar[3]];
			else if (key) currentParams[key] = [desc,dt,value];

			if (dt) {
				switch(dt) {
				case 'int':
					c = '<input type="text" name="prop_'+key+'" value="'+value+'" size="30" onchange="setParameter(\''+key+'\',\''+dt+'\',this)" />';
					break;
				case 'menu':
					value = ar[3];
					c = '<select name="prop_'+key+'" style="width:168px" onchange="setParameter(\''+key+'\',\''+dt+'\',this)">';
					ls = (ar[2]+'').split(",");
					if(currentParams[key]==ar[2]) currentParams[key] = ls[0]; // use first list item as default
					for(i=0;i<ls.length;i++){
						c += '<option value="'+ls[i]+'"'+((ls[i]==value)? ' selected="selected"':'')+'>'+ls[i]+'</option>';
					}
					c += '</select>';
					break;
				case 'list':
					value = ar[3];
					ls = (ar[2]+'').split(",");
					if(currentParams[key]==ar[2]) currentParams[key] = ls[0]; // use first list item as default
					c = '<select name="prop_'+key+'" size="'+ls.length+'" style="width:168px" onchange="setParameter(\''+key+'\',\''+dt+'\',this)">';
					for(i=0;i<ls.length;i++){
						c += '<option value="'+ls[i]+'"'+((ls[i]==value)? ' selected="selected"':'')+'>'+ls[i]+'</option>';
					}
					c += '</select>';
					break;
				case 'list-multi':
					value = (ar[3]+'').replace(/^\s|\s$/,"");
					arrValue = value.split(",")
					ls = (ar[2]+'').split(",");
					if(currentParams[key]==ar[2]) currentParams[key] = ls[0]; // use first list item as default
					c = '<select name="prop_'+key+'" size="'+ls.length+'" multiple="multiple" style="width:168px" onchange="setParameter(\''+key+'\',\''+dt+'\',this)">';
					for(i=0;i<ls.length;i++){
						if(arrValue.length){
							for(j=0;j<arrValue.length;j++){
								if(ls[i]==arrValue[j]){
									c += '<option value="'+ls[i]+'" selected="selected">'+ls[i]+'</option>';
								}else{
									c += '<option value="'+ls[i]+'">'+ls[i]+'</option>';
								}
							}
						}else{
							c += '<option value="'+ls[i]+'">'+ls[i]+'</option>';
						}
					}
					c += '</select>';
					break;
				case 'textarea':
					c = '<textarea name="prop_'+key+'" cols="50" rows="4" onchange="setParameter(\''+key+'\',\''+dt+'\',this)">'+value+'</textarea>';
					break;
				default:  // string
					c = '<input type="text" name="prop_'+key+'" value="'+value+'" size="30" onchange="setParameter(\''+key+'\',\''+dt+'\',this)" />';
					break;

				}
				t +='<tr><td bgcolor="#FFFFFF" width="50%">'+desc+'</td><td bgcolor="#FFFFFF" width="50%">'+c+'</td></tr>';
			};
		}
		t+='</table>';
		td = $('displayparams');
		td.innerHTML = t;
		tr.style.display='';
	}
	implodeParameters();
};

function setParameter(key,dt,ctrl) {
	var v;
	if(!ctrl) return null;
	switch (dt) {
		case 'int':
			ctrl.value = parseInt(ctrl.value);
			if(isNaN(ctrl.value)) ctrl.value = 0;
			v = ctrl.value;
			break;
		case 'menu':
			v = ctrl.options[ctrl.selectedIndex].value;
			currentParams[key][3] = v;
			implodeParameters();
			return;
			break;
		case 'list':
			v = ctrl.options[ctrl.selectedIndex].value;
			currentParams[key][3] = v;
			implodeParameters();
			return;
			break;
		case 'list-multi':
			var arrValues = new Array;
			for(var i=0; i < ctrl.options.length; i++){
				if(ctrl.options[i].selected){
					arrValues.push(ctrl.options[i].value);
				}
			}
			currentParams[key][3] = arrValues.toString();
			implodeParameters();
			return;
			break;
		default:
			v = ctrl.value+'';
			break;
	}
	currentParams[key][2] = v;
	implodeParameters();
};

// implode parameters
function implodeParameters(){
	var v, p, s='';
	for(p in currentParams){
		if(currentParams[p]) {
			v = currentParams[p].join(";");
			if(s && v) s+=' ';
			if(v) s += '&'+p+'='+ v;
		}
	}
	$('mutate_snippet').properties.value = s;
};

function encode(s){
	s=s+'';
	s = s.replace(/\=/g,'%3D'); // =
	s = s.replace(/\&/g,'%26'); // &
	return s;
};

function decode(s){
	s=s+'';
	s = s.replace(/\%3D/g,'='); // =
	s = s.replace(/\%26/g,'&'); // &
	return s;
};