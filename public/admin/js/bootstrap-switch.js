// Class definition

var KTBootstrapSwitch = function () {

    // Private functions
    var demos = function () {
        // minimum setup
        $('[data-switch=true]').bootstrapSwitch({
            onSwitchChange: function (e, state) {
                $(this).is(':checked');
                console.log($(this).is(':checked'));
                $.ajax({
                    type: 'POST', // Le type de la requête HTTP, ici devenu POST
                    data: 'id=' + $(this).data('id') + '&statut=' + $(this).is(':checked'),
                    url: "https://limmobilier.tn/admin/annonces/validate",
                    dataType: 'json', // ** ensure you add this line **
                    success: function (data) { // code_html contient le HTML renvoyé
                        toastr.options = {
                            "closeButton": true,
                            "debug": false,
                            "newestOnTop": true,
                            "progressBar": true,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                    }
                });
            }
        });
    };

    return {
        // public functions
        init: function () {
            demos();
        },
    };
}();

jQuery(document).ready(function () {
    KTBootstrapSwitch.init();
});
