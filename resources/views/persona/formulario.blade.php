@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Registro') }}</div>

                <div class="card-body">
                    <form action="{{route('persona.store')}}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="0">
                        <div class="form-group">
                            <label for="nombres">Nombres</label>
                            <input type="text" name="nombres" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="apellidos">Apellidos</label>
                            <input type="text" name="apellidos" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="documento">Numero de Documento (Passaporte o RUT)</label>
                            <input type="text" name="documento" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="sexo">Sexo</label>
                            <select name="sexo" class="form-control">
                                <option value="MASCULINO">MASCULINO</option>
                                <option value="FEMENINO">FEMENINO</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="sexo">Fecha de Nacimineto</label>
                            <input type="date" name="fecha_nacimiento" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="origen">Origen</label>
                            <select name="origen" class="form-control">
                                <option value="IQUIQUE">IQUIQUE</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="sexo">Fecha de Ingreso</label>
                            <input type="datetime-local" name="fecha_recepcion_muestra" class="form-control">
                        </div>


                        <br>
                        <br>
                        <input type="submit" value="GUARDAR" class="btn btn-primary">

                    </form>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection