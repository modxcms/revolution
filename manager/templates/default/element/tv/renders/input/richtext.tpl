<textarea id="tv{$tv->id}" name="tv{$tv->id}"
	class="textarea"
	cols="40" rows="15"
	{literal}onchange="MODx.fireResourceFormChange();"{/literal}
>{$tv->get('value')|escape}</textarea>