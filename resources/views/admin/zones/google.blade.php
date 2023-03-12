
<input type="hidden" id="type" value="{{$type}}">
<input type="hidden" id="coords" value="{{$data->coords}}" name="coords">
<div id="map" style="width:100%;height:500px;"></div>
<div id="infowindow-content">
    <span id="place-name"  class="title"></span>
    <span id="place-id"></span><br>
    <span id="place-address"></span>
</div>
  <script> 
    function initMap() {
      var type = document.getElementById('type');
      var map; 
      let zoneBase;
      let h3index;  
      var geocoder = new google.maps.Geocoder;
      var select_city  = document.getElementById('city_id');
      var city_id      = select_city.value;
      var coords       = [];
      var coords_in    = document.getElementById('coords'); 
      var perimetro;
      var coverage     = document.getElementById('coverage');
      // Pintamos el poligono
      if (type.value == 'new') {
        PrintPol(city_id);
      }else {
        UpdatePol(city_id);
      }
      // Listener para escucha de cambio de ciudad
      select_city.addEventListener('change', function(){
        let newCity = this.options[select_city.selectedIndex];
        city_id     = newCity.value;
        if (type.value == 'new') {
          PrintPol(city_id);
        } 
      });
  
      function PrintPol(city_id)
      {
        $.get('getCoords/'+city_id, function(data) {
            lat = data.lat;
            lng = data.lng;
            let latLng = new google.maps.LatLng(lat, lng);
            map = new google.maps.Map(document.getElementById('map'),
                {
                  center: latLng,
                  zoom: 13,
                  disableDefaultUI: true
            });  
            
            // Convert a lat/lng point to a hexagon index at resolution 6
            h3index = h3.geoToH3(lat, lng, 7); 
            // Get the center of the hexagon
            const hexCenterCoordinates = h3.h3ToGeo(h3index);
            // Get the vertices of the hexagon
            const hexBoundary = h3.h3ToGeoBoundary(h3index);
              
            const triangleCoords = [];  
            // Rellenamos
            for (let p = 0; p < hexBoundary.length; p++) {
              const element = hexBoundary[p];
              triangleCoords.push({
                lat: element[0],
                lng: element[1]
              })
            }
            
            // Define a zoneBase and set its editable property to true.
            zoneBase = new google.maps.Polygon({
              paths: triangleCoords,
              strokeColor: "#FF0000",
              strokeOpacity: 0.8,
              strokeWeight: 2,
              fillColor: "#FF0000",
              fillOpacity: 0.35, 
              draggable: true,
              editable: true,
            });

            
            zoneBase.setMap(map);
  
            zoneBase.addListener("dragend", CreateCoordsArr);
            
            

            google.maps.event.addListenerOnce(map, 'idle', function(){
              GetCoordsArr();
            });
            
            google.maps.event.addListener(zoneBase.getPath(), 'set_at', function(){ 
              GetCoordsArr();
            });

            google.maps.event.addListener(zoneBase.getPath(), 'insert_at', function(){ 
              GetCoordsArr();
            });

        });
      }

      function UpdatePol(city_id)
      {
        $.get('/zones/getCoords/'+city_id, function(data) {
            lat = data.lat;
            lng = data.lng;
            let latLng = new google.maps.LatLng(lat, lng);
            map = new google.maps.Map(document.getElementById('map'),
                {
                  center: latLng,
                  zoom: 14,
                  disableDefaultUI: true
            });  
             
            const triangleCoords = JSON.parse(coords_in.value);  
          
            var bounds = new google.maps.LatLngBounds();
            for (i = 0; i < triangleCoords.length; i++) {
              bounds.extend(triangleCoords[i]);
            }
 
            // Define a zoneBase and set its editable property to true.
            zoneBase = new google.maps.Polygon({
              paths: triangleCoords,
              strokeColor: "#FF0000",
              strokeOpacity: 0.8,
              strokeWeight: 2,
              fillColor: "#FF0000",
              fillOpacity: 0.35, 
              draggable: true,
              editable: true,
            }); 

            zoneBase.setMap(map);
            map.setCenter(bounds.getCenter());
            zoneBase.addListener("dragend", CreateCoordsArr);
            

            google.maps.event.addListenerOnce(map, 'idle', function(){
              GetCoordsArr();
            });
            
            google.maps.event.addListener(zoneBase.getPath(), 'set_at', function(){ 
              GetCoordsArr();
            });

            google.maps.event.addListener(zoneBase.getPath(), 'insert_at', function(){ 
              GetCoordsArr();
            });

        });
      }

      function CreateCoordsArr(event) { 
        const polygon = this;
        const vertices = polygon.getPath(); 
        let coords = [];
        for (let i = 0; i < vertices.getLength(); i++) {
          const xy = vertices.getAt(i); 
          coords.push({
            lat: xy.lat(),
            lng: xy.lng()
          });
        }
        coords_in.value = JSON.stringify(coords);
        perimetro = google.maps.geometry.spherical.computeLength(zoneBase.getPath());
        coverage.value = (perimetro/1000);
      } 

      function GetCoordsArr()
      {
        const vertices = zoneBase.getPath(); 
        let coords = [];
        for (let i = 0; i < vertices.getLength(); i++) {
          const xy = vertices.getAt(i); 
          coords.push({
            lat: xy.lat(),
            lng: xy.lng()
          });
        } 
        coords_in.value = JSON.stringify(coords);
        
        perimetro = google.maps.geometry.spherical.computeLength(zoneBase.getPath());
        coverage.value = (perimetro/1000).toFixed(2);
      }
    }  
  </script>
<script src="https://maps.googleapis.com/maps/api/js?key={{$ApiKey}}&libraries=places&callback=initMap"></script>