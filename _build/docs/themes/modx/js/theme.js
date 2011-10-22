$(function() {
    $("#search_box_icon, #search_box_clear").show();
    $("#search_box").show().autocomplete({
        source: "ajax_search.php",
        minLength: 2,
        select: function (event, ui) {
            if ((parent) && (parent.content)) {
                parent.content.document.location = '' + ui.item.id;
            } else {
                jQuery(document).location = '' + ui.item.id;
            }
        }
    });

    $('#search_box_icon').click(function() {
        $('#search_box').focus();
    });

    $('#search_box_clear').click(function() {
        $('#search_box').val('');
    });
});
