<!DOCTYPE html>
<html>
<head>
    <script
        src="https://code.jquery.com/jquery-2.2.4.js"
        integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="
        crossorigin="anonymous"></script>
</head>
<body>
<div style="height: 300px" id="map"></div>
<script>
    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++ ) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }
    function initMap() {
        // Create a map object and specify the DOM element for display.
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 14
        });

        var points=[];


        navigator.geolocation.watchPosition(function(position)
        {


            points.push(new google.maps.Marker({
            position:{lat:position.coords.latitude,lng:position.coords.longitude},
            map:map

        }));



            map.setCenter({lat:position.coords.latitude,lng:position.coords.longitude});
        });


        map.addListener('click', function(e) {

            points.push(new google.maps.Marker(
                {
                    map:map,
                    position:e.latLng
                }
            ));


        });

        var i=0;
        var times="";

        $("#calculate").on("click",calculate);

        var directionsService = new google.maps.DirectionsService;


        function calculate()
        {
            console.log(points);

            var pointOrigin =points[i];
            var pointDestination=points[i+1];
            if(pointOrigin && pointDestination)
            {


                var service = new google.maps.DistanceMatrixService();

                service.getDistanceMatrix(
                    {
                        origins:[pointOrigin.getPosition()],
                        destinations:[pointDestination.getPosition()],
                        travelMode: google.maps.TravelMode.DRIVING,
                        transitOptions: {
                            modes: [google.maps.TransitMode.BUS]
                        }
                    }, function(response){

                        console.log(response);

                        i++;

                        directionsService.route({
                            origin: pointOrigin.getPosition(),
                            destination:pointDestination.getPosition(),
                            travelMode: 'DRIVING'
                        }, function(response, status) {


                            var directionsDisplay = new google.maps.DirectionsRenderer({
                                suppressMarkers: true,
                                map: map,
                                directions: response,
                                draggable: false,
                                suppressPolylines: true,
                                // IF YOU SET `suppressPolylines` TO FALSE, THE LINE WILL BE
                                // AUTOMATICALLY DRAWN FOR YOU.
                            });


                            pathPoints = response.routes[0].overview_path.map(function (location) {
                                return {lat: location.lat(), lng: location.lng()};
                            });

                            var assumedPath = new google.maps.Polyline({
                                path: pathPoints, //APPLY LIST TO PATH
                                geodesic: true,
                                strokeColor: getRandomColor(),
                                strokeOpacity: 0.7,
                                strokeWeight: 2.5
                            });

                            assumedPath.setMap(map); // Set the path object to the map



                        });




                        times+="<br>De "+response.originAddresses[0]+" hasta "+response.destinationAddresses[0]+":"+response.rows[0].elements[0].duration.text;
                        calculate();




                    }
                );

            }
          $("#response").html(times);

        }



    }


</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDvx6X4xIXCsdiOtDRBge3DuO7i4FgMyc8&callback=initMap"
        async defer></script>
<h4 id="response"></h4>
<button id="calculate">Calcular tiempo de recorrido</button>
</body>
</html>