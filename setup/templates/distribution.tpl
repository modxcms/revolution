<script type="text/javascript" src="assets/js/sections/distribution.js"></script>
<form id="distribution" action="?action=distribution" method="post">
    <h2>Distribution</h2>
    <p>To get started quickly, you can choose one of the following distributions for your MODX site. This will automatically install third party extras and, if desired, a theme. </p>

    <div class="distributions">
        <div class="distribution">
            <label><input type="radio" name="distribution" value="advanced" data-packages="-" checked="checked"> None</label>
        </div>
        <div class="distribution">
            <label><input type="radio" name="distribution" value="blog" data-packages="getresources,wayfinder,tagger,quip"> Personal Blog</label>
        </div>
        <div class="distribution">
            <label><input type="radio" name="distribution" value="business" data-packages="getresources,wayfinder"> Business</label>
        </div>
        <div class="distribution">
            <label><input type="radio" name="distribution" value="advanced" data-packages="*"> Advanced</label>
        </div>
    </div>

    <div class="packages">
        <h2>Choose Extras</h2>
        <p class="description">Choose the extras you would like to install. </p>
        <div class="package">
            <label><input type="checkbox" name="packages[]" value="getresources"> getResources</label>
            <p class="description">Used for generating listings of resources or menus.</p>
        </div>
        <div class="package">
            <label><input type="checkbox" name="packages[]" value="tagger"> Tagger</label>
            <p class="description">Used for assigning tags to resources, and filtering resource listings.</p>
        </div>
        <div class="package">
            <label><input type="checkbox" name="packages[]" value="wayfinder"> Wayfinder</label>
            <p class="description">Generates hierarchical menus.</p>
        </div>
        <div class="package">
            <label><input type="checkbox" name="packages[]" value="versionx"> VersionX</label>
            <p class="description">Keeps track of changes to your resources and elements.</p>
        </div>
        <div class="package">
            <label><input type="checkbox" name="packages[]" value="..."> ...</label>
            <p class="description">... some more packages ...</p>
        </div>
    </div>

    <div class="setup_navbar">
        <input type="submit" name="proceed" id="modx-next" class="modx-hidden" value="{$_lang.next}" />
        <input type="button" onclick="MODx.go('options');" value="{$_lang.back}" />
    </div>
</form>
