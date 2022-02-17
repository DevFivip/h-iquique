@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Listado de Personas') }}</div>

                    <div class="card-body">
                        <a href="/persona/create">+ Registro</a>

                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>Nombres y Apellidos</td>
                                    <td>Documento</td>
                                    <td>Sexo</td>
                                    <td>Fecha de Ingreso</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($personas as $persona)
                                    <tr>
                                        <td>{{ $persona->id }}</td>
                                        <td>{{ $persona->nombres }} {{ $persona->apellidos }}</td>
                                        <td>{{ $persona->documento }}</td>
                                        <td>{{ $persona->sexo }}</td>
                                        <td>{{ $persona->fecha_recepcion_muestra }}</td>

                                        @if ($persona->status == '0')
                                            <td><a href="#status" onclick="status({{ $persona->id }})">💲 Por Pagar</a>
                                            </td>
                                        @else
                                            <td><a href="#status" onclick="status({{ $persona->id }})">💵 Pagado </a>
                                            </td>
                                        @endif

                                        <td>
                                            <a href="/get?token={{ $persona->_token }}">📃</a>
                                            <a href="/get/certificado?token={{ $persona->_token }}">🔐</a>
                                            <a href="/Resultados/Index/{{ $persona->qr }}">👁️</a>
                                            <a href="/persona/{{ $persona->id }}/edit">✏️</a>
                                            <a href="#eliminar" onclick="eliminar({{ $persona->id }})">🗑️</a>
                                        </td>

                                    </tr>
                                @endforeach
                                <tr>

                                </tr>
                            </tbody>
                        </table>
                        <script>
                            window.CSRF_TOKEN = '{{ csrf_token() }}';

                            async function status(id) {
                                if (confirm("¿Seguro deseas cambiar el estado?") == true) {
                                    const res = await fetch('persona/pagar/' + id, {
                                        method: 'POST',
                                        headers: {
                                            "Content-Type": "application/json",
                                            "Accept": "application/json",
                                            "X-Requested-With": "XMLHttpRequest",
                                            "X-CSRF-Token": window.CSRF_TOKEN
                                        },
                                    })

                                    location.reload()
                                }
                            }


                            async function eliminar(id) {
                                if (confirm("¿Seguro deseas Eliminar?") == true) {
                                    const res = await fetch('persona/' + id, {
                                        method: 'DELETE',
                                        headers: {
                                            "Content-Type": "application/json",
                                            "Accept": "application/json",
                                            "X-Requested-With": "XMLHttpRequest",
                                            "X-CSRF-Token": window.CSRF_TOKEN
                                        },
                                    })

                                    location.reload()
                                }
                            }
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
