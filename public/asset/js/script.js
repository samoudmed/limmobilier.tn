var ww = document.body.clientWidth;

function adjustMenu() {
    ww < 992 ? ($(".header__nav li").unbind("mouseenter mouseleave"), $("a.parent").unbind("click").bind("click", function() {
        return $(this).parent("li").toggleClass("hover"), $(this).toggleClass("active"), !1
    })) : ww >= 992 && ($(".header__nav li").removeClass("hover"), $(".header__nav li a").unbind("click"), $("a.parent").removeClass("active").on("click", function() {
        return !1
    }), $(".header__nav li").unbind("mouseenter mouseleave").bind("mouseenter mouseleave", function() {
        $(this).toggleClass("hover")
    }))
}
$(document).ready(function() {
    "use strict";
    var e, t, s;
    $(".header__nav a:not(:only-child)").each(function() {
        $(this).addClass("parent")
    }), $(".nav-toggle").on("hover", function() {
        return $(this).toggleClass("active"), $(".header__menu").slideToggle(200), !1
    }), adjustMenu(), $(".ht-field").dropkick({
        mobile: !0
    }), $(".listing-search__property-size").slider({
        range: !0,
        min: 100,
        max: 1e4,
        step: 100,
        values: [100, 1e4],
        slide: function(e, t) {
            $("#property-amount").text(t.values[0] + " - " + t.values[1])
        }
    }), $("#property-amount").text($(".listing-search__property-size").slider("values", 0) + " - " + $(".listing-search__property-size").slider("values", 1)), $(".listing-search__lot-size").slider({
        range: !0,
        min: 100,
        max: 1e4,
        step: 100,
        values: [100, 1e4],
        slide: function(e, t) {
            $("#lot-amount").text(t.values[0] + " - " + t.values[1])
        }
    }), $("#lot-amount").text($(".listing-search__lot-size").slider("values", 0) + " - " + $(".listing-search__lot-size").slider("values", 1)), $(".listing-search__more-btn").on("click", function() {
        return $(this).toggleClass("listing-search__more-btn--show"), $(".listing-search__more-inner").slideToggle(), !1
    }), $(".main-listing__form-more-filter").on("click", function() {
        return $(this).toggleClass("js-hide"), $(this).hasClass("js-hide") ? $(this).text("Plus de filtre") : $(this).text("Moin de filtre"), $(".main-listing__form-expand").slideToggle(), !1
    }), $(".property__accordion").on("click", ".property__accordion-header", function() {
        $(this).next().slideToggle(350), $(this).find(".property__accordion-expand").toggleClass("fa-caret-up fa-caret-down"), $(this).parent().siblings().find(".property__accordion-content").slideUp(350), $(this).parent().siblings().find(".property__accordion-expand").removeClass("fa-caret-up").addClass("fa-caret-down")
    }), $(".property__tab-list").on("hover", ".property__tab", function(e) {
        e.preventDefault(), $(".property__tab").removeClass("property__tab--active"), $(".property__tab-content").removeClass("is-visible"), $(this).addClass("property__tab--active"), $($(this).attr("href")).addClass("is-visible")
    }), $(".form-calculator__submit").on("click", function(e) {
        e.preventDefault(), $(".form-calculator__result").slideToggle(200)
    }), $(".sign-up__textcontent").on("click", ".sign-up__tab", function(e) {
        e.preventDefault(), $(".sign-up__tab").removeClass("is-active"), $(".sign-up__form").removeClass("is-visible"), $(this).addClass("is-active"), $($(this).attr("href")).addClass("is-visible")
    }), $(window).on("scroll", function() {
        $(this).scrollTop() > 300 ? $(".back-to-top").addClass("is-visible") : $(".back-to-top").removeClass("is-visible is-fade-out"), $(this).scrollTop() > 1200 && $(".back-to-top").addClass("is-fade-out")
    }), $(".back-to-top").on("click", function(e) {
        e.preventDefault(), $("html, body").animate({
            scrollTop: 0
        }, 700)
    }), $(".header__user").hover(function() {
        $(".header__user-menu").toggleClass("is-visible")
    }), e = new Date("16 September, 2018 00:00:00"), t = setInterval(function() {
        var s, i, a = {
            days: Math.floor((i = Date.parse(s = e) - Date.parse(new Date)) / 864e5),
            hours: Math.floor(i / 36e5 % 24),
            minutes: Math.floor(i / 6e4 % 60),
            seconds: Math.floor(i / 1e3 % 60),
            total: i
        };
        $(".days").html(a.days), $(".hours").html(("0" + a.hours).slice(-2)), $(".minutes").html(("0" + a.minutes).slice(-2)), $(".seconds").html(("0" + a.seconds).slice(-2)), a.total <= 0 && clearInterval(t)
    }, 1e3), $(".map-container--sticky").length > 0 && Stickyfill.add($(".map-container--sticky")[0])
}), $(window).on("resize orientationchange", function() {
    ww = document.body.clientWidth, adjustMenu()
});
$("#bien_ville").empty();
var data = {
    id: 1,
    text: 'Barn owl'
};
$('#bien_pays').append('<option value="0" selected="selected"></option>');
$(document).ready(function() {
    $('#bien_pays').on('change', function(e) {
        var $container = $("#bien_ville");
        $('#bien_pays option[value="0"]').remove();
        $('#bien_ville').empty();
        $.ajax({
            type: 'post',
            url: 'https://limmobilier.tn/compte/les-villes/' + $('#bien_pays').val(),
            cache: !1,
            dataType: 'json',
            beforeSend: function() {
                $('#loading').show()
            },
            success: function(data, textStatus, jqXHR) {
                $.each(data, function(i, item) {
                    $('#loading').hide();
                    $('#bien_ville').append('<option value="' + item.id + '">' + item.label + '</option>');
                    $('#villesDiv').show()
                })
            },
            complete: function() {
                $('#loading').hide()
            }
        })
    })
});

function formatNumber(number) {
    let result = number.replace(/(\d{2})(\d{3})(\d{3})/, '$1 $2 $3');
    $("#registration_form_telephone").val(result)
}
$("#registration_form_telephone").change(function() {
    formatNumber($(this).val())
});
$(".toggle-password").click(function() {
    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
        input.attr("type", "text")
    } else {
        input.attr("type", "password")
    }
});
$('[data-toggle="tooltip"]').tooltip();
$("#newsletter_submit").on("click", function() {
    $.ajax({
        type: 'POST',
        data: 'email=' + $('#newsletter_email').val(),
        url: "newsletter.html",
        dataType: 'json',
        success: function(data) {
            window.location.href = "preference-" + data + ".html"
        }
    })
});
$(document).ready(function() {
    $('.js-example-basic-single').select2()
});
$('#listing-gouvernorat').change(function() {
    $.ajax({
        type: 'POST',
        data: 'gouvernorat=' + $('#listing-gouvernorat').val(),
        url: "/listing-delegation.html",
        dataType: 'json',
        success: function(data) {
            $('#listing-delegation').empty();
            $('#listing-city').empty();
            $('#listing-delegation').append($('<option>', {
                value: '',
                text: ''
            }));
            $.each(data.delegations, function(i, item) {
                $('#listing-delegation').append($('<option>', {
                    value: item.id,
                    text: item.label
                }))
            });
            $('#listing-city').append($('<option>', {
                value: '',
                text: ''
            }))
        }
    })
});
$('#listing-delegation').change(function() {
    $.ajax({
        type: 'POST',
        data: 'delegation=' + $('#listing-delegation').val(),
        url: "/listing-villes.html",
        dataType: 'json',
        success: function(data) {
            $('#listing-city').empty();
            $('#listing-city').append($('<option>', {
                value: '',
                text: ''
            }));
            $.each(data, function(i, item) {
                $('#listing-city').append($('<option>', {
                    value: item.id,
                    text: item.label
                }))
            })
        }
    })
});
$(".listing__favorite").on("click", function() {
    $.ajax({
        type: 'POST',
        data: 'id=' + $(this).data('id'),
        url: "/favorite",
        dataType: 'json',
        success: function(data) {
            if (data == 'succes') {
                $.growl.notice({
                    message: "Annonce ajouté a votre liste"
                })
            }
        }
    })
});

$("#newsletter_submit").on("click", function() {
    $.ajax({
        type: 'POST',
        data: 'email=' + $('#newsletter_email').val(),
        url: "{{ path('newsletter') }}",
        dataType: 'json',
        success: function(data) {
            window.location.href = "preference-" + data + ".html"
        }
    })
})