<div class="dashboard-block [[+size]] [[+class]]" id="dashboard-block-[[+id]]" data-id="[[+id]]">
    <div class="title-wrapper">
        <div class="title[[+customizable:is=`1`:then=` draggable`]]">[[+name_trans]]</div>
        [[+customizable:is=`1`:then=`
            <div class="action-buttons">
                <button class="action icon icon-compress [[+size:is=`quarter`:then=`hidden`]]" data-action="shrink" aria-label="Shrink"></button>
                <button class="action icon icon-expand [[+size:is=`double`:then=`hidden`]]" data-action="expand" aria-label="Expand"></button>
                <button class="action icon icon-times-circle" data-action="remove" aria-label="Remove"></button>
            </div>
        `]]
    </div>
    <div class="body">
        [[+content]]
    </div>
</div>
