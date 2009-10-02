<textarea id="tv{$tv->id}" name="tv{$tv->id}"
	class="textarea modx-richtext"
	cols="40" rows="15"
	{literal}onchange="MODx.fireResourceFormChange();"{/literal}
>{$tv->get('value')|escape}</textarea>