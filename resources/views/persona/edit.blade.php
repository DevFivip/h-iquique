@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Registro') }}</div>

                    <div class="card-body">
                        <form action="{{ route('persona.update', $persona->id) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <input type="hidden" name="status" value="0">
                            <div class="form-group">
                                <label for="tipo_documento">Tipo Documento</label>
                                <select name="tipo_documento" class="form-control">
                                    @foreach ($tipo_documento as $tipo)
                                        <option @if ($persona->tipo_documento == $tipo) selected @endif
                                            value="{{ $tipo }}">{{ $tipo }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="nombres">Nombres</label>
                                <input type="text" name="nombres" class="form-control" value="{{ $persona->nombres }}">
                            </div>
                            <div class="form-group">
                                <label for="apellidos">Apellidos</label>
                                <input type="text" name="apellidos" class="form-control"
                                    value="{{ $persona->apellidos }}">
                            </div>
                            <div class="form-group">
                                <label for="documento">Numero de Documento (Passaporte o RUT)</label>
                                <input type="text" name="documento" class="form-control"
                                    value="{{ $persona->documento }}">
                            </div>

                            <div class="form-group">
                                <label for="sexo">Sexo</label>
                                <select name="sexo" class="form-control">
                                    @foreach ($sexos as $sexo)
                                        <option @if ($persona->sexo == $sexo) selected @endif
                                            value="{{ $sexo }}">{{ $sexo }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="fecha_nacimiento">Fecha de Nacimiento </label>
                                <input type="date" name="fecha_nacimiento" class="form-control"
                                    value="{{ $nacimiento }}">
                            </div>


                            <div class="form-group">
                                <label for="origen">Origen</label>
                                <select name="origen" class="form-control">
                                    @foreach ($sitios as $sitio)
                                        <option @if ($persona->sitio == $sitio) selected @endif
                                            value="{{ $sitio }}">{{ $sitio }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="fecha_recepcion_muestra">Fecha de Ingreso</label>
                                <input type="datetime-local" name="fecha_recepcion_muestra" class="form-control"
                                    value="{{ $recepcion }}">

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

    <script>


    </script>
@endsection
