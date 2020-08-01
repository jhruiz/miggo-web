$( function() {
    $(".menuvert").removeClass("active");
    var menuId = $('#menuvert').val();
    $("#menuvert_" + menuId).addClass("list-group-item-info");
});