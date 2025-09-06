var ww = document.body.clientWidth;

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
$("#filter").on("click", function() {
    if ($(".main-listing__widget").css('display') == 'block') {
        $(".main-listing__widget").css('display', 'none')
    } else if ($(".main-listing__widget").css('display') == 'none') {
        $(".main-listing__widget").css('display', 'block')
    }
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