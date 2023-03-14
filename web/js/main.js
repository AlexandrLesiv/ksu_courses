"use-strict";

/* FUNCTION IS SHOWING SEARCH FORM */
function showSearchForm(id) {
    let el = $("#" + id);
    if (el.hasClass("d-block")) {
        el.removeClass("d-block");
        el.addClass("d-none");
    } else {
        el.removeClass("d-none");
        el.addClass("d-block");
    }
}

$(function () {
	let isActiveDashboardMenu = false;
    $(".hamburger").on("click", function() {
        if (isActiveDashboardMenu) {
            $(this).removeClass("hamburger--active");
            $("#nav").removeClass("nav--active");
        } else {
            $(this).addClass("hamburger--active");
            $("#nav").addClass("nav--active");
        }

        isActiveDashboardMenu = !isActiveDashboardMenu;
    });

    $(".btn-filter").on("click", function (e) {
        e.preventDefault();
        
        const formFilter = $(".form-filter");
        if (formFilter) {
            if (formFilter.hasClass("d-none")) {
                formFilter.removeClass("d-none");
            } else {
                formFilter.addClass("d-none");
            }
        }
    });
});