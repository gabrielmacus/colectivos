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
    <header>
        <h2>Lineas</h2>
    </header>
    <div>
        <ul >


            <script id="template" type="application/x-mustache">

                {{#lineas}}
               <li>
           <h3>{{lineaNombre}} {{lineaNumero}}</h3>

           <p>
                  {{lineaDescripcion}}
           </p>
           <a href="lineas-seguimiento.php?id={{lineaId}}">Seguimiento</a>
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

