window.width = {
    desktop: 1220,
    desktopSmall: 1100,
    tablet: 992,
    mobile: 768
};
$('#filterpro .filterpro__title').on('click', function () {
    $(this).closest('.filterpro__section').toggleClass('open');
});
// show inner menu for mobile
$('#r-menu .with-child').on('click', function (evt) {
    var _this = $(this);
    var target = _this.closest('li');
    var windowWidth = $(window).width();
    target.toggleClass('open');
    if (windowWidth <= window.width.desktop) {
        evt.preventDefault();
    }
});
// remove open class for inner menu in toggle block
$('#r-menu-toggle').on('click', function () {
    if ($('#r-menu').hasClass('show')) {
        $('#r-menu li').each(function () {
            $(this).removeClass('open');
        })
    }
});
// hide collapsible block on outer click
$(document).mouseup(function (evt) {
    if ($("#filterpro").length === 0) {
        return false;
    }
    var div = $("#filterpro .option_box");
    // check click on element & his child
    if (!div.is(evt.target) && div.has(evt.target).length === 0) {
        div.find(".collapsible").slideUp(500);
    }
});
// remove colorbox modal for mobile and init for tablet & desktop
var manageColorbox = function () {
    var windowWidth = $(window).width();
    if (windowWidth <= window.width.desktop) {
        $.colorbox.remove();
    } else {
        $('.colorbox').colorbox({
            current: '',
            title: false,
            arrowKey: false,
            overlayClose: true,
            opacity: 0.5,
            rel: "colorbox-imggal"
        });
    }
}

$(window).on('load', function () {
    manageColorbox();
});

$(window).on('resize', function () {
    manageColorbox();
});
// disable colorBox for all page on screen smaller than desktop
$(window).on('load', function () {
    var windowWidth = $(window).width();
    if (windowWidth > window.width.desktop) {
        return false;
    }
    $('.product-info .options a.colorbox').on('click', function() {
        var href = $(this).attr('href');
        location.href = href;
    })
    $('a.colorbox').on('click', function (evt) {
        evt.preventDefault();
    });
});
// change big photo for product page on click nav image
$(window).on('load', function () {
    var windowWidth = $(window).width();
    if (windowWidth > window.width.desktop) {
        return false;
    }
    $('.product-info a.colorbox').on('click', function (evt) {
        evt.preventDefault();
    });
    $('.image-additional a.colorbox').on('click', function (evt) {
        evt.preventDefault();
        var srcImage = $(this).attr('href');
        var target = $('.product-info #image');
        target.show();
        $('#photo3d-mobile-container').hide();
        target.attr('src', srcImage);
    });
});
// destroy product img slider for mobile
var manageOwlCarouselSlider = function () {
    var $owl = $(".image-additional");
    var windowWidth = $(window).width();
    var carousel_Settings = {
        responsiveBaseWidth: ".image-additional",
        itemsCustom: [
            [400, 4]
        ]
    };
    if (windowWidth <= window.width.desktop) {
        $owl.trigger('destroy.owl.carousel').removeClass('owl-carousel owl-loaded owl-theme');
        $owl.find('.owl-stage-outer').children().unwrap();
    } else {
        $owl.addClass('owl-carousel').addClass('owl-theme').owlCarousel(carousel_Settings);
    }
}

$(window).on('load', function () {
    manageOwlCarouselSlider();
});

$(window).on('resize', function () {
    manageOwlCarouselSlider();
});
// checkout button for mobile
$(window).on('load', function () {
    $('.checkout-body').on('click', '.delivery-table tr', function() {
        if ($(window).width() <= window.width.mobile) {
            $(this).siblings().removeClass('checked')
            $(this).addClass('checked');
            $(this).find('input[name="delivery-way"]').prop('checked', true);
            $(this).find('input[name="delivery-way"]').trigger('change');
        }
    })
});

