document.addEventListener("DOMContentLoaded", function (event) {
    const showNavbar = (toggleId, navId, bodyId, headerId) => {
        const toggle = document.getElementById(toggleId),
            nav = document.getElementById(navId),
            bodypd = document.getElementById(bodyId),
            headerpd = document.getElementById(headerId);

        if (toggle && nav && bodypd && headerpd) {
            toggle.addEventListener("click", () => {
                nav.classList.toggle("show");
                toggle.classList.toggle("bx-x");
                bodypd.classList.toggle("body-pd");
                headerpd.classList.toggle("body-pd");
            });
        }
    };

    showNavbar("header-toggle", "nav-bar", "body-pd", "header");

    const linkColor = document.querySelectorAll(".nav_link");

    function colorLink() {
        if (linkColor) {
            linkColor.forEach((l) => l.classList.remove("active"));
            this.classList.add("active");
        }
    }
    linkColor.forEach((l) => l.addEventListener("click", colorLink));
});


//slider
$(".slider").slick({
    infinite: false,
    slidesToScroll: 1,
    responsive: [
        {
            breakpoint: 1024,
            settings: {
                slidesToScroll: 1,
            },
        },
        {
            breakpoint: 600,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 1,
            },
        },
        {
            breakpoint: 480,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1,
            },
        },
    ],
});

$(".slick-slide.slick-current").addClass("active");
$(document).on("click", ".slider li a", function (e) {
    e.stopPropagation();
    e.preventDefault();
    var Id = $(this).attr("href");
    $(".slick-slide").removeClass("active");
    $(this).closest(".slick-slide").addClass("active");
    $(".tab-content-section .tab-content").not(Id).removeClass("active");
    if (Id) {
        $(Id).addClass("active");
    }
});


//Sub menu on sidebar

$("#nav-bar .sidebar > ul li a").on("click", function () {
    // alert('test');
    // $("#nav-bar .sidebar > ul li").removeClass("active");
    // $(this).addClass("active");
});

