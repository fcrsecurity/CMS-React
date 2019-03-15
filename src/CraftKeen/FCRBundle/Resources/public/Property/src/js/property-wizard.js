/**
 * Initialize Collection for Vacancy List
 */
$('.vacancyList-collection').collection({
    drag_drop: true
});

/**
 * Initialize Collection for Gallery Media Items
 */
$('.media-gallery-collection').collection({
    drag_drop: true,
    after_add: function (collection, element) {
        var id = $(element).attr('id');
        var eidm = id.replace(id.split("_").pop(), "image");
        var inputs = $(element).find('input');
        $(inputs[0]).on("click", function () {
            var childWin = window.open("/elfinder/form?id=" + eidm, "popupWindow", "height=450, width=900");
        });
    }
});

/**
 * Renders Google Map Preview.
 * @param float lat
 * @param float lng
 * @param integer zoom
 */
function renderPreviewMap(lat, lng, zoom) {
    var mapHeight = $(".main-column").height() - $(".geo-column").height();
    $('#google_map').height(mapHeight);
    initMap(lat, lng, zoom);
}

/**
 * Get Latitude and Longitude by address from form fields
 */
function getLatLngByAddress() {
    var address = $("#craftkeen_fcrbundle_property_edit_details_geoAddress1").val();
    address += $("#craftkeen_fcrbundle_property_edit_details_geoAddress2").val();
    address += ', ' + $("#craftkeen_fcrbundle_property_edit_details_geoCity").val();
    address += ', ' + $("#craftkeen_fcrbundle_property_edit_details_geoProvince").val();
    address += ', ' + $("#craftkeen_fcrbundle_property_edit_details_geoPostal").val();
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({'address': address}, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            var latitude = results[0].geometry.location.lat();
            var longitude = results[0].geometry.location.lng();
            $("#craftkeen_fcrbundle_property_edit_details_geoLat").val(latitude);
            $("#craftkeen_fcrbundle_property_edit_details_geoLng").val(longitude);
            initMap(latitude, longitude, 10)
        }
    });
}

/**
 * Custom Callback for heroImage field. update preview image, when image selected.
 *
 * @param object file
 * @returns void
 */
function heroImageChangeCallback(file, item) {
    $('.heroImage-preview').attr('src', file.url);
    $('.hero-image').css("background", "{{ hero_overlay }}, url(" + file.url + ") no-repeat center center scroll");
}

/**
 * Custom Callback for Thumbnail field. update preview image, when image selected.
 *
 * @param object file
 * @returns void
 */
function thumbnailChangeCallback(file, item) {
    $('.thumbnail-preview').attr('src', file.url);
}

/**
 * Custom Callback for Media Gallery image field.
 *
 * @param object file
 * @returns void
 */
function galleryImageChangeCallback(file, item) {
    var galleryImageId = $(item).attr('id');
    $('#' + galleryImageId).attr('src', file.url);
}

$(function () {
    $('.rejectionComment').hide().prop('disabled', true);
    $('.btn-reject').on('click', function (e) {
        if ($('.rejectionComment').val().length == 0) {
            e.preventDefault();
        }
        $('.rejectionComment').prop('disabled', false).fadeIn();
    });
});
