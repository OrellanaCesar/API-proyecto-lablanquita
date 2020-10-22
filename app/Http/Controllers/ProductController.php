<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

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

        $products = Product::with('categories:category_id,category_name','Brands:brand_id,brand_name')
                    ->get();
        return response()->json($products, 200);
    }

    public function offerDay(){

        /*Esta funcion devuelve todos los productos que son ofertas del dia 
        y sus marcas y categorias correspondiente  
        Parameters: no recibe
        return: json con los datos del producto*/

        $products = Product::where('product_offer_day','=',true)
                    ->with('categories:category_id,category_name','brands:brand_id,brand_name')
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
                    ->with('categories:category_id,category_name','brands:brand_id,brand_name')
                    ->orderby('product_best_seller_order','asc')
                    ->get();
        return response()->json($products, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
