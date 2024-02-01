<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Http\Requests\StoreCarroRequest;
use App\Http\Requests\UpdateCarroRequest;
use App\Repositories\CarroRepository;
use Illuminate\Http\Request;

class CarroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carroRepository = new CarroRepository(new Carro());
        
        if($request->has('attributes_modelo')){
            $carroRepository->selectAttributesRelacionais('modelo:id,'.$request->query('attributes_modelo'));
           
        }else{
            $carroRepository->selectAttributesRelacionais('modelo');
        }

        if($request->has('filtro')){
            $carroRepository->selectFilter($request->query('filtro'));
        }

        if($request->has('attributes')){
            $carroRepository->selectAttributes($request->query('attributes'));
        }
        
        $carros = $carroRepository->getResult();
        
        if(count($carros) == 0){
            return response()->json(['error' => 'Nenhum carro encontrado'], 404);
        }

        return $carros;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCarroRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCarroRequest $request)
    {
        try {
            
            $carro = Carro::create($request->all());

            return response()->json($carro, 201);

        } catch (\Throwable $th) {
            return response()->json(['error' => 'Erro ao cadastrar carro'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Int $id)
    {
        try {
            $obj = Carro::with('modelo')->findOrFail($id);
            return response()->json($obj);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'informação não encontrada'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarroRequest  $request
     * @param  Int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCarroRequest $request, Int $id)
    {
        try {

            $obj = Carro::findOrFail($id);
            $obj->update([
                'modelo_id' => $request->modelo_id ?? $obj->modelo_id,
                'km' => $request->km ?? $obj->km,
                'placa' => $request->placa ?? $obj->placa,
                'disponivel' => $request->disponivel ?? $obj->disponivel
            ]);

            return response()->json($obj, 200);

        } catch (\Throwable $e) {
            return response()->json(['error' => 'informação não encontrada'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Int $id)
    {
        try {
            
            $obj = Carro::findOrFail($id);

            $del = $obj->delete();
            
            if(!$del){
                return response()->json(['error' => 'erro ao remover registro'], 500);
            }
            return response()->json(['success' => true, 'msg' => 'registro removido com sucesso'], 200); 

        } catch (\Throwable $e) {
            return response()->json(['error' => 'nenhum registro encontrado'], 404);
        }
    }
}
