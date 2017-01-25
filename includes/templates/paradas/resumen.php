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
    <header class="w3-padding">
        <h2>Paradas</h2>
    </header>
    <div>
    <ul class="w3-row-padding w3-ul " >


        <script id="template" type="application/x-mustache">

                {{#paradas}}
               <li class="w3-col s12 m6 w3-border-0 ">

                <div class="w3-card-4 w3-container w3-white w3-margin-top">

                   <h3 >{{paradaReverse}}</h3>
           <div id="map{{paradaId}}" class="map" data-lat="{{paradaLat}}" data-lng="{{paradaLng}}">


           </div>

           <ul class="w3-ul" >
           {{#lineas}}
               <li style="padding:0px" class="w3-border-0">
                   <span>{{numero}} {{nombre}}</span>
               </li>
            {{/lineas}}

           </ul>

           <p>
                  {{paradaDescripcion}}
           </p>
     <a href="paradas-edit.php?id={{paradaId}}">Editar</a>
          <a href="paradas-data.php?act=del&id={{paradaId}}">Eliminar</a>
                </div>


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

