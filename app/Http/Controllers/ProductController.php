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
        
        /*Esta funcion devuelve todos los productos mas vendido  y sus marcas y categorias
        correspondiente  
        Parameters: no recibe
        return: json con los datos del producto*/

        $products = Product::where('product_best_seller','=',true)
                    ->with('category:category_id,category_name','brand:brand_id,brand_name')
                    ->orderby('product_best_seller_order','asc')
                    ->get();
        return response()->json($products, 200);
    }

    public function dataTableProducts(){

        /*Esta funcion devuelve un DataTable con todos los datos de productos ordenados por el
        nombre del product, se le agrega una columna que es accion donde se ejecuta la 
        accion q se va hacer en el DataTable
        Parameters : no se le pasa ningun parametro
        Return : una tabla con los atributos de las productos ordenadas por nombres */

        
        return DataTables::of(Product::with('category:category_id,category_name','brand:brand_id,brand_name')
                            ->orderby('product_name')                
                            ->get())
                            ->addColumn('accion', function($b){
                                return '';
                            })->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function countOffer(){
        $p = $p = Product::selectRaw('count(*) as cantidad')
                    ->where('product_offer_day','=',true)
                    ->first();
        return $p->cantidad;
    }

    public function cantBest(){
        $p = $p = Product::selectRaw('count(*) as cantidad')
                        ->where('product_best_seller','=',true)
                        ->first();
        return $p->cantidad;
    }

    public function idOffer($orden){
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
        if ($valueO == 1){
            $cant_o = $this->cantOffer();
            $id_o = $this->idOffer($orderO);
            if (($cant_o > 0) && ($id_o > 0) ){
                $p = Product::find($id_o)->update(['product_offer_day_order' => 0]);
            }
        }
        if ($valueB == 1 ){
            $cant_b = $this->cantBest();
            $id_b = $this->idBest($orderB);
            if (($cant_b > 0 ) && ( $id_b > 0)){
                $p = Product::find($id_b)->update(['product_best_seller_order' => 0]);
            }
        }    

    }

    public function store(Request $request)
    {
        $validaData = $request->validate([
            'product_name' => ['required','string'],
            'product_description' => ['required','string'],
            'product_image' => ['required', 'image', new NoJfifi],
            'product_price' => ['required'],
            'product_stock' => ['required'],
            'product_offer_day' => ['required'],
            'product_best_seller' => ['required'],
            'pproduct_offer_day_order' => ['required'],
            'product_best_seller_order' => ['required'],
            'branf_id' => ['required'],
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
            $product->product_offer_day = $request-product_offer_day;
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
