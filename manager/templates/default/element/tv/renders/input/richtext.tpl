<textarea id="tv{$tv->id}" name="tv{$tv->id}" class="modx-richtext" rows="15"
    style="width: '97%';"
    {literal}onchange="MODx.fireResourceFormChange();"{/literal}
>{$tv->get('value')|escape}</textarea>