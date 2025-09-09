
$(":input").on("change", function () {
    $(".main-listing-list").empty();
    $(".pagination").empty();
    $(".loading").show();
    $.ajax({
        type: 'POST', // Le type de la requête HTTP, ici devenu POST
        data: 'offre={% if(offre is defined) %}{{offre}}{% endif %}&type=' + $('input[name$="property-type"]:checked').val() + '&gouvernorat=' + $('#listing-gouvernorat').val() + '&delegation=' + $('#listing-delegation').val() + '&ville=' + $('#listing-city').val() + '&chambres=' + $('#listing-bedroom').val() + '&prixMin=' + $('#min-prix').val() + '&prixMax=' + $('#max-prix').val() + '&surfaceMin=' + $('#min-area').val() + '&surfaceMax=' + $('#max-area').val() + '&keyWord=' + $('#main-listing-keyword').val() + '&sort=' + $('#sort-type').val(),
        url: "/recherche-annonce.html",
        dataType: 'json', // ** ensure you add this line **
        success: function (data) { // code_html contient le HTML renvoyé
            var result = jQuery.parseJSON(JSON.stringify(data));
            $(".listing-sort__list").html(data.length + ' résultats');
            $(".loading").hide();
            jQuery.each(data, function (index, item) {
                var html = '<div class="col-md-4 item-grid__container"><div class="listing-homepage"><div class="item-grid__image-container style-hover-zoom clearfix"><a href="https://limmobilier.tn/annonce/' + item.slug + '-' + item.id_1 + '.html"><div class="item-grid__image-overlay" style="background: url(\'/uploads/photos/263x175/' + item.photo + '\');width: 100%;height: 175px;background-position: 50% 50%;background-repeat: no-repeat;background-size: cover;"></div></a></div><div class="absolute"><ul class="property-status"><li class="property-status-item property-status-rent"><span>' + item.tlabel + '</span></li></ul><div class="property-group-label"><span class="label-featured label label-success">' + item.offre_2 + '</span></div></div><div class="item-grid__content-container"><div class="listing__content"><div class="listing__header"><div class="listing__header-primary"><div><h3 class="listing__title  h-70"><a href="https://limmobilier.tn/annonce/' + item.slug + '-' + item.id_1 + '.html">' + item.label_2 + '</a></h3></div><span class="listing__type"><a href="#">' + item.delegation + '</a></span><div class="property-price"><p class="listing__price">' + item.prix_3 + ' DT</p></div></div></div><div class="flex-center space-beetween pb12 list-details"><div class="clearfix"><div class="property-meta"><ul class="property-meta-list list-inline">';
                if (item.nbrPieces) {
                    html += '<li class="property-label-bedrooms" data-toggle="tooltip" data-placement="top" title="" data-original-title="Pièces"><i class="icon-property-bedrooms"></i><span class="label-content">' + item.nbrPieces + ' pièces</span></li>';
                }
                if (item.surface_3) {
                    html += '<li class="property-label-areasize" data-toggle="tooltip" data-placement="top" title="" data-original-title="Surface" aria-describedby="tooltip386929"><i class="icon-property-areasize"></i><span class="label-content">' + item.surface_3 + ' m<sup>2</sup></span></li>';
                }
                html += '</ul></div></div><a href="#"><img itemprop="logo" src="/public/uploads/logos/' + item.logo + '" alt="" loading="lazy" width="50" height="50" itemprop="logo" style="width: 50px; height: 50px; object-fit: contain;"></a></div></div></div></div></div>';

                $(".main-listing-list").prepend(html);
            });
        }
    });
});