<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Utils\Clientes;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use LaravelQRCode\Facades\QRCode;

use setasign\Fpdi\Tcpdf\Fpdi;

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
        $datos['_token'] = Str::random(23);
        $datos['facturado'] = 0;
        $persona = Persona::create($datos);
        $this->hospital($persona);
        $this->denuncia($persona);
        return redirect('persona');
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
        $sitios = ['Iquique', 'Viña del Mar'];

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
        $data['_token'] = Str::random(23);

        $persona = Persona::find($id);
        $persona->update($data);

        $this->hospital($persona);
        $this->denuncia($persona);
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


    public function denuncia($persona)
    {

        $this->delete_file($persona->documento, 'PDI');

        $pdf = new FPDI();

        //Merging of the existing PDF pages to the final PDF
        $pageCount =   $pdf->setSourceFile(__DIR__ . '/../../../resources/pdf/base-pdi1.pdf');
        for ($i = 1; $i <= $pageCount; $i++) {
            $tplIdx = $pdf->importPage($i, '/MediaBox');
            $pdf->AddPage();
            $pdf->useTemplate($tplIdx);
        }

        $nombre = ucwords(strtolower($persona->nombres . ' ' . $persona->apellidos));
        $correlativo = 28159199 + ($persona->id * 2) + 5;


        $html = <<<EOD
        <div style="text-align: left; line-height: 25px; font-size:21px; color:#536678;">
        <p><b>Estimado(a): $nombre </b></p>
        </div>
        EOD;

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(130, 150, 36.5, 55.4, $html, 0, 0, 0, true, '', true);


        $html2 = <<<EOD
        <div style="text-align: left; line-height: 42px; font-size:19px; font-family:arial;color:#536678;font-weight:600;">
        <p>Junto con saludar, le informamos que su <b>Declaración Voluntaria de Ingreso Clandestino N° $correlativo,</b> ha sido recepcionada correctamente para revisión. Será via correo electrónico por un funcionario policial, quien le indicará los pasos a seguir para terminar este trámite</p>
        </div>
        EOD;

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(136, 150, 36.5, 75.5, $html2, 0, 0, 0, true, '', true);


        $html3 = <<<EOD
        <div style="text-align: left; line-height: 42px; font-size:20px; font-family:arial;color:#536678;">
        <p>Si usted tiene alguna duda contáctenos al fono: 227081003 y 227081043, o al correo electronico: <b style="color:#4084f4;">autodenuncia.jenamig@ investigaciones.cl</b></p>
        </div>
        EOD;

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(136, 150, 36.5, 183.5, $html3, 0, 0, 0, true, '', true);


        return $pdf->Output(__DIR__ . '/../../../storage/app/public/PDI/' . strtoupper($nombre) . ' PDI[' . $persona->documento . '].pdf', 'F');
    }

    public function hospital($persona)
    {
        $this->delete_file($persona->documento, 'PCR');

        $pdf = new FPDI();

        //Merging of the existing PDF pages to the final PDF
        $pageCount =   $pdf->setSourceFile(__DIR__ . '/../../../resources/pdf/base-pcr_compressed.pdf');
        for ($i = 1; $i <= $pageCount; $i++) {
            $tplIdx = $pdf->importPage($i, '/MediaBox');
            $pdf->AddPage();
            $pdf->useTemplate($tplIdx);
        }


        $test = new DateTime($persona->fecha_recepcion_muestra);
        $fecha_registro = $test->format('l jS F Y');


        $pdf->setFont('Helvetica', '', 10);
        $pdf->SetTextColor(102, 139, 159);
        //numero de certificado
        $pdf->setXY(151, 11.5);
        $pdf->Write(0, $persona->id + 2 + 382134988);


        $pdf->setFont('Helvetica', 'L', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->setXY(66, 31);
        $pdf->Write(0, strtoupper($persona->nombres));

        $pdf->setXY(66, 34.2);
        $pdf->Write(0, strtoupper($persona->apellidos));

        $pdf->setXY(66, 37.4);
        $pdf->Write(0, strtoupper($persona->documento));

        $fecha_nacimiento = (new DateTime($persona->fecha_nacimiento))->format('d/m/Y');
        $pdf->setXY(66, 40.6);
        $pdf->Write(0, strtoupper($fecha_nacimiento));

        $date1 = new DateTime($persona->fecha_nacimiento);
        $date2 = new DateTime(date("Y-m-d"));

        $interval = $date1->diff($date2);
        $edad = $interval->y . " año(s), " . $interval->m . " mes(es), " . $interval->d . " dia(s) ";

        $pdf->setXY(64.7, 43.8);
        $pdf->Write(0, ': ' . $edad);


        $test = new DateTime($persona->fecha_recepcion_muestra);
        $fecha = $test->format('d/m/Y');
        $hora = $test->format('H:i:s');
        $recepcion = $fecha . ' ' . $hora;


        $pdf->setXY(130, 40.6);
        $pdf->Write(0, $recepcion);

        $pdf->setXY(130, 44.3);
        $pdf->Write(0, $persona->sexo);


        $nombre = strtoupper($persona->nombres . ' ' . $persona->apellidos);

        $fnacimiento = new DateTime($persona->fecha_nacimiento);
        $nacimiento = $fnacimiento->format('d/m/Y');

        // $test = new DateTime($persona->fecha_recepcion_muestra);
        $fecha = $test->format('d/m/Y');
        $hora = $test->format('H:i:s');
        $fecha_muestra = $fecha . ' ' . $hora;

        $link = env('APP_URL') . '/get' . '?token=' . $persona->_token;

        $id = $persona->id;
        QRCode::URL($link)->setSize(10)->setMargin(0)->setOutfile('../storage/app/public/qr/' . $id . '.png')->png();
        $pdf->Image('../storage/app/public/qr/' . $id . '.png', 85, 200, 38, 38);
        $pdf->Image('../resources/pdf/firma2.png', 135, 232, 50, 20);

        return $pdf->Output(__DIR__ . '/../../../storage/app/public/PCR/' . strtoupper($nombre) . ' PCR[' . $persona->documento . '].pdf', 'F');
    }

    public function delete_file($dni, $type)
    {

        $allFiles = Storage::disk('public')->files($type);
        $find = [];

        for ($i = 0; $i < count($allFiles); $i++) {
            # code...
            $st = strrpos($allFiles[$i], $type . "[" . $dni . "]");
            if (!!$st) {
                array_push($find, $allFiles[$i]);
            }
        }

        if (!!count($find)) {
            for ($ii = 0; $ii < count($find); $ii++) {
                unlink(storage_path('app/public/' . $find[$ii]));
            }
            return true;
        } else {

            return true;
        }
    }

    public function send_whatapp($token)
    {

        dd($token);
    }

    public function facturar(Request $request)
    {
        $personas = $request->json();

        $precio = 25000;

        $items_cantidad = count($personas);

        $precioTotal = $items_cantidad * $precio;
        $cliente = Clientes::get();
        $rut = "517.0" . rand(4, 8) . rand(1, 9) . '.' . rand(101, 872);


        dd($precio, $items_cantidad, $precioTotal, $cliente, $rut);
    }
}
