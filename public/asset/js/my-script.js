$('.select2-selection__rendered').select2({
    placeholder: 'Select an option'
});


/* main menu mobile */
document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const mainMenu = document.querySelector('#pbr-mainmenu');

    // --- Toggle the main mobile menu ---
    if (menuToggle && mainMenu) {
        menuToggle.addEventListener('click', function () {
            // Toggle the class. CSS will handle the rest.
            mainMenu.classList.toggle('is-open');

            // Update the aria-expanded attribute for accessibility
            const isOpen = mainMenu.classList.contains('is-open');
            this.setAttribute('aria-expanded', isOpen);
        });
    }

    // --- NEW: Toggle dropdown sub-menus (.level-1) on mobile ---
    const dropdownToggles = document.querySelectorAll('#pbr-mainmenu .dropdown > a');

    dropdownToggles.forEach(function (toggle) {
        toggle.addEventListener('click', function (event) {
            // Only activate this behavior on mobile screen sizes
            if (window.innerWidth <= 768) {
                // Prevent the link from being followed on the first tap
                event.preventDefault();

                // Get the parent <li> element
                const parentLi = this.parentElement;

                // Toggle the 'is-open' class on the parent <li>
                if (parentLi) {
                    parentLi.classList.toggle('is-open');
                }
            }
        });
    });
});
/* filter slide */
$(":input").on("change", function () {
    $(".main-listing-list").empty();
    $(".pagination").empty();
    $(".loading").show();
    $.ajax({
        type: 'POST', // Le type de la requête HTTP, ici devenu POST
        data: 'offre={% if(offre is defined) %}{{offre}}{% endif %}&type=' + $('input[name$="property-type"]:checked').val() + '&gouvernorat=' + $('#listing-gouvernorat').val() + '&delegation=' + $('#listing-delegation').val() + '&ville=' + $('#listing-city').val() + '&chambres=' + $('#listing-bedroom').val() + '&prixMin=' + $('#min-prix').val() + '&prixMax=' + $('#max-prix').val() + '&surfaceMin=' + $('#min-area').val() + '&surfaceMax=' + $('#max-area').val() + '&keyWord=' + $('#main-listing-keyword').val() + '&sort=' + $('#sort-type').val(),
        url: "https://test.limmobilier.tn/recherche-annonce.html",
        dataType: 'json', // ** ensure you add this line **
        success: function (data) { // code_html contient le HTML renvoyé
            var result = jQuery.parseJSON(JSON.stringify(data));
            if ($('input[name$="property-type"]:checked').val() != 'ALL') {
                $(".main-listing__tag_type").show();
                $(".main-listing__clear").show();
                $(".main-listing__tag-value_Type").html($('input[name$="property-type"]:checked').val());
            }
            /*if ($('#listing-city').val() != 'ALL') {
             $(".main-listing__tag_city").show();
             var villes =  {#{ villes|json_encode }#};
             //$(".main-listing__tag-value_City").html('{#{villes.0.label}#}');
             }*/
            $(".listing-sort__list").html(data.length + ' résultats');
            $(".loading").hide();
            jQuery.each(data, function (index, item) {
                var html = '<div class="col-md-4 item-grid__container"><div class="listing-homepage"><div class="item-grid__image-container style-hover-zoom clearfix"><a href="/annonce/' + item.slug + '-' + item.id_1 + '.html"><div class="item-grid__image-overlay" style="background: url(\'/uploads/photos/263x175/' + item.photo + '\');width: 100%;height: 175px;background-position: 50% 50%;background-repeat: no-repeat;background-size: cover;"></div></a></div><div class="absolute"><ul class="property-status"><li class="property-status-item property-status-rent"><span>' + item.tlabel + '</span></li></ul><div class="property-group-label"><span class="label-featured label label-success">' + item.offre_2 + '</span></div></div><div class="item-grid__content-container"><div class="listing__content"><div class="listing__header"><div class="listing__header-primary"><div><h3 class="listing__title  h-70"><a href="/annonce/' + item.slug + '-' + item.id_1 + '.html">' + item.label_2 + '</a></h3></div><span class="listing__type"><a href="#">' + item.delegation + '</a></span><div class="property-price"><p class="listing__price">' + item.prix_3 + ' DT</p></div></div></div><div class="flex-center space-beetween pb12 list-details"><div class="clearfix"><div class="property-meta"><ul class="property-meta-list list-inline">';
                if (item.nbrPieces) {
                    html += '<li class="property-label-bedrooms" data-toggle="tooltip" data-placement="top" title="" data-original-title="Pièces"><i class="icon-property-bedrooms"></i><span class="label-content">' + item.nbrPieces + ' pièces</span></li>';
                }
                if (item.surface_3) {
                    html += '<li class="property-label-areasize" data-toggle="tooltip" data-placement="top" title="" data-original-title="Surface" aria-describedby="tooltip386929"><i class="icon-property-areasize"></i><span class="label-content">' + item.surface_3 + ' m<sup>2</sup></span></li>';
                }
                html += '</ul></div></div><a href="#"><img itemprop="logo" src="/public/uploads/logos/' + item.logo + '" loading="lazy" width="50" height="50" itemprop="logo" style="width: 50px; height: 50px; object-fit: contain;"></a></div></div></div></div></div>';

                $(".main-listing-list").prepend(html);
            });
        }
    });
});