/*  jQuery Nice Select - v1.0
    https://github.com/hernansartorio/jquery-nice-select
    Made by Hernán Sartorio  */
!(function (e) {
    e.fn.niceSelect = function (t) {
        function s(t) {
            t.after(
                e("<div></div>")
                    .addClass("nice-select")
                    .addClass(t.attr("class") || "")
                    .addClass(t.attr("disabled") ? "disabled" : "")
                    .attr("tabindex", t.attr("disabled") ? null : "0")
                    .html('<span class="current"></span><ul class="list"></ul>')
            );
            var s = t.next(),
                n = t.find("option"),
                i = t.find("option:selected");
            s.find(".current").html(i.data("display") || i.text()),
                n.each(function (t) {
                    var n = e(this),
                        i = n.data("display");
                    s.find("ul").append(
                        e("<li></li>")
                            .attr("data-value", n.val())
                            .attr("data-display", i || null)
                            .addClass(
                                "option" +
                                    (n.is(":selected") ? " selected" : "") +
                                    (n.is(":disabled") ? " disabled" : "")
                            )
                            .html(n.text())
                    );
                });
        }
        if ("string" == typeof t)
            return (
                "update" == t
                    ? this.each(function () {
                          var t = e(this),
                              n = e(this).next(".nice-select"),
                              i = n.hasClass("open");
                          n.length &&
                              (n.remove(),
                              s(t),
                              i && t.next().trigger("click"));
                      })
                    : "destroy" == t
                    ? (this.each(function () {
                          var t = e(this),
                              s = e(this).next(".nice-select");
                          s.length && (s.remove(), t.css("display", ""));
                      }),
                      0 == e(".nice-select").length &&
                          e(document).off(".nice_select"))
                    : console.log('Method "' + t + '" does not exist.'),
                this
            );
        this.hide(),
            this.each(function () {
                var t = e(this);
                t.next().hasClass("nice-select") || s(t);
            }),
            e(document).off(".nice_select"),
            e(document).on("click.nice_select", ".nice-select", function (t) {
                var s = e(this);
                e(".nice-select").not(s).removeClass("open"),
                    s.toggleClass("open"),
                    s.hasClass("open")
                        ? (s.find(".option"),
                          s.find(".focus").removeClass("focus"),
                          s.find(".selected").addClass("focus"))
                        : s.focus();
            }),
            e(document).on("click.nice_select", function (t) {
                0 === e(t.target).closest(".nice-select").length &&
                    e(".nice-select").removeClass("open").find(".option");
            }),
            e(document).on(
                "click.nice_select",
                ".nice-select .option:not(.disabled)",
                function (t) {
                    var s = e(this),
                        n = s.closest(".nice-select");
                    n.find(".selected").removeClass("selected"),
                        s.addClass("selected");
                    var i = s.data("display") || s.text();
                    n.find(".current").text(i),
                        n.prev("select").val(s.data("value")).trigger("change");
                }
            ),
            e(document).on("keydown.nice_select", ".nice-select", function (t) {
                var s = e(this),
                    n = e(s.find(".focus") || s.find(".list .option.selected"));
                if (32 == t.keyCode || 13 == t.keyCode)
                    return (
                        s.hasClass("open")
                            ? n.trigger("click")
                            : s.trigger("click"),
                        !1
                    );
                if (40 == t.keyCode) {
                    if (s.hasClass("open")) {
                        var i = n.nextAll(".option:not(.disabled)").first();
                        i.length > 0 &&
                            (s.find(".focus").removeClass("focus"),
                            i.addClass("focus"));
                    } else s.trigger("click");
                    return !1;
                }
                if (38 == t.keyCode) {
                    if (s.hasClass("open")) {
                        var l = n.prevAll(".option:not(.disabled)").first();
                        l.length > 0 &&
                            (s.find(".focus").removeClass("focus"),
                            l.addClass("focus"));
                    } else s.trigger("click");
                    return !1;
                }
                if (27 == t.keyCode) s.hasClass("open") && s.trigger("click");
                else if (9 == t.keyCode && s.hasClass("open")) return !1;
            });
        var n = document.createElement("a").style;
        return (
            (n.cssText = "pointer-events:auto"),
            "auto" !== n.pointerEvents &&
                e("html").addClass("no-csspointerevents"),
            this
        );
    };
})(jQuery);

$(document).ready(function () {
    /********* On scroll heder Sticky *********/
    $(window).scroll(function () {
        var scroll = $(window).scrollTop();
        if (scroll >= 50) {
            $("header").addClass("head-sticky");
            $(".announce-bar").slideUp("500");
        } else {
            $("header").removeClass("head-sticky");
            $(".announce-bar").slideDown("500");
        }
    });
    /********* Mobile Menu ********/
    $(".mobile-menu-button").on("click", function (e) {
        e.preventDefault();
        setTimeout(function () {
            $("body").addClass("no-scroll active-menu");
            $(".mobile-menu-wrapper").toggleClass("active-menu");
            $(".overlay").addClass("menu-overlay");
        }, 50);
    });
    $("body").on(
        "click",
        ".overlay.menu-overlay, .menu-close-icon svg",
        function (e) {
            e.preventDefault();
            $("body").removeClass("no-scroll active-menu");
            $(".mobile-menu-wrapper").removeClass("active-menu");
            $(".overlay").removeClass("menu-overlay");
        }
    );
    /********* Cart Popup ********/
    $(".cart-header").on("click", function (e) {
        e.preventDefault();
        setTimeout(function () {
            $("body").addClass("no-scroll cartOpen");
            $(".overlay").addClass("cart-overlay");
        }, 50);
    });
    $("body").on("click", ".overlay.cart-overlay, .closecart", function (e) {
        e.preventDefault();
        $(".overlay").removeClass("cart-overlay");
        $("body").removeClass("no-scroll cartOpen");
    });
    /********* Mobile Filter Popup ********/
    // $(".filter-title").on("click", function (e) {
    //     e.preventDefault();
    //     setTimeout(function () {
    //         $("body").addClass("no-scroll filter-open");
    //         $(".overlay").addClass("active");
    //     }, 50);
    // });
    $("body").on("click", ".overlay.active, .close-filter", function (e) {
        e.preventDefault();
        $(".overlay").removeClass("active");
        $("body").removeClass("no-scroll filter-open");
    });
    /******* Cookie Js *******/
    $(".cookie-close").click(function () {
        $(".cookie").slideUp();
    });
    /******* Subscribe popup Js *******/
    $(".close-sub-btn").click(function () {
        $(".subscribe-popup").slideUp();
        $(".subscribe-overlay").removeClass("open");
    });
    /*********  size Popup  ********/
    $(".book-btn").click(function () {
        $(".cart-popup").toggleClass("active");
        $("body").toggleClass("no-scroll");
    });
    $(".close-btn").click(function () {
        $(".cart-popup").removeClass("active");
        $("body").removeClass("no-scroll");
    });
    $(".close-btn").click(function () {
        $(".cart-popup").removeClass("active");
        $("body").removeClass("no-scroll");
    });
    /********* qty spinner ********/
    var quantity = 0;
    $(".quantity-increment").click(function () {
        var t = $(this).siblings(".quantity");
        var quantity = parseInt($(t).val());
        $(t).val(quantity + 1);
    });
    $(".quantity-decrement").click(function () {
        var t = $(this).siblings(".quantity");
        var quantity = parseInt($(t).val());
        if (quantity > 1) {
            $(t).val(quantity - 1);
        }
    });
    /******  Nice Select  ******/
    $("select").niceSelect();
    /*********  Multi-level accordion nav  ********/
    $(".acnav-label").click(function () {
        var label = $(this);
        var parent = label.parent(".has-children");
        var list = label.siblings(".acnav-list");
        if (parent.hasClass("is-open")) {
            list.slideUp("fast");
            parent.removeClass("is-open");
        } else {
            list.slideDown("fast");
            parent.addClass("is-open");
        }
    });
    /****  TAB Js ****/
    $("ul.tabs li").click(function () {
        var $this = $(this);
        var $theTab = $(this).attr("data-tab");
        if ($this.hasClass("active")) {
            // do nothing
        } else {
            $this
                .closest(".tabs-wrapper")
                .find("ul.tabs li, .tabs-container .tab-content")
                .removeClass("active");
            $(
                '.tabs-container .tab-content[id="' +
                    $theTab +
                    '"], ul.tabs li[data-tab="' +
                    $theTab +
                    '"]'
            ).addClass("active");
        }
        $(".dietetist-slider").slick("refresh");
    });

    // video pop up
    //video-play
    $(".play-vid").on("click", function () {
        if ($(this).attr("data-click") == 1) {
            $(this).attr("data-click", 0);
            $("#img-vid")[0].pause();
            $(".play-vid").css("opacity", "1");
        } else {
            $(this).attr("data-click", 1);
            $("#img-vid")[0].play();
            $(".play-vid").css("opacity", "1");
            $(".play-vid").css("opacity", "0");
        }
    });
    $(".play-btn").click(function (m) {
        $("body,html").addClass("no-scroll popupopen");
        $(".overlay-popup").addClass("popup-show");
    });
    $(".close-popup").click(function (m) {
        $("body,html").removeClass("no-scroll popupopen");
        $(".overlay-popup").removeClass("popup-show");
    });

    // HOME BANNER SLIDER JS END //
    /**** FULL-WIDTH-SLIDER  ****/
    $(".banner-main-slider").slick({
        arrows: false,
        dots: true,
        infinite: true,
        speed: 800,
        slidesToShow: 1,
        slidesToScroll: 1,
        centerPadding: "0",
        cssEase: "cubic-bezier(0.5,0,0,1)",
        centerMode: true,
        prevArrow:
            '<button class="slick-prev slick-arrow"><span class="slickbtn"><svg><use xlink:href="#slickarrow"></use></svg></span></button>',
        nextArrow:
            '<button class="slick-next slick-arrow"><span class="slickbtn"><svg><use xlink:href="#slickarrow"></use></svg></span></button>',
        responsive: [
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                },
            },
        ],
    });
    $(".rooms-slider").slick({
        arrows: false,
        dots: true,
        infinite: true,
        speed: 800,
        slidesToShow: 2,
        slidesToScroll: 1,
        centerPadding: "0",
        cssEase: "cubic-bezier(0.5,0,0,1)",
        centerMode: true,
        prevArrow:
            '<button class="slick-prev slick-arrow"><span class="slickbtn"><svg><use xlink:href="#slickarrow"></use></svg></span></button>',
        nextArrow:
            '<button class="slick-next slick-arrow"><span class="slickbtn"><svg><use xlink:href="#slickarrow"></use></svg></span></button>',
        responsive: [
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                },
            },
        ],
    });

    if ($(".testimonial-slider").length > 0) {
        $(".testimonial-slider").slick({
            autoplay: false,
            slidesToShow: 3,
            speed: 1000,
            slidesToScroll: 1,
            centerMode: false,
            arrows: false,
            centerPadding: 0,
            prevArrow:
                '<button class="slick-prev slick-arrow"><span class="slickbtn"><svg><use xlink:href="#slickarrow"></use></svg></span></button>',
            nextArrow:
                '<button class="slick-next slick-arrow"><span class="slickbtn"><svg><use xlink:href="#slickarrow"></use></svg></span></button>',
            dots: true,
            buttons: false,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    },
                },
                {
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    },
                },
            ],
        });
    }

    if ($(".blog-slider").length > 0) {
        $(".blog-slider").slick({
            autoplay: false,
            slidesToShow: 3,
            speed: 1000,
            arrows: false,
            slidesToScroll: 1,
            prevArrow:
                '<button class="slick-prev slick-arrow"><span class="slickbtn"><svg><use xlink:href="#slickarrow"></use></svg></span></button>',
            nextArrow:
                '<button class="slick-next slick-arrow"><span class="slickbtn"><svg><use xlink:href="#slickarrow"></use></svg></span></button>',
            dots: true,
            buttons: false,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    },
                },
                {
                    breakpoint: 421,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    },
                },
            ],
        });
    }

    if ($(".insta-slider").length > 0) {
        $(".insta-slider").slick({
            autoplay: true,
            slidesToShow: 4,
            speed: 1000,
            arrows: false,
            slidesToScroll: 1,
            prevArrow:
                '<button class="slick-prev slick-arrow"><span class="slickbtn"><svg><use xlink:href="#slickarrow"></use></svg></span></button>',
            nextArrow:
                '<button class="slick-next slick-arrow"><span class="slickbtn"><svg><use xlink:href="#slickarrow"></use></svg></span></button>',
            dots: false,
            buttons: false,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    },
                },
                {
                    breakpoint: 421,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    },
                },
            ],
        });
    }
    if ($(".product-image-slider").length > 0) {
        $(".product-image-slider").slick({
            autoplay: false,
            slidesToShow: 1,
            speed: 1000,
            arrows: false,
            slidesToScroll: 1,
            prevArrow:
                '<button class="slick-prev slick-arrow"><span class="slickbtn"><svg><use xlink:href="#slickarrow"></use></svg></span></button>',
            nextArrow:
                '<button class="slick-next slick-arrow"><span class="slickbtn"><svg><use xlink:href="#slickarrow"></use></svg></span></button>',
            dots: true,
            buttons: false,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    },
                },
                {
                    breakpoint: 421,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    },
                },
            ],
        });
    }
});

/********* MY ACCOUNT PAGE SCROLL FIXED JS ********/
if ($(".my-acc-column").length > 0) {
    jQuery(function ($) {
        var topMenuHeight = $("#daccount-nav").outerHeight();
        $("#account-nav").menuScroll(topMenuHeight);
        $(".account-list li:first-child").addClass("active");
    });
    // COPY THE FOLLOWING FUNCTION INTO ANY SCRIPTS
    jQuery.fn.extend({
        menuScroll: function (offset) {
            // Declare all global variables
            var topMenu = this;
            var topOffset = offset ? offset : 0;
            var menuItems = $(topMenu).find("a");
            var lastId;
            // Save all menu items into scrollItems array
            var scrollItems = $(menuItems).map(function () {
                var item = $($(this).attr("href"));
                if (item.length) {
                    return item;
                }
            });
            // When the menu item is clicked, get the #id from the href value, then scroll to the #id element
            $(topMenu).on("click", "a", function (e) {
                var href = $(this).attr("href");
                var offsetTop =
                    href === "#" ? 0 : $(href).offset().top - topOffset;
                function checkWidth() {
                    var windowSize = $(window).width();
                    if (windowSize <= 767) {
                        $("html, body")
                            .stop()
                            .animate(
                                {
                                    scrollTop: offsetTop - 200,
                                },
                                300
                            );
                    } else {
                        $("html, body")
                            .stop()
                            .animate(
                                {
                                    scrollTop: offsetTop - 100,
                                },
                                300
                            );
                    }
                }
                // Execute on load
                checkWidth();
                // Bind event listener
                $(window).resize(checkWidth);
                e.preventDefault();
            });
            // When page is scrolled
            $(window).scroll(function () {
                function checkWidth() {
                    var windowSize = $(window).width();
                    if (windowSize <= 767) {
                        var nm = $("html").scrollTop();
                        var nw = $("body").scrollTop();
                        var fromTop = (nm > nw ? nm : nw) + topOffset;
                        // When the page pass one #id section, return all passed sections to scrollItems and save them into new array current
                        var current = $(scrollItems).map(function () {
                            if ($(this).offset().top - 250 <= fromTop)
                                return this;
                        });
                        // Get the most recent passed section from current array
                        current = current[current.length - 1];
                        var id = current && current.length ? current[0].id : "";
                        if (lastId !== id) {
                            lastId = id;
                            // Set/remove active class
                            $(menuItems)
                                .parent()
                                .removeClass("active")
                                .end()
                                .filter("[href='#" + id + "']")
                                .parent()
                                .addClass("active");
                        }
                    } else {
                        var nm = $("html").scrollTop();
                        var nw = $("body").scrollTop();
                        var fromTop = (nm > nw ? nm : nw) + topOffset;
                        // When the page pass one #id section, return all passed sections to scrollItems and save them into new array current
                        var current = $(scrollItems).map(function () {
                            if ($(this).offset().top <= fromTop) return this;
                        });
                        // Get the most recent passed section from current array
                        current = current[current.length - 1];
                        var id = current && current.length ? current[0].id : "";
                        if (lastId !== id) {
                            lastId = id;
                            // Set/remove active class
                            $(menuItems)
                                .parent()
                                .removeClass("active")
                                .end()
                                .filter("[href='#" + id + "']")
                                .parent()
                                .addClass("active");
                        }
                    }
                }
                // Execute on load
                checkWidth();
                // Bind event listener
                $(window).resize(checkWidth);
            });
        },
    });
}
/********* MY ACCOUNT PAGE SCROLL FIXED JS END********/
/********* Wrapper top space ********/
$(window).on("load resize orientationchange", function () {
    var header_hright = $("header").outerHeight();
    $("header")
        .next(".wrapper")
        .css("margin-top", header_hright + "px");
});
/********* Wrapper top space End********/

/******  STEPPY FORM  CSS  ******/
var totalSteps = $(".steps li").length;
$(".submit").on("click", function () {
    return false;
});

$(".steps li:nth-of-type(1)").addClass("active");
$(".myContainer .step-container:nth-of-type(1)").addClass("active");

$(".step-container").on("click", ".next", function () {
    $(".steps li")
        .eq($(this).parents(".step-container").index() + 1)
        .addClass("active");
    $(this)
        .parents(".step-container")
        .removeClass("active")
        .next()
        .addClass("active");
    $(".right-slider").slick("refresh");
});

$(".step-container").on("click", ".back", function () {
    $(".steps li")
        .eq($(this).parents(".step-container").index() - totalSteps)
        .removeClass("active");
    $(this)
        .parents(".step-container")
        .removeClass("active")
        .prev()
        .addClass("active");
    $(".right-slider").slick("refresh");
});
/******  STEPPY FORM  CSS  End******/
function myFunction() {
    var dots = document.getElementById("dots");
    var moreText = document.getElementById("more");
    var btnText = document.getElementById("myBtn");

    if (dots.style.display === "none") {
        dots.style.display = "inline";
        btnText.innerHTML = "Read more";
        moreText.style.display = "none";
    } else {
        dots.style.display = "none";
        btnText.innerHTML = "Read less";
        moreText.style.display = "inline";
    }
}

function decodeHtmlEntities(str) {
    const txt = document.createElement('textarea');
    txt.innerHTML = str;
    return txt.value;
}
function formatCurrency(price, settingsEntity) {
    let symbolPosition = 'pre';
    let currencySpace = null;
    let symbol = '$';
    let format = 2;
    let decimalSeparator = ',';
    let thousandSeparator = '.';
    const decodedString = decodeHtmlEntities(settingsEntity);
    const settings = JSON.parse(decodedString);

    price = parseFloat(price);
    if (isNaN(price)) {
        console.error('Invalid price value');
        return '';
    }

    let length = price.toFixed(format).split('.')[0].length;
    if (settings) {
        if (settings.site_currency_symbol_position === 'post') {
            symbolPosition = 'post';
        }
        if (settings.defult_currancy_symbol) {
            symbol = settings.defult_currancy_symbol;
        }
        if (settings.currency_format) {
            format = parseInt(settings.currency_format, 10);
        }
        if (settings.currency_space) {
            currencySpace = settings.currency_space;
        }
        if (settings.site_currency_symbol_name) {
            symbol = settings.site_currency_symbol_name === 'symbol' ? settings.defult_currancy_symbol : settings.defult_currancy;
        }

        if (length > 3) {
            decimalSeparator = settings.float_number && settings.float_number !== 'dot' ? ',' : '.';
        } else {
            decimalSeparator = settings.decimal_separator && settings.decimal_separator !== 'dot' ? ',' : '.';
        }
        thousandSeparator = settings.thousand_separator === 'dot' ? '.' : ',';
    }

    let [integerPart, fractionalPart] = price.toFixed(format).split('.');
    integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator);

    let formattedPrice = integerPart + (fractionalPart ? decimalSeparator + fractionalPart : '');

    return (
        (symbolPosition === 'pre' ? symbol : '') +
        (currencySpace === 'withspace' ? ' ' : '') +
        formattedPrice +
        (currencySpace === 'withspace' ? ' ' : '') +
        (symbolPosition === 'post' ? symbol : '')
    );
}