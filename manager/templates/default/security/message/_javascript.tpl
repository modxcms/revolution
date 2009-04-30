{literal}
<script type="text/javascript">
// <![CDATA[
var Messages;

Ext.onReady(function() {
	Messages = new Message();
	// hide the form containers
	Ext.get('new_message_div').dom.style.display = 'none';	
	Ext.get('reply_div').dom.style.display = 'none';
	Ext.get('read_div').dom.style.display = 'none';
	Ext.get('forward_div').dom.style.display = 'none';
	
	// setup form fields
	Messages.changeType('user','mn_');
	Messages.changeType('user','m_reply_');
	Messages.changeType('user','m_fwd_');
});

var Message = function() {};
Message.prototype = {	
	switchView: function(leaveOpen) {
		Ext.get('new_message_div').dom.style.display = leaveOpen=='create' ? '' : 'none';
		Ext.get('reply_div').dom.style.display = leaveOpen=='reply' ? '' : 'none';  
		Ext.get('read_div').dom.style.display = leaveOpen=='read' ? '' : 'none';  
		Ext.get('forward_div').dom.style.display = leaveOpen=='forward' ? '' : 'none';    
	},
	
	populateForward: function(dir) {
		// get selected row
		var task = new Ext.util.DelayedTask(function() {
			var selected = adGrid.grid.getSelectionModel().getSelected();
			if (typeof(selected) != 'undefined') {
				Ext.get('m_fwd_text').dom.value = '\n\n-----\n'+'From: ' + selected.data.sender +'\nDate: ' + selected.data.postdate + '\n' + selected.data.message;
				Ext.get('m_fwd_subject').dom.value = 'Fw: ' + selected.data.message_link;
			}
		}, window);
		task.delay(100);
		return false;
	},
	
	populateRead: function() {
		// get selected row
		var task = new Ext.util.DelayedTask(function() {
			var selected = adGrid.grid.getSelectionModel().getSelected();
			if (typeof(selected) != 'undefined') {
				Ext.get('m_read_text').dom.value = selected.data.message;
				Ext.get('m_read_subject').dom.value = selected.data.message_link;
			}
		}, window);
		task.delay(100);
		return false;
	},
	
	populateReply: function() {
		// get selected row
		var task = new Ext.util.DelayedTask(function() {
			var selected = adGrid.grid.getSelectionModel().getSelected();
			if (typeof(selected) != 'undefined') {
				Ext.get('m_reply_text').dom.value = '\n\n-----\n' + selected.data.message;
				Ext.get('m_reply_subject').dom.value = 'Re: ' + selected.data.message_link;
			}
		}, window);
		task.delay(100);
		return false;
	},
	
	changeType: function(type,pfix) {
		if (type == 'user') {
			Ext.get(pfix+'udiv').dom.style.display='';
			Ext.get(pfix+'rdiv').dom.style.display='none';
		} else if (type == 'role') {
			Ext.get(pfix+'udiv').dom.style.display='none';
			Ext.get(pfix+'rdiv').dom.style.display='';
		} else {
			Ext.get(pfix+'udiv').dom.style.display='none';
			Ext.get(pfix+'rdiv').dom.style.display='none';
		}
	},

	markAsRead: function(mid) {
		Ext.Ajax.request({
			url: '{/literal}{$_config.connectors_url}{literal}security/message.php?action=read',
			params: {id: mid}
		});
		adGrid.grid.dataSource.reload();
		return false;
	},
	
	markAsUnread: function() {
		var task = new Ext.util.DelayedTask(function() {
			var selected = adGrid.grid.getSelectionModel().getSelected();
			var mid = selected.data.id;
			Ext.Ajax.request({
				url: '{/literal}{$_config.connectors_url}{literal}security/message.php?action=unread',
				params: {id: mid},
				success: function(response) {
					adGrid.grid.dataSource.reload();
					adGrid.grid.getView().refresh;
				}
			});
		});
		task.delay(100);
		return false;
	},
	
	refresh: function() {
		var task = new Ext.util.DelayedTask(function() {
			adGrid.grid.dataSource.reload();
			adGrid.grid.getView().refresh;
		});
		task.delay(100);
		return false;
	},
	
	remove: function() {
		var task = new Ext.util.DelayedTask(function() {
			var selected = adGrid.grid.getSelectionModel().getSelected();
			if (confirm(_('confirm_delete_message'))) {
			var mid = selected.data.id;
			Ext.Ajax.request({
				url:"{/literal}{$_config.connectors_url}{literal}security/message.php?action=delete",
				params: {id: mid},
				success: function(response) {
					adGrid.grid.dataSource.reload();
					adGrid.grid.getView().refresh;
					adGrid.grid.getSelectionModel().selectPrevious();
				}
			});
			}
		});
		task.delay(100);
		return false;		
	}
};
// ]]>
</script>
{/literal}