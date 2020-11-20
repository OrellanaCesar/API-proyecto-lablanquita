<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Rules\NoJfifi;
use DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*Esta funcion devuelve todos los productos  y sus marcas y categorias
        correspondiente  
        Parameters: no recibe
        return: json con los datos del producto*/

        $products = Product::with('category:category_id,category_name','brand:brand_id,brand_name')
                    ->orderby('product_name') 
                    ->get();
        return response()->json($products, 200);
    }

    public function offerDay(){

        /*Esta funcion devuelve todos los productos que son ofertas del dia 
        y sus marcas y categorias correspondiente  
        Parameters: no recibe
        return: json con los datos del producto*/

        $products = Product::where('product_offer_day','=',true)
        ->with('category:category_id,category_name','brand:brand_id,brand_name')
        ->orderby('product_offer_day_order','asc')
        ->get();
        return response()->json($products, 200);
    }

    public function bestSeller(){

        /*
        Esta funcion devuelve todos los productos mas vendido  y sus marcas y categorias
        correspondiente  
        Parameters: no recibe
        return: json con los datos del producto
        */

        $products = Product::where('product_best_seller','=',true)
        ->with('category:category_id,category_name','brand:brand_id,brand_name')
        ->orderby('product_best_seller_order','asc')
        ->get();

        return response()->json($products, 200);
    }

    public function getProductsD(){

        /*
        Esta funcion se creo para recargar el dataTable cuando se elimina un producto.
        parameter:no hay.
        return: json con los datos del producto
        */

        $p = Product::select('products.*','brands.brand_name','categories.category_name')
            ->join('brands','products.brand_id','=','brands.brand_id')
            ->join('categories','products.category_id','=','categories.category_id')
            ->orderby('product_name')                
            ->get();
        return response()->json($p, 200);
        
    }

    public function dataTableProducts(){

        /*Esta funcion devuelve un DataTable con todos los datos de productos ordenados por el
        nombre del product, se le agrega una columna que es accion donde se ejecuta la 
        accion q se va hacer en el DataTable
        Parameters : no se le pasa ningun parametro
        Return : una tabla con los atributos de las productos ordenadas por nombres */

        $p = Product::select('products.*','brands.brand_name','categories.category_name')
            ->join('brands','products.brand_id','=','brands.brand_id')
            ->join('categories','products.category_id','=','categories.category_id')
            ->orderby('product_name')                
            ->get();

        return datatables()->of($p)->addColumn('action', function($prod){
                            return '';
                        ;
                    })->rawColumns( ['action'] )
                    ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function countOffer($order){

        /* Esta funcion devuelve la cantidad de productos 
        que son ofertas del dia
        parameter: no hay.
        return: un numero entero que representa la cantidad
        */

        $p = Product::selectRaw('count(*) as cantidad')
        ->where('product_offer_day','=',true)
        ->where('product_offer_day_order','=',$order)
        ->first();
        return $p->cantidad;
    }

    public function countBest($order){

        /* Esta funcion devuelve la cantidad de productos 
        que son ofertas destacados
        parameter: no hay.
        return: un numero entero que representa la cantidad
        */
        $p = Product::selectRaw('count(*) as cantidad')
        ->where('product_best_seller','=',true)
        ->where('product_best_seller_order','=',$order)
        ->first();
        return $p->cantidad;
    }

    public function idOffer($orden){

        /* Esta funcion devuelde el id del producto que es oferta del dia
        y tiene el orden pasado por paramtro
        parameter: entero que representara la posicion del carousel.
        return: el id del producto con ese orden o si no lo encuentra devuelev
        un -1
        */

        $p = Product::select('product_id')
        ->where('product_offer_day','=',true)
        ->where('product_offer_day_order' ,'=', $orden)
        ->first();
        if ($p !== null){
            return $p->product_id;
        }else{
            return -1;
        }
        
    }

    public function idBest($orden){

        /* Esta funcion devuelde el id del producto que es destacado
        y tiene el orden pasado por paramtro
        parameter: entero que representara la posicion del carousel.
        return: el id del producto con ese orden o si no lo encuentra devuelev
        un -1
        */

        $p = Product::select('product_id')
        ->where('product_best_seller','=',true)
        ->where('product_best_seller_order' ,'=', $orden)
        ->first();
        if ($p !== null){
            return $p->product_id;
        }else{
            return -1;
        }
    }

    public function actulizaCarousel($orderO,$orderB,$valueO,$valueB){

        /* Esta funcion pone en 0 tanto a prduct_offert_day_order como al product_best_seller_order 
        en caso de que los valores(valueO,valueB) que representaran cantidad sean == 1 
        prameter: orden de offer, orden de best , valor nuevo para orden, valor nuevo para best
        return: no retorna nada.
        */

        if ($valueO == 1){
            $cant_o = $this->countOffer($orderO);
            $id_o = $this->idOffer($orderO);
            if (($cant_o > 0) && ($id_o > 0) ){
                $p = Product::find($id_o)->update(['product_offer_day_order' => 0]);
            }
        }
        if ($valueB == 1 ){
            $cant_b = $this->countBest($orderB);
            $id_b = $this->idBest($orderB);
            if (($cant_b > 0 ) && ( $id_b > 0)){
                $p = Product::find($id_b)->update(['product_best_seller_order' => 0]);
            }
        }    

    }

    public function ocupedOffer(){

        /* Esta funcion devuelve todos los orden de los productos
        que son ofertas del dia.
        prameter:no hay.
        return: json con los orden de productos oferta del dia.*/

        $p = Product::select('products.product_offer_day_order')
        ->where('products.product_offer_day','=',true)
        ->where('products.product_offer_day_order', '>', 0)
        ->orderby('products.product_offer_day_order', 'asc')
        ->get();
        return response()->json($p, 200);
    }

    public function ocupedBest(){

        /* Esta funcion devuelve todos los orden de los productos
        que son destacados.
        prameter:no hay.
        return: json con los orden de productos destacados.*/

        $p = Product::select('products.product_best_seller_order')
        ->where('products.product_best_seller','=',true)
        ->where('products.product_best_seller_order', '>', 0)
        ->orderby('products.product_best_seller_order', 'asc')
        ->get();
        return response()->json($p, 200);
    }

    public function store(Request $request)
    {
        /* 
        Esta funcion crea un producto.
        parameter: los datos del producto.
        return: un json , 200 si se pudo realizar el alta del producto y 
        500 en caso contrario
        */ 

        $validaData = $request->validate([
            'product_name' => ['required','string'],
            'product_description' => ['required','string'],
            'product_image' => ['required', 'image', new NoJfifi],
            'product_price' => ['required'],
            'product_stock' => ['required'],
            'product_offer_day' => ['required'],
            'product_best_seller' => ['required'],
            'product_offer_day_order' => ['required'],
            'product_best_seller_order' => ['required'],
            'brand_id' => ['required'],
            'category_id' => ['required'],
        ]);
        
        if($request->file('product_image')){
            $file = $request->file('product_image');
            $path = Storage::disk('public')->put('image/products', $request->file('product_image'));
            $product = new Product();
            $product->product_name = $request->product_name;
            $product->product_price = $request->product_price;
            $product->product_description = $request->product_description;
            $product->product_stock = $request->product_stock;
            $product->product_image = Storage::url($path);
            $product->product_offer_day = $request->product_offer_day;
            $product->product_offer_day_order = intVal($request->product_offer_day_order);
            $product->product_best_seller = $request->product_best_seller;
            $product->product_best_seller_order = intVal($request->product_best_seller_order);
            $product->brand_id = intVal($request->brand_id);
            $product->category_id = intVal($request->category_id);
            $product->product_discount_percentage = 0;
            $this->actulizaCarousel(
                $product->product_offer_day_order,
                $product->product_best_seller_order,
                $product->product_offer_day,
                $product->product_best_seller
            );
            if ($product->save()) {
                return response()->json([
                    'success' => true,
                    'data' => $product->toArray()
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'La producto no pudo ser agregado'
                ], 500);
            }
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
        /*
        Trae los datos de un producto
        parametros: identificador del producto
        salida: jason con los datos del producto
        */
        
        $p = Product::with('category:category_id,category_name','brand:brand_id,brand_name')
        ->where('product_id','=',$id)
        ->get();
        return response()->json($p, 200);
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){

        /* 
        Esta funcion actualiza los datos de u  producto.
        paramter: el id del producto a modificar y los nuevos datos pasados en la request.
        return: mensaje de exito o fracaso.
        */

        $validaData = $request->validate([
            'product_name' => ['required','string'],
            'product_description' => ['required','string'],
            'product_price' => ['required'],
            'product_stock' => ['required'],
            'product_offer_day' => ['required'],
            'product_best_seller' => ['required'],
            'product_offer_day_order' => ['required'],
            'product_best_seller_order' => ['required'],
            'brand_id' => ['required'],
            'category_id' => ['required'],
        ]);
        

        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'El producto con ' . $id . ' no fue encontrado.'
            ], 400);
        }
        if ($request->hasFile('product_image')) {
            
            $path_old = $product->product_image;
            $path = Storage::disk('public')->put('image/products', $request->file('product_image'));
            $path = Storage::url($path);
            $path_new = $path;

        }else{
            
            $path = $product->product_image;
        }
        $data = array(
            'product_name' => $request->product_name,
            'product_description' => $request->product_description,
            'product_price' => $request->product_price,
            'product_stock' => $request->product_stock,
            'product_offer_day' => $request->product_offer_day,
            'product_best_seller' => $request->product_best_seller,
            'product_offer_day_order' => $request->product_offer_day_order,
            'product_best_seller_order' => $request->product_best_seller_order,
            'product_image' => $path,
            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id
        );
        $this->actulizaCarousel(
            $data['product_offer_day_order'],
            $data['product_best_seller_order'],
            $data['product_offer_day'],
            $data['product_best_seller']
        );
        if ($product->update($data)) {
            if ($request->hasFile('product_image')) {
                Storage::delete('public'.substr($path_old,8));
            }
            return response()->json([
                'success' => true,
                'message' => 'El producto se modifico correctamente'
            ], 200);
        }else {
            if ($request->hasFile('product_image')) {
                Storage::delete('public'.substr($path_new,8));
            }
            return response()->json([
                'success' => false,
                'message' => 'El producto no pudo ser modificado'
            ], 500);
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){

        /*
        Esta funcion elimina los datos de un producto y su imagen 
        dado un id 
        parameter: id del producto
        return: respuesta json de la transaccion.
        */

        $product =  Product::find($id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Producto con id ' . $id . ' no existe'
            ], 400);
        }else{
            if ($product->delete()) {
                Storage::delete('public'.substr($product->product_image,8));
                return response()->json("Se elimino el producto correctamente", 200);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'El producto no pudo ser eliminado'
                ], 500);
            }
        }            
    }

    public function searchProducts(Request $request){

        /*
        Esta funcion busca los productos que conciden con la request.
        parameter:no hay.
        return: no hay.
        */

        $products_search = Product::select('products.*','brands.brand_name','categories.category_name')
                    ->join('brands','products.brand_id','=','brands.brand_id')
                    ->join('categories','products.category_id','=','categories.category_id')
                    ->where('products.product_name','like','%'.$request->product_name.'%')
                    ->orWhere('products.product_name','like','%'.strtoupper($request->product_name).'%')
                    ->orWhere('products.product_description','like','%'.$request->product_description.'%')
                    ->orWhere('products.product_description','like','%'.strtoupper($request->product_description).'%')
                    ->orWhere('brands.brand_name','like','%'.strtoupper($request->brand_name).'%')
                    ->orWhere('categories.category_name','like','%'.strtoupper($request->category_name).'%')                
                    ->get();

        $data = array();
        foreach ($products_search as $p) {
            $prod = Product::with('category:category_id,category_name','brand:brand_id,brand_name')
                    ->find($p->product_id);
            array_push($data,$prod);
                    
        }
        return response()->json($data, 200 );
                
    }
}
