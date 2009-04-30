Ext.onReady(function() {
//	Ext.state.Manager.setProvider(new Ext.state.CookieProvider);
	var treepanel = new Ext.ux.FileTreePanel({
		 width:284
		,height:400
		,id:'ftp'
		,title:'FileTreePanel'
		,renderTo:'treepanel'
		,rootPath:'./'
		,topMenu:true
		,autoScroll:true
		,enableProgress:false
//		,baseParams:{additional:'haha'}
//		,singleUpload:true
	});
});

