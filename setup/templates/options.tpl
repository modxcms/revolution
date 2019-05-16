<form id="options" action="?action=options" method="post">

    <div class="content-wrap">
        <h2>{$_lang.options_title}</h2>

        <div class="options">
            <div class="options-wrap">

                <label class="option-item">
                    <div class="option-item-input">
                        <input type="radio" class="hide" name="installmode" id="installmode0" value="0" {if $installmode EQ 0} checked="checked" autofocus="autofocus" {/if} />
                        <i class="fa fas fa-gift"></i>
                        <span></span>
                    </div>
                    <div class="option-item-desc">
                        <div class="label">{$_lang.options_new_installation}</div>
                        <div class="desc">
                            {$_lang.options_install_new_copy} {$app_name} <br>
                            <strong>{$_lang.options_install_new_note}</strong>
                        </div>
                    </div>
                </label>

                <label class="option-item">
                    <div class="option-item-input">
                        <input type="radio" class="hide" name="installmode" id="installmode1" value="1" {if $installmode LT 1} disabled="disabled" {/if}{if $installmode EQ 1} checked="checked" autofocus="autofocus" {/if} />
                        <i class="fa fas fa-upload"></i>
                        <span></span>
                    </div>
                    <div class="option-item-desc">
                        <div class="label">{$_lang.options_upgrade_existing}</div>
                        <div class="desc">
                            {$_lang.options_upgrade_existing_note}
                        </div>
                    </div>
                </label>

                <label class="option-item">
                    <div class="option-item-input">
                        <input type="radio" class="hide" name="installmode" id="installmode3" value="3" {if $installmode LT 1} disabled="disabled" {/if}{if $installmode EQ 3} checked="checked" autofocus="autofocus" {/if} />
                        <i class="fa fas fa-upload"></i>
                        <span></span>
                    </div>
                    <div class="option-item-desc">
                        <div class="label">{$_lang.options_upgrade_advanced}</div>
                        <div class="desc">
                            <strong>{$_lang.options_install_new_note}</strong>
                        </div>
                    </div>
                </label>

            </div>
        </div>

        {if $installmode EQ 0}

        <h2>{$_lang.advanced_options}</h2>
        <div class="options advanced_options">
            <div class="options-wrap">

                <div class="option-item">
                    <div class="option-item-input">
                        <input type="text" name="new_folder_permissions" id="new_folder_permissions" value="{$new_folder_permissions}" size="5" maxlength="4" />
                    </div>
                    <div class="option-item-desc">
                        <div class="label">{$_lang.options_new_folder_permissions}</div>
                        <div class="desc">
                            {$_lang.options_new_folder_permissions_note}
                        </div>
                    </div>
                </div>

                <div class="option-item">
                    <div class="option-item-input">
                        <input type="text" name="new_file_permissions" id="new_file_permissions" value="{$new_file_permissions}" size="5" maxlength="4" />
                    </div>
                    <div class="option-item-desc">
                        <div class="label">{$_lang.options_new_file_permissions}</div>
                        <div class="desc">
                            {$_lang.options_new_file_permissions_note}
                        </div>
                    </div>
                </div>

                <div class="option-item">
                    <div class="option-item-input">
                        <input type="checkbox" name="send_poweredby_header" id="send_poweredby_header" value="1" checked="checked" />
                        <label for="send_poweredby_header"></label>
                    </div>
                    <div class="option-item-desc">
                        <div class="label">{$_lang.options_send_poweredby_header}</div>
                        <div class="desc">
                            {$_lang.options_send_poweredby_header_note}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {/if}

        {if $smarty.const.MODX_SETUP_KEY EQ '@traditional@' AND $unpacked EQ 1 AND $files_exist EQ 1}

        <input type="hidden" name="unpacked" id="unpacked" value="1" />

        {else}
        {if $installmode NE 0}
        <h2>{$_lang.advanced_options}</h2>
        {/if}

        <div class="options advanced_options">
            <div class="options-wrap">

                <div class="option-item">
                    <div class="option-item-input">
                        <input type="checkbox" name="unpacked" id="unpacked" value="1" {if $unpacked EQ 0} disabled="disabled" {/if}{if $unpacked EQ 1} checked="checked" {/if} />
                        <label for="unpacked"></label>
                    </div>
                    <div class="option-item-desc">
                        <div class="label">{$_lang.options_core_unpacked}</div>
                        <div class="desc">
                            {$_lang.options_core_unpacked_note}
                        </div>
                    </div>
                </div>

                <div class="option-item">
                    <div class="option-item-input">
                        <input type="checkbox" name="inplace" id="inplace" value="1" {if $files_exist EQ 0} disabled="disabled" {/if}{if $files_exist EQ 1} checked="checked" {/if} />
                        <label for="inplace"></label>
                    </div>
                    <div class="option-item-desc">
                        <div class="label">{$_lang.options_core_inplace}</div>
                        <div class="desc">
                            {$_lang.options_core_inplace_note}
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {/if}
    </div>

    <div class="setup_navbar">
        <input type="button" onclick="MODx.go('welcome');" value="&#xf053; {$_lang.back}" id="modx-back" class="button" />
        <input type="submit" name="proceed" value="{$_lang.next} &#xf054;" id="modx-next" class="button" />
    </div>
</form>
