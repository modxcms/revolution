<style>
    #modx-action-buttons .x-btn {
        display: inline-block;
    }
</style>

<script type="text/javascript">

    Ext.onReady(function() {
        var myPanel = new Ext.Panel({
            renderTo : document.body,
            height   : 50,
            width    : 150,
            title    : 'Simple Panel',
            html     : 'This is my content',
            frame    : true
        });
    });

    console.log("Adding panel.");


</script>

<div class="container">
    <div class="container">
        <h2  class="modx-page-header">[[%resource_trash_title]]</h2>

        <div class="x-panel-body shadowbox">
            <div class="panel-desc">Some description</div>
            <div class="x-panel main-wrapper">
                <div>
                    <span class="x-btn x-btn-small">
                        <button>[[%resource_trash_button_purge_title]]</button>
                    </span>
                    <span class="x-btn">
                        <button>[[%resource_trash_button_restore_title]]</button>
                    </span>
                </div>
            </div>
        </div>
    </div>


    <div id="modx-action-buttons" class="x-toolbar">
        <span class="x-btn x-btn-small primary-button">
            <em class="">
                <button type="button" class="x-btn-text">[[%resource_trash_button_close_title]]</button>
            </em>
        </span>
    </div>
</div>