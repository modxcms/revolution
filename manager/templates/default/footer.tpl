    </div>
    <!-- #modx-content-->
    <div id="modx-footer">
        {if $_search}
            <div class="modx-subnav" id="modx-manager-search-icon-submenu">
                <div class="modx-subnav-arrow"></div>
                <div id="modx-manager-search" role="search"></div>
            </div>
        {/if}
        {eval var=$navb_submenus}
        {eval var=$userNav_submenus}
    </div>
</div>
<!-- #modx-container -->

</body>
</html>