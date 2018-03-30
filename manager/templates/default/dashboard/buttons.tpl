<div class="dashboard-block headless {$size}" id="dashboard-block-{$id}" data-id="{$id}">
    <div class="body{if $customizable} draggable{/if}">
        {if $customizable}
            <div class="action-buttons">
                <button class="action icon icon-compress{if $size == 'quarter'} hidden{/if}"
                        data-action="shrink"></button>
                <button class="action icon icon-expand{if $size == 'double'} hidden{/if}"
                        data-action="expand"></button>
                <button class="action icon icon-times-circle"
                        data-action="remove"></button>
            </div>
        {/if}
        <div class="dashboard-buttons">
            {foreach $properties as $item}
                <a href="{$item.link}" class="dashboard-button"{if !empty($item.target)} target="{$item.target}"{/if}>
                    <div class="dashboard-button-icon">
                        <i class="icon icon-{$item.icon}"></i>
                    </div>
                    <div class="dashboard-button-wrapper">
                        <div class="dashboard-button-title">{$item.title}</div>
                        <div class="dashboard-button-description">{$item.description}</div>
                    </div>
                </a>
            {/foreach}
        </div>
    </div>
</div>