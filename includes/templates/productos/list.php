{{^products}}
<div class="empty">
    <h2>No hay productos disponibles</h2>
</div>
{{/products}}

{{#error}}
<div class="error">
    <h2>{{info}}</h2>
</div>

{{/error}}


<table class="list">
    <thead>
    <tr>
        <th>
            #
        </th>
        <th>
            Nombre
        </th>
        <th>
            Marca
        </th>
    </tr>
    </thead>
    <tbody>
    {{#products}}
    <tr>
        <td>{{id}}</td>
        <td>{{name}}</td>
        <td>{{brand}}</td>
    </tr>
    {{/products}}




    </tbody>
</table>                                                                  
