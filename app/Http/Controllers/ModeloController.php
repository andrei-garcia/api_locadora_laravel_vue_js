<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModeloRequest;
use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\ModeloRepository;

class ModeloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $modeloRepository = new ModeloRepository(new Modelo());
        
        if($request->has('attributes_marca')){
            $modeloRepository->selectAttributesRelacionais('marca:id,'.$request->query('attributes_marca'));
           
        }else{
            $modeloRepository->selectAttributesRelacionais('marca');
        }

        if($request->has('filtro')){
            $modeloRepository->selectFilter($request->query('filtro'));
        }

        if($request->has('attributes')){
            $modeloRepository->selectAttributes($request->query('attributes'));
        }
        
        $modelos = $modeloRepository->getResult();
        
        if(count($modelos) == 0){
            return response()->json(['error' => 'Nenhum modelo encontrado'], 404);
        }

        return $modelos;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ModeloRequest $request)
    {
        try {
            
            $modelo = Modelo::create($request->all());

            $request->hasFile('imagem') ? $modelo->update(['imagem' => $request->file('imagem')->store('modelos','public')]) : null;
            return response()->json($modelo, 201);

        } catch (\Throwable $th) {
            return response()->json(['error' => 'Erro ao cadastrar modelo'], 500);
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
            $modelo = Modelo::with('marca')->findOrFail($id);
            return response()->json($modelo);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Modelo não encontrado'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ModeloRequest $request, Int $id)
    {
        try {
            $modelo = Modelo::findOrFail($id);

            $modelo->update([
                'nome' => $request->nome ?? $modelo->nome,
                'marca_id' => $request->marca_id ?? $modelo->marca_id,
                'numero_portas' => $request->numero_portas ?? $modelo->numero_portas,
                'lugares' => $request->lugares ?? $modelo->lugares,
                'air_bag' => $request->air_bag ?? $modelo->air_bag,
                'abs' => $request->abs ?? $modelo->abs
            ]);
            
            if($request->hasFile('imagem')){
                Storage::disk('public')->delete($modelo->imagem);
                $modelo->update(['imagem' => $request->file('imagem')->store('modelos','public')]);
            }

            return response()->json($modelo, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Modelo não encontrado'], 404);
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
            
            $modelo = Modelo::findOrFail($id);
           
            Storage::disk('public')->delete($modelo->imagem);
            
            $del = $modelo->delete();
            
            if(!$del){
                return response()->json(['error' => 'Erro ao remover modelo'], 500);
            }
            return response()->json(['success' => true, 'msg' => 'modelo removido com sucesso'], 200); 

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Modelo não encontrado'], 404);
        }
    }
}
