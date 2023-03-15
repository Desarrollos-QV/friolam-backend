
<input id="pac-input" class="controls form-control" name="address" value="{{ $data->address }}" type="text" placeholder="Ingresa la ubicación de la sucursal">

<div class="form-group col-md-6"><input type="text" hidden name="lat" id="lat" class="form-control" required placeholder="Latitude" value="{{ $data->lat }}"></div>
<div class="form-group col-md-6"><input type="text" hidden name="lng" id="lng" class="form-control" required placeholder="Longitude" value="{{ $data->lng }}"></div>

    <div id="map" style="width:100%;height:500px;"></div>
    <div id="infowindow-content">
      <span id="place-name"  class="title"></span>
      <span id="place-id"></span><br>
      <span id="place-address"></span>
    </div>
 
<script>
function initMap() {
    var map;
    var marker; 
    var input = document.getElementById('pac-input');
    var autocomplete = new google.maps.places.Autocomplete(input);
    var infowindow = new google.maps.InfoWindow();
    var infowindowContent = document.getElementById('infowindow-content');
    var lat2 = parseFloat(document.getElementById('lat').value);
    var lng2 = parseFloat(document.getElementById('lng').value);
    var name = document.getElementById('pac-input').value;

    if(name.length == 0){
        var geocoder = new google.maps.Geocoder;
        navigator.geolocation.getCurrentPosition(
          (position) => {
              lat = position.coords.latitude;
              lng = position.coords.longitude;
              map = new google.maps.Map(
                  document.getElementById('map'),
                  {
                    center: {lat: lat, lng: lng},
                    zoom: 12,
                    disableDefaultUI: true
                  }
              );
 

              input.focus();
              autocomplete.bindTo('bounds', map);

              // Specify just the place data fields that you need.
              autocomplete.setFields(['place_id', 'geometry', 'name', 'formatted_address']);

              // map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

              infowindow.setContent(infowindowContent);

              marker = new google.maps.Marker({map: map});
              marker.addListener('click', function() {
                infowindow.open(map, marker);
              });
          },
          () => {
            handleLocationError(true, infoWindow, map.getCenter());
          }
        );

    } else {
        var geocoder = new google.maps.Geocoder;
        navigator.geolocation.getCurrentPosition(
          (position) => {
              lat = position.coords.latitude;
              lng = position.coords.longitude;
              map = new google.maps.Map(
                  document.getElementById('map'),
                  {
                    center: {lat: lat2, lng: lng2},
                    zoom: 10,
                    disableDefaultUI: true
                  }
              );
 
              //input.focus();
              autocomplete.bindTo('bounds', map);

              // Specify just the place data fields that you need.
              autocomplete.setFields(['place_id', 'geometry', 'name', 'formatted_address']);

              // map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

              infowindow.setContent(infowindowContent);

              marker = new google.maps.Marker({map: map});
              marker.addListener('click', function() {
                infowindow.open(map, marker);
              });
          },
          () => {
            handleLocationError(true, infoWindow, map.getCenter());
          }
        );
    }

    autocomplete.addListener('place_changed', function() {
      infowindow.close();
      var place = autocomplete.getPlace();

      if (!place.place_id) {
        return;
      }
      geocoder.geocode({'placeId': place.place_id}, function(results, status) {
        if (status !== 'OK') {
          window.alert('Geocoder failed due to: ' + status);
          return;
        }

        map.setZoom(13);
        map.setCenter(results[0].geometry.location);

        // Set the position of the marker using the place ID and location.
        marker.setPlace(
            {placeId: place.place_id, location: results[0].geometry.location});

        marker.setVisible(true);

        infowindowContent.children['place-address'].textContent = results[0].formatted_address;
 
        document.getElementById('lat').value = results[0].geometry.location.lat();
        document.getElementById('lng').value = results[0].geometry.location.lng();
        infowindow.open(map, marker);
      });
    });
  }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{$ApiKey}}&libraries=places&callback=initMap"></script>
