<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use DataTables;

class BrandController extends Controller
{
    //
    /*Esta funcion devuelve todas las marcas ordenadas por el nombre, no recibe parametros*/
    public function index()
    {
        return response()->json(Brand::orderby('brandname')->get(), 200);
    }

    /*Esta funcion devuelve un DataTable con todos los datos de marcas ordenados por el nombre de la marca, se le agrega una columna que es accion donde se ejecuta la accion q se va hacer en el DataTable*/
    public function dataTableBrands()
    {   return DataTables::of(Brand::orderby('brandname')->get())
    ->addColumn('accion', function($b){
      return '';
  })->make(true);
}

/*Estafuncion Store recibe el parametro request donde tendran los datos de la nueva marca a registrar , valida el nombre de la marca , crea una marca y guarda los datos que tiene el request y los guarda en la base de datos, la funcion devuelve si se pudo guardar correctamente o no*/
public function store(Request $request)
{
    $validaData = $request->validate([
        'brandname' => ['required','string']
    ]
);
    $brand = new Brand();
    $brand->brandname = strtoupper($request->brandname);

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


/*Estafuncion Destroy recibe el parametro id ,que es el id de la marca a eliminar , busca la marca con ese id y lo elimina , devuelve si pudo eliminar la marca o no */
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


/*Estafuncion Update recibe el parametro id y request ,que es el id de la marca a modificar y reuqest contendra los datos que se modifico , busca la marca con ese id ,verifica si es valido el nombre de la marca, se fija si existe esa marca con es id , si no existe devuelve un mensaje de q no existe , si existe modifica los datos de esa marca con el id que se paso por parametro , devuelve si pudo modificarses la marca o no */
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
