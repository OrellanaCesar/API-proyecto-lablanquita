<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use DataTables;

class BrandController extends Controller
{
    //
    public function index()
    {
        return response()->json(Brand::orderby('brandname')->get(), 200);
    }


    public function dataTableBrands()
    {   return DataTables::of(Brand::orderby('brandname')->get())
      ->addColumn('accion', function($b){
          return '';
      })->make(true);
    }


    public function store(Request $request)
    {
        $validaData = $request->validate([
            'brandname' => ['required','string']
        ]
    );
        $brand = new Brand();
        $brand->brandname = strtoupper($request->brandname)

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
    }



    public function update(Request $request, $id)
    {
            $brand = Brand::find($id);
            $validaData = $request->validate([
                'brandname' => ['required','string']
                ]
            );

            if (!$brand) {
                return response()->json([
                    'success' => false,
                    'message' => 'Marca con identificador ' . $id . ' no encontrada'
                ], 400);
            }
            $data = array(
                'brandname' => $request->brandname,
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

}
