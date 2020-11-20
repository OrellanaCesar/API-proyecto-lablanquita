<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;
use DataTables;

class BrandController extends Controller
{
    //

    public function index()
    {

        /*Esta funcion devuelve todas las marcas ordenadas por el nombre
        parametros: no recibe parametros
        Return :los datos de la marca en forma ordenada por nombre*/

        return response()->json(Brand::orderby('brand_name')->get(), 200);
    }

    
    public function dataTableBrands()
    {  

        /*Esta funcion devuelve un DataTable con todos los datos de marcas ordenados por el
        nombre de la marca, se le agrega una columna que es accion donde se ejecuta la 
        accion q se va hacer en el DataTable
        Parametros : no se le pasa ningun parametro
        Return : una tabla con los atributos de las marcas ordenadas por nombres */

        return DataTables::of(Brand::orderby('brand_name')->get())
                ->addColumn('accion', function($b){
                        return '';
                })->make(true);
}


public function store(Request $request)
{

    /*Estafuncion Store valida el nombre de la marca , crea una marca y guarda los datos 
    que tiene el request y los guarda en la base de datos
    Parámetros:recibe el parametro request donde tendran los datos de la nueva marca a registrar
    Return:la funcion devuelve si se pudo guardar correctamente o no */

    $validaData = $request->validate([
        'brand_name' => ['required','string']
    ]
);
    $brand = new Brand();
    $brand->brand_name = strtoupper($request->brand_name);

    if ($brand->save()){
        return response()->json([
            'success' => true,
            'data' => $brand->toArray()
        ]);
    }else{
        return response()->json([
            'success' => false,
            'message' => 'La marca no pudo ser agregada'
        ], 500);
    }
}



public function destroy($id)
{

    /*
    Estafuncion Destroy lo que haces es  buscar la marca con ese id y lo elimina 
    Parametros:recibe el parametro id ,que es el id de la marca a eliminar  
    Return:devuelve si pudo eliminar la marca o no 
    */
    
    $brand = Brand::find($id)->products;
    
    if (sizeof($brand) == 0){

        $brand = Brand::find($id);
        if ($brand->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'La Marca no pudo ser borrada'
            ], 500);
        }
    }else{

        return response()->json([
            'success' => false,
            'message' => 'La Marca no puede ser borrada. tiene un producto asociado'
        ], 500);
    }
}


public function update(Request $request, $id)
{

    /*Estafuncion Update busca la marca con ese id ,verifica si es valido el nombre de la marca
    , se fija si existe esa marca con es id , si no existe devuelve un mensaje de q no existe ,
    si existe modifica los datos de esa marca con el id que se paso por parametro
    Parametros:recibe el parametro id y request ,que es el id de la marca a modificar 
    y reuqest contendra los datos que se modifico   , devuelve si pudo modificarses 
    la marca o no 
    Return:Devuelve un mensaje si no existe la marca con ese ide o devuelve si se pudo 
    realizar la opreacion de actualizacion */
    
    $brand = Brand::find($id);
    $validaData = $request->validate([
        'brand_name' => ['required','string']
    ]
);

    if (!$brand) {
        return response()->json([
            'success' => false,
            'message' => 'Marca con identificador ' . $id . ' no encontrada'
        ], 400);
    }
    $data = array(
        'brand_name' => strtoupper($request->brand_name),
    );

    $updated = $brand->update($data);


    if ($updated)
        return response()->json([
            'success' => true
        ]);
    else
        return response()->json([
            'success' => false,
            'message' => 'La marca no se pudo actualizar'
        ], 500);
}

public function getBrand($id){
    $b = Brand::select('brands.brand_name')
    ->where('brands.brand_id','=',$id)
    ->get();
    return response()->json($b, 200);
}

public function searchProducts($id){
    $brand = Brand::find($id);
    $products_brand = Brand::find($id)->products;
    $products = array();
    foreach ($products_brand as $p) {
        $category = Category::find($p->category_id);
        $data['product_id'] = $p->product_id;
        $data['product_name'] = $p->product_name;
        $data['product_description'] = $p->product_description;
        $data['product_price'] = $p->product_price;
        $data['product_image'] = $p->product_image;
        $data['product_stock'] = $p->product_stock;
        $data['product_offer_day'] = $p->product_offer_day;
        $data['product_offer_day_order'] = $p->product_offer_day_order;
        $data['product_best_seller'] = $p->product_best_seller;
        $data['product_best_seller_order'] = $p->product_best_seller_order;
        $data['category'] = $category;
        $data['brand'] = $brand;
        array_push($products,$data);
    }
    return response()->json($data, 200);
}

}
