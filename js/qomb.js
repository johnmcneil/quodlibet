$(document).ready(function(){

/* scrollspy for navigation in main page */    
$("body").scrollspy({
    target: '.bs-docs-sidebar',
    offset: 390
});

$("#sidebar").affix({
    offset: {
        top: 390
    }
});

/* scrollspy for preview in insert panel */
/*
$("body").scrollspy({
    target: '#preview',
    offset: 0
});
*/

$("#preview2").affix({
    offset: {
        top: 425,
        bottom: function () {
            return (this.bottom = $('#edit').outerHeight(true))
        }
    }
});


//this is to make the first theme in the nav highlighted before you scroll.
$(".bs-docs-sidebar .nav .nav .active").prev().children().css("color","#FF1919");

/*this function fixes the problem that only the themes in the right column are
 *highlighted in the navigation by scrollspy. this function highlights the ones
 *that need to be highlighted and dehighlights the ones that need that. */
$(window).scroll(function(){
    //this sets any <li> without class:active to have the <a> it contains be grey.  
    $(".bs-docs-sidebar .nav .nav>li:not(.active) a").css("color","#777");

    //this makes sure that any li with class:active will have its <a> be red.
    $(".bs-docs-sidebar .nav .nav .active a").css("color", "#FF1919");

    //this says that the <li><a> previous to <li class="active"> should be red.
    $(".bs-docs-sidebar .nav .nav .active").prev().children().css("color","#FF1919");

});

/* make the letter ranges link to the first quote in each.
 * had to write a separate line of jquery for each letter-range because attr() only gets the first href.
 */
$("#letter-range1").attr("href", $("#letter-range1").next().children(":first-child").children(":first-child").attr("href"));
$("#letter-range2").attr("href", $("#letter-range2").next().children(":first-child").children(":first-child").attr("href"));
$("#letter-range3").attr("href", $("#letter-range3").next().children(":first-child").children(":first-child").attr("href"));
$("#letter-range4").attr("href", $("#letter-range4").next().children(":first-child").children(":first-child").attr("href"));
$("#letter-range5").attr("href", $("#letter-range5").next().children(":first-child").children(":first-child").attr("href"));


// show-hide for full citations

$(".full-attribution").hide()
$(".full-attribution-click-me").click(function() {
    $(this).next().toggle();
});






// admin page - show-hide button for the insert form
$(document).ready(function(){
    $("#insert").hide();
    $("#insert-button").click(function(){
        $("#insert").toggle();
    });
});

});