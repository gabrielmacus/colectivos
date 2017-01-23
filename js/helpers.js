/**
 * Created by Usuario on 21/01/2017.
 */

$(document).on("click",".remove",function(e){
    $(this).closest("li").remove();
});

$(document).ready(function(){
    $(".sortable").sortable();
});
function getFormData($form){
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function(n, i){
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}

$(document).on("sortstop",".sortable",function(){

    console.log(arrayFromUl($("#paradas-list li")));

});
function arrayFromUl(ul)
{
    var data=[];

    $.each(ul,function(k,v)
    {
        var item=$(v);

            data.push(item.data("value"));



    }

    );
    return data;

}