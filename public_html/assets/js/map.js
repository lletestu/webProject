function initialize() {
    var mapOptions = {
        zoom: 13,
        center: new google.maps.LatLng(48.8517530,2.2870950),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        streetViewControl:false,
        mapTypeControl:false,
        disableDoubleClickZoom:false
    }
    var map = new google.maps.Map(document.getElementById('map-canvas'),
        mapOptions);

    setMarkers(map, id_localisation);
}

/**
 * Data for the markers consisting of a name, a LatLng and a zIndex for
 * the order in which these markers should display on top of each
 * other.
 */
var id_localisation = [
    ['id 1', 48.8517530,2.2870950]
];

function setMarkers(map, locations) {
    var image = {
        url: 'pin.png',
        // This marker is 20 pixels wide by 32 pixels tall.
        size: new google.maps.Size(42, 55),
        // The origin for this image is 0,0.
        origin: new google.maps.Point(0,0),
        // The anchor for this image is the base of the flagpole at 0,32.
        anchor: new google.maps.Point(0, 32)
    };

    var shape = {
        coord: [1, 1, 1, 20, 18, 20, 18 , 1],
        type: 'poly'
    };
    for (var i = 0; i < locations.length; i++) {
        var beach = locations[i];
        var myLatLng = new google.maps.LatLng(beach[1], beach[2]);
        var marker1 = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: beach[0]
        });

        attachSecretMessage(marker1,i);
    }
}

function attachSecretMessage(marker, num) {
    var message = ['37 quai de grennelle - 750015 Paris'];
    var infowindow = new google.maps.InfoWindow({
        content: message[num]
    });

    google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(marker.get('map'), marker);
    });
}

google.maps.event.addDomListener(window, 'load', initialize);