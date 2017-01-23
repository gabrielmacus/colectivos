
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

                                console.log(time);
                                if(distance<=0.80)
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


    function calculateDistance(origin,destination,cb)
    {    var service = new google.maps.DistanceMatrixService();

        service.getDistanceMatrix(
            {
                origins:[origin],
                destinations:[destination],
                travelMode: google.maps.TravelMode.DRIVING,
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

            $.each(res[0].paradas,function(k,v){

                console.log(v);
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

</script>


<script  id="template" type="application/x-mustache">

{{#linea}}
<h2>Seguimiento de la linea {{lineaNumero}} {{lineaNombre}}</h2>
<div style="border:0;width: 100%;height: 300px"  id="map"></div>
 <ul>
 {{#paradas}}
    <li id="{{id}}">{{reverse}} <strong class="status"></strong> <strong class="distance"></strong></li>
 {{/paradas}}
</ul>
{{/linea}}


</script>

