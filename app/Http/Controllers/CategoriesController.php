<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use DataTables;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        /*Esta función devuelve todos las categorías 
        Parametros: no recibe
        return: json con los datos de categorías*/

        $categories = Category::get();
        return response()->json($categories, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dataTableCategories(){ 
        
        /*Esta función devuelve un DataTable con todos los datos de Categorías ordenados por el
        nombre de la categoría. Se le agrega una columna llamada "Acción"que ejecutará la 
        acción que se va hacer en el DataTable.
        Parámetros : no se le pasa ningún parámetro
        Return : una tabla con los atributos de las Categorías ordenadas por nombres */

        return DataTables::of(Category::orderby('category_name')->get())
                    ->addColumn('accion', function($b){
                                        return '';
                    })->make(true);
    }

    public function store(Request $request){
       /*Esta función valida el nombre de la categoría, crea una categoría, ésta captura los datos 
        que tiene el request y los guarda en la base de datos.
        Parámetros: recibe el parámetro request que tendrá los datos de la nueva Categoría a registrar.
        Return: la función devuelve un mensaje indicando si se pudo guardar correctamente o no la Categoría. */ 

        $validaData = $request->validate([
            'category_name' => ['required','string']
        ]);
        $category = new Category();
        $category->category_name = strtoupper($request->category_name);
    
        if ($category->save()){
            return response()->json([
                'success' => true,
                'data' => $category->toArray()
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'La Categoría no pudo ser agregada'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $c = Category::find($id);
        return response()->json($c, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        /*Esta función busca la Categoría con el id pasado por parámetro, verifica que sea válido 
        el nombre de la Categoría, verifica que exista Categoría con ese id. Si no existe, devuelve
        un mensaje indicando que no existe. Si existe, modifica los datos de esa Categoría.
        Parámetros: recibe el parámetro 'id' de la Categoría a modificar y 'request' que tendrá los
        datos que se modificaron.
        Return: Devuelve un mensaje indicando que no existe la Categoría con ese 'id', o devuelve 
        un mensaje indicando que si se pudo realizar la opreación de actualización
         */

        $category = Category::find($id);
        $validaData = $request->validate([
                            'category_name' => ['required','string']
                    ]);
        
        
        
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Categoría con identificador ' . $id . ' no encontrada'
            ], 400);
        }
        
        $data = array(
                'category_name' => strtoupper($request->category_name),
        );

        $updated = $category->update($data);


        if ($updated)
        return response()->json([
            'success' => true
        ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'La Categoría no se pudo actualizar'
            ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        /*Esta función busca la Categoría con el id pasado por parámetro y lo elimina.
          Parámetros: recibe el parámetro id, que es el código de la categoría a eliminar  
          Return: devuelve un mensaje indicando si pudo eliminar la categoría o no */
          $product = Category::find($id)->products;

          $category = Category::find($id);

          if (sizeof($product) > 0) {
            return response()->json([
                'success' => false,
                'message' => 'La Categoría no se puede eliminar porque existe un producto asociado'
            ], 500);
          } 

          if ($category->delete()) {
            return response()->json([
                            'success' => true
                        ]);
          } else {
                return response()->json([
                            'success' => false,
                            'message' => 'La Categoría no pudo ser borrada'
            ], 500);
            }
    }

    public function searchProducts($id){
        $category = Category::find($id);
        $products_category = Category::find($id)->products;
        $products = array();
        foreach ($products_category as $p) {
            $brand = Brand::find($p->brand_id);
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
