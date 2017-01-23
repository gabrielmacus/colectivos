<script>




    function initMap() {


        function loadData(callback) {
            $.ajax({
                method:"get",
                url:"paradas-data.php?act=list&<?php echo $_SERVER['QUERY_STRING'];?>",
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


        $(document).ready(

            function () {
                loadData(function (data) {
                    var template=$("#template");

                    template.replaceWith(Mustache.render(template.html(),{"paradas":data}));


                    $(".map").each(function () {

                        var lat =$(this).data("lat");
                        var lng =$(this).data("lng");

                        console.log(lat);

                        var map = new google.maps.Map($(this)[0], {
                            zoom: 17,
                            center:{lat:lat,lng:lng}
                        });

                        var marker =new google.maps.Marker({
                            map:map,
                            position:{lat:lat,lng:lng}


                        });


                    });




                });



            }
        );



    }


</script>
<section class="resumen">
    <header>
        <h2>Paradas</h2>
    </header>
    <div>
    <ul >


        <script id="template" type="application/x-mustache">





                {{#paradas}}
               <li>
           <h3>{{paradaReverse}}</h3>


                {{setPoint}}



           <div id="map{{paradaId}}" class="map" data-lat="{{paradaLat}}" data-lng="{{paradaLng}}">

           </div>

           <ul>
           {{#lineas}}
               <li>
                   <span>{{numero}} {{nombre}}</span>
               </li>
            {{/lineas}}
          
           </ul>

           <p>
                  {{paradaDescripcion}}
           </p>

       </li>
           {{/paradas}}

           {{^paradas}}
           <div class="info-msg">
              <h3>No hay paradas disponibles</h3>
           </div>

           {{/paradas}}
        </script>


    </ul>
    </div>   
</section>

