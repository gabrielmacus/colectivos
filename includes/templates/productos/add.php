
<form>


    <div>
        <label>Nombre</label>
        <input type="text" name="name">
    </div>
    <div>
        <label>Marca</label>
        <select class="brands" name="brand">


        </select>

    </div>

    <div >
        <label>Direcciones</label>
        <div class="direcciones">

            <div class="clone" style="display: none" data-name="address">
                <div>
                    <label>Calle</label>
                    <input data-name="street">
                </div>
                <div>
                    <label>Numero</label>
                    <input data-name="number">
                </div>
            </div>


        </div>
        <button onclick="addItem('.direcciones')" type="button">+</button>

    </div>

    <button type="submit">Aceptar</button>
</form>

<script>

    function toSelect(selector,items) {
        var select =$(selector);
        select.html("<option>-</option>");
        $.ajax({
            method:"get",
            url:"action.php?act=list&type="+items,

            dataType:"json",

            success:function (data) {

                console.log(data);

                if(data.success)
                {
                    var brands = data.data;

                    $.each(brands,function (key,value) {

                        select.append("<option value="+value._id.$id+">"+value.name+"</option>");


                    });

                }


            },
            error:function (err) {

                console.log(err);
            }
        });



    }
    function addItem(selector,item) {

        var clone=$(".direcciones .clone").clone();

        var mainName=clone.data("name");

        var selector=$(selector);

        selector.append("<div class='copy'>"+clone.html()+"</div>");

        var fields=selector.find("[data-name]:visible");

        var objCount=selector.find(".copy").length-1;

        $.each(fields,function (key,value) {
           var field =$(value);
            field.attr("name",mainName+"["+objCount+"]["+field.data("name")+"]");
        });



        $.each(item,function (key,value) {

            console.log(mainName+"["+objCount+"]["+key+"]");

           selector.find("[name='"+mainName+"["+(i+1)+"]["+key+"]']").val(value);

            i++;

        });






    }
    function add(e) {

            var data=$(this).serialize();

            $.ajax({
                method:"post",
                url:"<?php echo $urlOnSave;?>",
                async:false,

                dataType:"json",
                data:data,

                success:function (data) {

                    console.log(data);


                },
                error:function (err) {

                    console.log(err);
                }
            });
            e.preventDefault();
        }

    $(document).ready(function () {
        toSelect(".brands","brands");
        $(document).on("submit","form",add);


        <?php

        if($isEdit)
        {
            ?>
        $.ajax({
            method:"get",
            url:"action.php?act=list&type=products&id=<?php echo $_GET["id"] ?>",

            dataType:"json",
            success:function (data) {

                var obj=data.data[0];

                $.each(obj,function (key,value) {

                    $("form [name="+key+"]").val(value);

                    if(key!="_id"&& $.isArray(value))
                    {
                        $.each(value,function (index,item) {



                            addItem(".direcciones",item);


                        });

                    }


                });


            },
            error:function (err) {

                console.log(err);
            }
        });

        <?php
        }
        ?>












    });
</script>