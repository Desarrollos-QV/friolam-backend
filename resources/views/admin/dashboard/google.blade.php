
  
<div class="row">
    <div class="col-12 col-lg-12 mx-auto">
        <div class="card radius-10" style="height: 680px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-0">Seguimiento en vivo </h6>
                    </div>
                </div>

                <div class="chart-container-2  mt-4">
                  <div id="map" style="width:100%;height:600px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
  let lat = 0;
  let lng = 0;

  var map;
  let markers = [];
  var ready = false;
  var numDeltas = 100;

  function initMap() {
    var infowindow = new google.maps.InfoWindow();
    var infowindowContent = document.getElementById('infowindow-content');

    var geocoder = new google.maps.Geocoder;
    navigator.geolocation.getCurrentPosition(
      (position) => {
          lat = position.coords.latitude;
          lng = position.coords.longitude;
          map = new google.maps.Map(
              document.getElementById('map'),
              {
                center: {lat: lat, lng: lng},
                zoom: 10,
                disableDefaultUI: true
              }
          );
          map.controls[google.maps.ControlPosition.TOP_LEFT];

          infowindow.setContent(infowindowContent);
          // Consultamos movimiento de repartidores
          addMark();
          setInterval(() => {
            addMark();
          }, 4000);

          // Generamos un cronometro para que el navegador no se sobreCargue de 10 minutos
          setTimeout(() => {
            location.reload()
          }, 600000);
      },
      () => {
        handleLocationError(true, infoWindow, map.getCenter());
      }
    );
  }

  function addMark(){
    // Consultamos los Repartidores
    $.ajax({
      async: true,
      type:'GET',
      url:'https://boxi.grupoorus.mx/api/getAllStaffs',
      success: function(resp) {
        
          if (resp.data.length > 0) {
            let staffs = resp.data;

            if (ready == true) { // ya se cargaron los markes por primera vez
              /**
               * 
               * Verificamos si hay algun cambio en las coordenadas de algun marker
               *  
              */
              for (var i = 0; i < markers.length; i++) {
                let element = markers[i];

                for (let x = 0; x < staffs.length; x++) {
                  const origins = staffs[x];
                  
                  
                  if (element.id_staff == origins.id) {

                    let init_pos = Math.abs(element.lat) + Math.abs(element.lng);
                    let end_pos  = Math.abs(origins.lat) + Math.abs(origins.lng);
                    
                  
                    if (init_pos != end_pos) { // El repa cambio de posicion
                      
                      element.setMap(null);
                      markers.splice(i,1);
                      var location = new google.maps.LatLng(origins.lat, origins.lng);
                      
                      const marker = new google.maps.Marker({
                        position: location,
                        map: map,
                        title: origins.name,
                        icon: "https://dash.kito-app.com.mx/assets/img/d.png",
                        lat: origins.lat, 
                        lng: origins.lng,
                        id_staff: origins.id
                      });

                      markers.push(marker);
                      
                      var infowindow = new google.maps.InfoWindow({
                          content: ''
                      });
            
                      //contenido de la infowindow
                      var content='<div id="content" style="width: auto; height: auto;"><b>Repartidor(a)</b> <br />' + origins.name + '</div>';   

                      google.maps.event.addListener(marker, 'click', function(marker, content, infowindow) {
                        return function(){
                            infowindow.setContent(content); //asignar el contenido al globo
                            infowindow.open(map, marker); //mostrarlo
                        };            
                      }(marker,content,infowindow));
                    }
                  }
                }
              }
            }else {
              for (let x = 0; x < staffs.length; x++) {
                const element = staffs[x];
                var location = new google.maps.LatLng(element.lat, element.lng);
                
                const marker = new google.maps.Marker({
                  position: location,
                  map: map,
                  title: element.name,
                  icon: "https://dash.kito-app.com.mx/assets/img/d.png",
                  lat: element.lat, 
                  lng: element.lng,
                  id_staff: element.id
                });

                markers.push(marker);
                
                var infowindow = new google.maps.InfoWindow({
                    content: ''
                });
      
                //contenido de la infowindow
                var content='<div id="content" style="width: auto; height: auto;"><b>Repartidor(a)</b> <br />' + element.name + '</div>';   

                google.maps.event.addListener(marker, 'click', function(marker, content, infowindow) {
                  return function(){
                      infowindow.setContent(content); //asignar el contenido al globo
                      infowindow.open(map, marker); //mostrarlo
                  };            
                }(marker,content,infowindow));
                
              }
            }

            ready = true;
          }

      }
    });
  }

</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{$ApiKey}}&libraries=places&callback=initMap"></script>