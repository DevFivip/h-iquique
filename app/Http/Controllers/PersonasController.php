<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use DateTime;
use Illuminate\Http\Request;

class PersonasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $personas = Persona::orderBy('id', 'DESC')->get();
        return view('persona.index', compact('personas'));
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('persona.formulario');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $datos = $request->all();
        Persona::create($datos);
        return redirect('persona');
        // dd($request);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $persona = Persona::find($id);
        $sexos = ['MASCULINO', "FEMENINO"];
        $sitios = ['Iquique','Viña del Mar'];

        $test = new DateTime($persona->fecha_recepcion_muestra);
        $fecha = $test->format('Y-m-d');
        $hora = $test->format('H:i');
        $recepcion = $fecha . 'T' . $hora;

        $fnacimiento = new DateTime($persona->fecha_nacimiento);
        $nacimiento = $fnacimiento->format('Y-m-d');

        return view('persona.edit', compact('persona', 'sexos', 'sitios', 'recepcion', 'nacimiento'));
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
        $data = $request->all();
        $persona = Persona::find($id);
        $persona->update($data);
        return redirect('persona');
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


    public function pagar($id)
    {
        $persona = Persona::find($id);
        $persona->update(['status' => ($persona->status === '0') ? '1' : '0']);
        return $persona;
    }
}
