<script>



    var marker;

    $(document).ready(function () {
        loadLineas();

    });

    function loadLineas() {
        $.ajax(
            {
                method:"get",
                url:"lineas-data.php?act=list",
                dataType:"json",
                async:false,
                success:function (res) {

                    var lineas= $("#lineas");
                    $.each(res.data,function (k,v) {

                        lineas.append("<option value="+v.lineaId+">"+v.lineaNumero+"  "+v.lineaNombre+"</option>");
                    });

                },
                error:function (err) {

                    console.log(err);
                }
            }
        );
    }

    function initMap() {
        $(document).on("submit",".add form",function (e) {
            $.ajax(
                {
                    method:"post",
                    url:"<?php echo $urlSave; ?>",
                    data:$(this).serialize(),
                    dataType:"json",
                    success:function (res) {
                        console.log(res);
                    },
                    error:function (err) {
                        console.log(err);
                    }
                }
            );
            e.preventDefault();
        });


        var map = new google.maps.Map(document.getElementById("map"), {
            zoom: 8,
            center:{lat:-31.729,lng:-60.5401}
        });


        <?php if($isEdit)
{
 ?>



        $.ajax(
            {
                method:"get",
                url:"paradas-data.php?act=list&id=<?php echo $id;?>",
                data:$(this).serialize(),
                dataType:"json",
                success:function (res) {
                    var data =res.data[0];

                    marker = new google.maps.Marker(
                        {
                            map:map,
                            position:{lat:parseFloat(data.paradaLat),lng:parseFloat(data.paradaLng)}
                        }
                    );

                    $.each(data,function (k,v) {



                        if(Array.isArray(v))
                        {

                            var ids=[];
                            $.each(v,function (k,v) {


                                ids.push(v.id);

                            });


                            $("[name='"+k+"[]']").val(ids);

                        }
                        else
                        {
                            $("[name="+k+"]").val(v);


                        }



                    })
                },
                error:function (err) {
                    console.log(err);
                }
            }
        );



        <?php
        }else
        {
        ?>
       var  marker = new google.maps.Marker({map: map});
        <?php
        }?>



        var geocoder = new google.maps.Geocoder;



        map.addListener('click', function(event) {
            marker.setPosition(event.latLng);


            $("[name=paradaLat]").val(event.latLng.lat());
            $("[name=paradaLng]").val(event.latLng.lng());

            geocoder.geocode({'location':  {lat:event.latLng.lat(), lng:event.latLng.lng()}}, function(results, status) {

                console.log(results);
                console.log(status);

                if (status === google.maps.GeocoderStatus.OK) {
                    if (results[1]) {

                        $("[name=paradaReverse]").val(results[0].formatted_address);

                    } else {

                    }
                } else {

                }
            });






        });
    }
</script>
<section class="add">
    <h2>Nueva parada</h2>
    <form>

        <div id="map" class="map">

        </div>
        <div>
            <div>
                <input name="paradaLat" hidden>
                <input name="paradaLng" hidden>
                <input name="paradaReverse" readonly>

            </div>
        </div>
       <!-- <div>
            <label>Lineas que paran en ella</label>
            <select id="lineas" multiple name="lineas[]">


            </select>
        </div>-->
        <div>
            <label>Descripcion</label>
           <textarea name="paradaDescripcion"></textarea>
        </div>
        <div>
            <button type="submit">Aceptar</button>
        </div>
    </form>
</section>