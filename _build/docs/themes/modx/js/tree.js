$(document).ready(function() {
    $(".filetree").treeview({
        collapsed: true,
        persist: "cookie"
    });

    $("#accordion").accordion({
        collapsible: true,
        autoHeight:  false,
        fillSpace:   true
    });

    $(".tabs").tabs();
    
    var uls = $('ul.treeview:empty');
    uls.each(function() {
        $(this).parent().remove();
    });
});