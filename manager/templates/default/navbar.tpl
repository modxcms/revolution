<div id="modx-home-icon"><a href="?a=1"></a></div>
<div id="modx-topnav-div">
    <ul id="modx-topnav">
    {foreach from=$menus item=menu name=m}
       <li id="limenu{$menu.id}" class="top {if $smarty.foreach.m.first}first active{/if}">
           <a href="javascript:;" onmouseover="MODx.changeMenu(this,'menu{$menu.id}');">{$menu.text}</a>
           
	       <div class="zone">
	           <ul class="modx-subnav" id="menu{$menu.id}">
	           {foreach from=$menu.children item=submenu name=sm}
	               <li><a 
	                   href="javascript:;"
	                   onclick="{if $submenu.handler NEQ ''}{$submenu.handler|escape}{else}MODx.loadPage({$submenu.action},'{$submenu.params}');{/if}">
	                       {$submenu.text}                   
	                       {if $submenu.description}
	                        <span class="description">{$submenu.description}</span>
	                       {/if}
                   </a></li>
                {/foreach}
	               <li class="last"><span>&nbsp;</span></li>
	            </ul>
            </div>
        </li>
    {/foreach}    
        <li class="cls"></li>
    </ul>
</div>