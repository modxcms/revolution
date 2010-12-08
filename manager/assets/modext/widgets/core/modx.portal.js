Ext.namespace('MODx.portal');

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
Ext.reg('portlet', MODx.portal.Portlet);