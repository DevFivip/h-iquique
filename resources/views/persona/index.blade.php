@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Listado de Personas') }}</div>

                    <div class="card-body">
                        <a href="/persona/create">+ Registro</a>
                        &nbsp;
                        &nbsp;

                        <a class="btn btn-primary right" onclick="facturar()">Facturar</a>

                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <td></td>
                                    <td>ID</td>
                                    <td>Nombres y Apellidos</td>
                                    <td>Documento</td>
                                    <td>Sexo</td>
                                    <td>Fecha de Ingreso</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($personas as $persona)
                                    <tr>
                                        <td> <input type="checkbox" id="check" value="{{ $persona->id }}"
                                                class="custom-control-input"></td>
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
                                            @if (isset($persona->fecha_recepcion_muestra) && isset($persona->documento))
                                                <a href="/get?token={{ $persona->_token }}">📃</a>
                                                {{-- <a href="/get/certificado?token={{ $persona->_token }}">🔐</a> --}}
                                            @endif

                                            <a href="/get/denuncia?token={{ $persona->_token }}">👮</a>
                                            <a href="/Resultados/Index/{{ $persona->qr }}">👁️</a>
                                            <a href="/persona/{{ $persona->id }}/edit">✏️</a>
                                            <a href="#eliminar" onclick="eliminar({{ $persona->id }})">🗑️</a>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="true">
                                                    Enviar
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item"
                                                        onclick="enviar(1,{{ $persona->id }})">PCR</a>
                                                    <a class="dropdown-item"
                                                        onclick="enviar(2,{{ $persona->id }})">PDI</a>
                                                    <a class="dropdown-item" onclick="enviar(3,{{ $persona->id }})">PDI
                                                        + PCR </a>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                                <tr>

                                </tr>
                            </tbody>
                        </table>
                        <script>
                            $('.dropdown-toggle').dropdown()

                            window.CSRF_TOKEN = '{{ csrf_token() }}';

                            async function enviar($i, $id) {
                                let v = {}
                                v.id = $id
                                switch ($i) {
                                    case 1:
                                        v.pcr = true
                                        break;

                                    case 2:
                                        v.pdi = true

                                        break;

                                    case 3:
                                        v.pcr = true
                                        v.pdi = true
                                        break;

                                    default:
                                        v = undefined
                                        break;
                                }

                                const res = await fetch('enviar', {
                                    method: 'POST',
                                    body: JSON.stringify(v),
                                    headers: {
                                        "Content-Type": "application/json",
                                        "Accept": "application/json",
                                        "X-Requested-With": "XMLHttpRequest",
                                        "X-CSRF-Token": window.CSRF_TOKEN
                                    },
                                })

                                console.log({res})


                            }

                            async function makeFactura(data) {
                                const res = await fetch('persona/facturar', {
                                    method: 'POST',
                                    body: JSON.stringify(data),
                                    headers: {
                                        "Content-Type": "application/json",
                                        "Accept": "application/json",
                                        "X-Requested-With": "XMLHttpRequest",
                                        "X-CSRF-Token": window.CSRF_TOKEN
                                    },
                                })
                                console.log({
                                    res
                                });
                            }

                            function facturar() {
                                let inputs = document.querySelectorAll('#check');
                                let checked = []
                                for (let i = 0; i < inputs.length; i++) {
                                    const el = inputs[i];
                                    if (!!el.checked) {
                                        checked.push(el.value)
                                    }
                                }
                                if (!!checked.length) {
                                    makeFactura(checked)
                                }

                            }

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
