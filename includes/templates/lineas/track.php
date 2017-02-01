
<script>
    var map;
    var marker;
    var paradas=[];


    function loadData(callback) {



        $.ajax({
            method:"get",
            url:"lineas-data.php?act=list&<?php echo $_SERVER['QUERY_STRING'];?>",
            dataType:"json",
            success:function(res)
            {
                console.log(res.data);
                if(callback)
                {
                    callback(res.data);
                }


            },
            error:function(err)
            {
                throw err;
            }
        });

    }

    function setPosition()
    {
        $.ajax(
            {
                method:"get",
                url:"<?php echo $urlSave;?>&id=<?php echo $id ?>",
                dataType:"json",
                success:function(res)
                {
                    var position=new google.maps.LatLng(parseFloat(res.data.lat), parseFloat(res.data.lng));

                    if(!map.getCenter())
                    {
                        map.setCenter(position);
                    }

                    marker.setPosition(position);


                    var i=0;
                    var times=0;
                    var distances=0;
                    function calculate()
                    {

                        var parada=paradas[i];

                        if(parada)
                        {

                            calculateDistance(position,parada.marker.getPosition(),function(data){

                                var distance=data.rows[0].elements[0].distance.value;
                                var time=data.rows[0].elements[0].duration.value;


                                 times+=time;
                                 distances+=distance;

                                time=(times/60).toFixed(2);
                                distance=(distances/1000).toFixed(2);

                                if(distance<=0.25)
                                {
                                    $("#"+parada.id+" .status").html("Llegando");
                                }
                                else
                                {
                                    $("#"+parada.id+" .status").html(time+" minutos");
                                }


                                $("#"+parada.id+" .distance").html(distance+" km");



                                position=paradas[i].marker.getPosition();
                                i++;
                                calculate();


                            })
                        }
                    }


                    calculate();







                },
                error:function(err)
                {
                    console.log(err);
                }
            }
        );

    }
/*
* ,
 transitOptions: {
 modes: [google.maps.TransitMode.BUS]
 }*/

    function calculateDistance(origin,destination,cb)
    {    var service = new google.maps.DistanceMatrixService();

        service.getDistanceMatrix(
            {
                origins:[origin],
                destinations:[destination],
                travelMode: google.maps.TravelMode.DRIVING ,
                transitOptions: {
                    modes: [google.maps.TransitMode.BUS]
                }
            }, function(response){

                if(cb)
                {
                    cb(response);
                }


            }
        );

    }
    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++ ) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }
    function traceRoute(origin,destination,map)
    {
        var directionsService = new google.maps.DirectionsService;

        directionsService.route({
            origin: origin,
            destination:destination,
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
               /* strokeColor: getRandomColor(),*/strokeColor:'#1abc9c',
                strokeOpacity: 0.7,
                strokeWeight: 2.5
            });

            assumedPath.setMap(map); // Set the path object to the map

        });
    }
    function initMap()
    {

        loadData(function(res) {




            var template = $("#template");




            template.replaceWith(Mustache.render(template.html(),{"linea":res[0]}));


            map =new google.maps.Map(
                document.querySelector("#map")
                ,{

                    zoom: 15
                }
            );

            marker=new google.maps.Marker(
                {
                    map:map,
                   icon:"icons/bus40x40.png"
                }
            );
            var i=0;

            $.each(res[0].paradas,function(k,v){


                var origen=res[0].paradas[i];
                var destino=res[0].paradas[i+1];

                i++;

                if(destino)
                {
                  traceRoute({lat:parseFloat(origen.lat),lng:parseFloat(origen.lng)},{lat:parseFloat(destino.lat),lng:parseFloat(destino.lng)},map);

                }
                else
                {
                    traceRoute({lat:parseFloat(origen.lat),lng:parseFloat(origen.lng)},{lat:parseFloat(res[0].paradas[0].lat),lng:parseFloat(res[0].paradas[0].lng)},map);

                }






                var parada={marker:new google.maps.Marker(
                    {
                        map:map,
                        icon:"icons/parada.png"

                    }),id: v.id};

                parada.marker.setPosition({lat: parseFloat(v.lat),lng: parseFloat(v.lng)});

                paradas.push(parada);



            });









            <?php
            if($isTracking)
             {?>


            setPosition();
            window.setInterval(function(){
                setPosition();

            },1500);
            <?php
            }

            else
            {
              ?>



            navigator.geolocation.watchPosition(watchPosition,function(err)
            {

            },{  
                enableHighAccuracy: true,
                maximumAge: Infinity});
            <?php
            }?>


        });



    }

    function watchPosition(res)
    {
        var position={lat: res.coords.latitude, lng: res.coords.longitude};

        console.log(res);
        $("#data").append("<br>"+position.lat+" "+position.lng);
        if(!map.getCenter())
        {
            map.setCenter(position);
        }

        marker.setPosition(position);

        $.ajax(
            {
                method:"post",
                url:"<?php echo $urlSave;?>&id=<?php echo $id ?>",
                data:position,
                dataType:"json",
                success:function(res)
                {

                    //$("h1").html(position.lat+" "+position.lng);

                },
                error:function(err)
                {
                    console.log(err);
                }
            }
        );



    }



    function selectParada(id,e)
    {

        $.each(paradas,function(k,v)
        {
            v.marker.setIcon("icons/parada.png");


        });



        if(!$(e).hasClass("active"))
        {
            $(".parada").removeClass("active");

            var parada = paradas.filter(function(el)
            {
                return el.id==id;
            })[0];

            parada.marker.setIcon("icons/marker.png");
            $(e).addClass("active");
            map.setZoom(17);
            map.setCenter(parada.marker.getPosition());
        }
        else
        {
            $(".parada").removeClass("active");
            map.setZoom(15);
        }


    }

</script>

<script  id="template" type="application/x-mustache">

{{#linea}}
<?php if($isTracking)
    {
        ?>
        <h2 class=" w3-padding" style="background:#3FC380;color:white">Posicion de la linea {{lineaNumero}} {{lineaNombre}}</h2>


        <?php
    }
    else
    {
        ?>
        <h2  class=" w3-padding" style="background:#3FC380;color:white">Reportando linea {{lineaNumero}} {{lineaNombre}}</h2>


        <?php
    }?>
<div style="border:0;width: 100%;height: 300px"  id="map"></div>
 <ul style="list-style:none;padding:0">
 {{#paradas}}
 <?php if($isTracking)
{
 ?>
     <li  id="{{id}}" class=" w3-margin-bottom"><span  onclick="selectParada({{id}},this)" style="background:#F4D03F;width:100%" class="w3-tag parada  w3-text-black  w3-padding">
     <i class="w3-text-red fa fa-map-marker" aria-hidden="true"></i>
 {{reverse}}</span>

 <div class="w3-row w3-padding">
 <i style="font-size:20px;position:relative;top:2px" class="fa fa-clock-o" aria-hidden="true"></i>
<strong class="status"></strong>, <i style="font-size:20px;position:relative;top:2px" class="fa fa-location-arrow" aria-hidden="true"></i>
 <strong style="font-weight:300" class="distance"></strong>
 </div>

 </li>
    <?php
}
    else
    {
     ?>

         <li id="{{id}}"   class=" w3-margin-bottom"><span onclick="selectParada({{id}},this)" style="background:#BE90D4;width:100%" class="parada w3-tag  w3-text-black  w3-padding-large"><i class="w3-text-red fa fa-map-marker" aria-hidden="true"></i>
 {{reverse}}</span></li>

        <?php
    }


    ?>





 {{/paradas}}
</ul>
{{/linea}}


</script>

