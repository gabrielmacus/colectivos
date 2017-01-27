
<script>


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


    $(document).ready(

        function () {
            loadData(function (data) {

                console.log(data);
                var template=$("#template");



                template.replaceWith(Mustache.render(template.html(),{"lineas":data}));




            });



        }
    );



</script>
<section class="resumen">
    <header class="w3-row w3-center">
        <h2>Lineas</h2>
    </header>
    <div  class="w3-row-padding">
        <ul  class="w3-row-padding" style="list-style: none">


            <script id="template" type="application/x-mustache">
                {{#lineas}}
               <li class="w3-col s12 m6 w3-padding-large ">
               <div class="w3-card w3-border-0 w3-margin-bottom w3-white" style="width:100%">
               <header style="background:#4ABC96" class=" w3-padding">
                  <h3>{{lineaNombre}} {{lineaNumero}}</h3>
               </header>
               <div style="overflow:hidden">

                 <iframe class="map-zoom" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d27149.695845265866!2d-60.54144361737079!3d-31.723761798328482!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95b452615e6995f1%3A0x65d7a9af86367918!2sHostel+del+Parque+Urquiza!5e0!3m2!1ses-419!2sar!4v1485549018851" frameborder="0" style="border:0;width:100%;height:200px" allowfullscreen></iframe>


                        </div>
               <footer class="w3-padding">

                  {{^lineaDescripcion}}
                   <p class=" w3-margin-top w3-margin-bottom ">
                     No existe descripcion
                   <p>
                  {{/lineaDescripcion}}

                     {{#lineaDescripcion}}

                  <button style="background:#039BE5" class="w3-btn  w3-margin-top w3-margin-bottom w3-btn-block">Ver descripcion</button>
                     {{/lineaDescripcion}}


                  <div class="w3-row-padding">

                     <a href="lineas-seguimiento.php?id={{lineaId}}" class="w3-col s6 " style="text-align:right">
                      <img src="http://icon-icons.com/icons2/474/PNG/512/magnifier-target_46870.png" style="width:60%">
                     </a>

               <a href="lineas-reportar.php?id={{lineaId}}"class="w3-col s6 " style="text-align:left">
                      <img src="icons/satellite.png" style="width:60%;">
                     </a>
<!--
 <div class="w3-col s3 w3-center">
                      <img src="http://icon-icons.com/icons2/474/PNG/512/magnifier-target_46870.png" style="width:80%">
                     </div>

 <div class="w3-col s3 w3-center">
                      <img src="http://icon-icons.com/icons2/474/PNG/512/magnifier-target_46870.png" style="width:80%">
                     </div>-->

                  </div>





                <a href="lineas-seguimiento.php?id={{lineaId}}">Seguimiento</a>
               <a href="lineas-reportar.php?id={{lineaId}}">Dar ubicacion</a>

                   <a href="lineas-edit.php?id={{lineaId}}">Editar</a>
                              <a href="lineas-data.php?act=del&id={{lineaId}}">Eliminar</a>







               </footer>


               </div>

            </li>
           {{/lineas}}

           {{^lineas}}
         <div class="info-msg">
              <h3>No hay lineas disponibles</h3>
           </div>

           {{/lineas}}



        </script>


        </ul>
    </div>
</section>

