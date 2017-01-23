<script>






    function loadParadas() {
        $.ajax(
            {
                method:"get",
                url:"paradas-data.php?act=list",
                dataType:"json",
                async:false,
                success:function (res) {

                    var lineas= $("#paradas");
                    $.each(res.data,function (k,v) {

                        lineas.append("<option value="+v.paradaId+">"+v.paradaReverse+"</option>");
                    });

                    $( "#paradas" ).sortable({
                        revert: true
                    });


                },
                error:function (err) {

                    console.log(err);
                }
            }
        );
    }

    $(document).on("submit",".add form",function (e) {


        var data =getFormData($(this));

         data.paradas=arrayFromUl($("#paradas-list li"));
        $.ajax(
            {
                method:"post",
                url:"<?php echo $urlSave; ?>",
                data:data,
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

    $(document).on("click","#add-parada",function(e)
    {
        var parada =$("#paradas :selected");

        $("#paradas-list").append("<li data-value="+parada.val()+">"+parada.html()+" <span class='remove'>X</span></li>");



    })

    $(document).ready(function () {


        loadParadas();
        <?php if($isEdit)
        {
        ?>
        var data =getFormData($(this));

        data.paradas=arrayFromUl($("#paradas-list li"));
        $.ajax(
            {
                method:"get",
                url:"lineas-data.php?act=list&id=<?php echo $id;?>",
                data:data,
                dataType:"json",
                success:function (res) {
                    var data =res.data[0];


                    $.each(data,function (k,v) {



                        if(Array.isArray(v))
                        {
                            $.each(v,function (clave,v) {



                                $("#paradas").val(v.id);

                              $("#add-parada").click();


                            });



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
        }?>
    });


</script>
<section class="add">
    <h2>Nueva linea</h2>
    <form>
        <div>
            <label>Numero</label>
            <input name="lineaNumero">
        </div>
        <div>
            <label>Nombre</label>
            <input name="lineaNombre">
        </div>
        <div>
            <select id="paradas" >

            </select>
            <button type="button" id="add-parada">Agregar</button>

            <ul class="sortable" id="paradas-list">

            </ul>
        </div>
        <div>
            <label>Descripcion</label>
            <textarea name="lineaDescripcion"></textarea>
        </div>


        <button type="submit">Aceptar</button>

    </form>
</section>