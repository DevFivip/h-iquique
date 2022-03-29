@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Registro') }}</div>

                    <div class="card-body">
                        <form action="{{ route('persona.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="0">

                            <div class="form-group">
                                <label for="tipo_documento">Tipo Documento</label>
                                <select name="tipo_documento" class="form-control">
                                    <option value="OTROS">OTROS</option>
                                    <option value="RUT">ROL UNICO TRIBUTARIO</option>
                                    <option value="RUN">ROL UNICO NACIONAL</option>
                                    <option value="PASAPORTE">PASAPORTE</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="documento">Numero de Documento (Passaporte o RUT)</label>
                                <input type="text" name="documento" class="form-control" id="documento">
                                <a class="btn btn-primary my-2" onclick="buscar()">Buscar Cédula</a>
                            </div>

                            <div class="form-group">
                                <label for="nombres">Nombres</label>
                                <input type="text" name="nombres" class="form-control" id="nombres">
                            </div>

                            <div class="form-group">
                                <label for="apellidos">Apellidos</label>
                                <input type="text" name="apellidos" class="form-control" id="apellidos">
                            </div>

                            <div class="form-group">
                                <label for="sexo">Sexo</label>
                                <select name="sexo" class="form-control">
                                    <option value="MASCULINO">MASCULINO</option>
                                    <option value="FEMENINO">FEMENINO</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="sexo">Fecha de Nacimiento</label>
                                <input type="date" name="fecha_nacimiento" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="origen">Origen</label>
                                <select name="origen" class="form-control">
                                    <option value="Iquique">IQUIQUE</option>
                                    <option value="Viña del Mar">Viña del Mar</option>
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

                        <script>
                            window.CSRF_TOKEN = '{{ csrf_token() }}';

                            async function buscar() {

                                let inputDocumento = document.querySelector("#documento");
                                let valor = inputDocumento.value;

                                let inputNombres = document.querySelector("#nombres");
                                let inputApellidos = document.querySelector("#apellidos");

                                const res = await fetch('/scrapping/' + valor, {
                                    method: 'GET',
                                    headers: {
                                        "Content-Type": "application/json",
                                        "Accept": "application/json",
                                        "X-Requested-With": "XMLHttpRequest",
                                        "X-CSRF-Token": window.CSRF_TOKEN
                                    },
                                })

                                let cliente = await res.json();

                                inputNombres.value = cliente.nombres
                                inputApellidos.value = cliente.apellidos
                                inputDocumento.value = 'CI' + valor
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
