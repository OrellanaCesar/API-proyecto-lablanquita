<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <style>
        .cabecera{
            background-color: #ef9b3c;
            color: #c6f3fa;
            font-weight: bold;
        }

        .cuerpo{
            background-color: #fcf8db;
        }

    </style>
</head>
<body>

<img src="" alt="{{ asset('img/logo.png') }}">
<p>Limpieza Blanquita tiene nuevas ofertas y novedades para vos.</p>
{{-- <h1>{{ $subject }}</h1> --}}
<table border="1">

    <caption>{{ $subject }}</caption>
    <tr class="cabecera">
        <th align="center">Producto</th>
        <th align="center">Categoría</th>
        <th align="center">Marca</th>
        <th align="center">Descripción</th>
        <th align="center">Precio</th>
    </tr>
    
    @for ($i = 0; $i < sizeof($products); $i++)
        <tr class="cuerpo">
            <td align="center"> {{ $products[$i]->product_name }}</td>
            <td align="center"> {{ $products[$i]->category->category_name }}</td>
            <td align="center"> {{ $products[$i]->brand->brand_name }}</td>
            <td align="center"> {{ $products[$i]->product_description }}</td>
            <td align="center"> ${{ $products[$i]->product_price }} </td>
        </tr>
    @endfor

</table>

Gracias por confiar en nosotros!!
Limpieza Blanquita.
</body>
</html>