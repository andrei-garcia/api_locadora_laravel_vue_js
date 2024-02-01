<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Repositories\ClienteRepository;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $repository = new ClienteRepository(new Cliente());
        

        if($request->has('filtro')){
            $repository->selectFilter($request->query('filtro'));
        }

        if($request->has('attributes')){
            $repository->selectAttributes($request->query('attributes'));
        }
        
        $ojbs = $repository->getResult();
        
        if(count($ojbs) == 0){
            return response()->json(['error' => 'Nenhuma informacao encontrada'], 404);
        }

        return $ojbs;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreClienteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClienteRequest $request)
    {
        try {
            
            $obj = Cliente::create($request->all());

            return response()->json($obj, 201);

        } catch (\Throwable $th) {
            return response()->json(['error' => 'Erro ao cadastrar cliente'], 500);
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
            $obj = Cliente::findOrFail($id);
            return response()->json($obj);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'informação não encontrada'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateClienteRequest  $request
     * @param  Int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClienteRequest $request, Int $id)
    {
        try {

            $obj = Cliente::findOrFail($id);
            $obj->update([
                'nome' => $request->nome ?? $obj->nome
            ]);

            return response()->json($obj, 200);

        } catch (\Throwable $e) {
            return response()->json(['error' => 'informação não encontrada'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        try {
            
            $obj = Cliente::findOrFail($cliente->id);

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
