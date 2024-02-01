<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\MarcaRequest;
use Illuminate\Support\Facades\Storage;
use App\Repositories\MarcaRepository;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $marcaRepository = new MarcaRepository(new Marca());

        if($request->has('attributes_modelo')){
            $marcaRepository->selectAttributesRelacionais('modelos:id,'.$request->query('attributes_modelo'));
        }else{
            $marcaRepository->selectAttributesRelacionais('modelos');
        }

        if($request->has('filtro')){
            $marcaRepository->selectFilter($request->query('filtro'));
        }

        if($request->has('attributes')){
            $marcaRepository->selectAttributes($request->query('attributes'));
        }
        
        $marcas = $marcaRepository->getResult();
        
        if(count($marcas) == 0){
            return response()->json(['error' => 'Não existem marcas cadastradas'], 404);
        }

        return $marcas;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MarcaRequest $request)
    {
        
        try {
            
            $marca = Marca::create($request->all());

            $request->hasFile('imagem') ? $marca->update(['imagem' => $request->file('imagem')->store('marcas','public')]) : null;
            return response()->json($marca, 201);

        } catch (\Throwable $th) {
            return response()->json(['error' => 'Erro ao cadastrar marca'], 500);
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
            $marca = Marca::with('modelos')->findOrFail($id);
            return response()->json($marca);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Marca não encontrada'], 404);
        }
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Int  $marca
     * @return \Illuminate\Http\Response
     */
    public function update(MarcaRequest $request, Int $marca)
    {
        try {
            $marca = Marca::findOrFail($marca);
            $marca->update([
                'nome' => $request->nome ?? $marca->nome
            ]);
            
            if($request->hasFile('imagem')){
                Storage::disk('public')->delete($marca->imagem);
                $marca->update(['imagem' => $request->file('imagem')->store('marcas','public')]);
            }

            return response()->json($marca, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Marca não encontrada'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Int $id)
    {
        
        try {
            
            $marca = Marca::findOrFail($id);
           
            Storage::disk('public')->delete($marca->imagem);
            
            $del = $marca->delete();
            
            if(!$del){
                return response()->json(['error' => 'Erro ao remover marca'], 500);
            }
            return response()->json(['success' => true, 'msg' => 'marca removida com sucesso'], 200); 

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Marca não encontrada'], 404);
        }
       
    }
}
