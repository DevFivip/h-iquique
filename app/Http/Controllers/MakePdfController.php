<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use DateTime;
use Illuminate\Http\Request;
use setasign\Fpdi\Tcpdf\Fpdi;
use LaravelQRCode\Facades\QRCode;
use App\Http\Controllers\PersonasController;

class MakePdfController extends Controller
{
    //


    public function hospital(Request $request)
    {
        $p = new PersonasController();


        $data = $request->all();

        try {
            $token = $data['token'];
            $persona = Persona::where('_token', $token)->first();
            $p->delete_file($persona->documento, 'PCR');

            if ($persona === null) {
                abort(404);
            }
        } catch (\Throwable $th) {
            return abort(404);
        }

        $pdf = new FPDI();
        $pdf->setPrintFooter(false);
        $pdf->setPrintHeader(false);

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

        $link = env('APP_URL_IP') . '/get' . '?token=' . $persona->_token;
        $id = $persona->id;
        QRCode::URL($link)->setSize(10)->setMargin(0)->setOutfile('../storage/app/public/qr/' . $id . '.png')->png();
        $pdf->Image('../storage/app/public/qr/' . $id . '.png', 85, 200, 38, 38);

        $pdf->Image('../resources/pdf/firma2.png', 135, 232, 50, 20);

        return $pdf->Output(__DIR__ . '/../../../storage/app/public/PCR/' . strtoupper($nombre) . ' PCR[' . $persona->documento . '].pdf', 'FD');
    }

    public function certificado(Request $request)
    {
        $data = $request->all();

        try {
            $token = $data['token'];

            $persona = Persona::where('_token', $token)->first();

            if ($persona === null) {
                abort(404);
            }
        } catch (\Throwable $th) {
            return abort(404);
        }


        $pdf = new FPDI();
        $pdf->setPrintFooter(false);
        $pdf->setPrintHeader(false);

        //Merging of the existing PDF pages to the final PDF
        $pageCount =   $pdf->setSourceFile(__DIR__ . '/../../../resources/pdf/base-certificado.pdf');
        for ($i = 1; $i <= $pageCount; $i++) {
            $tplIdx = $pdf->importPage($i, '/MediaBox');
            $pdf->AddPage();
            $pdf->useTemplate($tplIdx);
        }


        $test = new DateTime($persona->fecha_recepcion_muestra);
        $fecha_registro = $test->format('l j \d\e  F \d\e\l Y');

        $fecha = <<<EOD
        <div style="text-align: right;">$fecha_registro</div>
        EOD;

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(146, 200, 33, 68, $fecha, 0, 0, 0, true, '', true);

        $nombre = strtoupper($persona->nombres . ' ' . $persona->apellidos);

        $fnacimiento = new DateTime($persona->fecha_nacimiento);
        $nacimiento = $fnacimiento->format('d/m/Y');

        $test = new DateTime($persona->fecha_recepcion_muestra);
        $fecha = $test->format('d/m/Y');
        $hora = $test->format('H:i:s');
        $fecha_muestra = $fecha . ' ' . $hora;


        $html = <<<EOD
        <div style="text-align: justify;">
        <p style="line-height: 20px;">La <b>SECRETARÍA REGIONAL MINISTERIAL DE SALUD</b> de la <b>Región de $persona->origen</b>, certifica que, <b>$nombre</b> identificado con Documento <b>$persona->documento</b> con fecha de nacimiento en <b>$nacimiento</b> se realizó un Test de <b>ANTÍGENOS</b> para <b>COVID-19</b> con resultado <b>NEGATIVO</b>, tomado el día <b>$fecha_muestra</b> según indicaciones regionales recibidas por el Ministerio de Salud.</p>
        </div>
        EOD;

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(146, 200, 33, 65, $html, 0, 0, 0, true, '', true);



        $html = <<<EOD
        <div style="text-align: center;">
            <p style="line-height: 20px;"> <b>Unidad Toma de Muestras y Testeo SEREMI de Salud $persona->origen</b></p>
        </div>
        EOD;

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(90, 200, 59, 161, $html, 0, 0, 0, true, '', true);



        $link = env('APP_URL') . '/get/certificado' . '?token=' . $persona->_token;

        $id = $persona->id;
        QRCode::URL($link)->setSize(10)->setMargin(0)->setOutfile('../storage/app/public/qr/' . $id . '.png')->png();

        $pdf->Image('../storage/app/public/qr/' . $id . '.png', 85, 200, 38, 38);

        $pdf->Output();


        // $persona = Persona::find($id);

        // $lugar = $persona->sitio;


        // $pdf = new FPDI();

        // $pdf->AddPage();

        // $pdf->
        // // $pdf->SetAutoPageBreak(false);

        // $pdf->setSourceFile(__DIR__ . '/../../../resources/pdf/base-certificado.pdf');

        // $tplIdx = $pdf->importPage(1);

        // // $dimesion = $this->dimension(2.34);

        // $pdf->useTemplate(
        //     $tplIdx,
        //     1,
        //     1
        //     /**$dimesion[0], $dimesion[1], true*/
        // );

        // $pdf->SetFillColor(0, 255, 255);
        // $pdf->Rect(34, 70, 147, 80, 'F');

        // $pdf->SetFont('Arial', '', '8');
        // $pdf->MultiCell(
        //     20,
        //     90,
        //     $pdf->Write(2,'hola'),
        //     $pdf->Write(2,'buenos dias'),

        // );





        // $pdf->Ln(5);
        // $pdf->SetFont('Arial', 'I', 12);
        // $pdf->SetTextColor(128);

        // //Your text cell
        // $pdf->SetY(50);
        // $pdf->SetX(90);


        // $pdf->Image('../storage/app/public/qr/' . $id . '.png', 153, 45.8, 52, 52);
        return $pdf->Output(strtoupper($nombre) . '.pdf', 'I');
    }

    public function denuncia(Request $request)
    {
        $p = new PersonasController();
        $data = $request->all();

        try {
            $token = $data['token'];

            $persona = Persona::where('_token', $token)->first();
            $p->delete_file($persona->documento, 'PDI');
            if ($persona === null) {
                abort(404);
            }
        } catch (\Throwable $th) {
            return abort(404);
        }


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


        return $pdf->Output(__DIR__ . '/../../../storage/app/public/PDI/' . strtoupper($nombre) . ' PDI[' . $persona->documento . '].pdf', 'FD', 'I');
    }


    public function sanitario(Request $request)
    {
        $data = $request->all();

        try {
            $token = $data['token'];

            $persona = Persona::where('_token', $token)->first();

            if ($persona === null) {
                abort(404);
            }
        } catch (\Throwable $th) {
            return abort(404);
        }


        $pdf = new FPDI();
        $pdf->SetAutoPageBreak(false);
        $pdf->setPrintFooter(false);
        $pdf->setPrintHeader(false);
        //Merging of the existing PDF pages to the final PDF
        $pageCount =   $pdf->setSourceFile(__DIR__ . '/../../../resources/pdf/BASE_NUEVO_PASE_SANITARIO.pdf');
        for ($i = 1; $i <= $pageCount; $i++) {
            $tplIdx = $pdf->importPage($i, '/MediaBox');
            $pdf->AddPage();
            $pdf->useTemplate($tplIdx);
        }



        $date1 = new DateTime($persona->fecha_nacimiento);
        $date2 = new DateTime(date("Y-m-d"));

        $interval = $date1->diff($date2);
        $edad = $interval->y . " años";
        $prefix='';

        if ($persona->sexo === 'MASCULINO' && $interval->y > 20) {
            $prefix = 'DON ';
        }

        if ($persona->sexo === 'FEMENINO' && $interval->y > 20) {
            $prefix = 'DOÑA ';
        }


        $test = new DateTime($persona->fecha_recepcion_muestra);
        $fecha_registro = $test->format('l j \d\e  F \d\e\l Y');

        $fecha = <<<EOD
        <div style="text-align: right;">IQUIQUE, $fecha_registro</div>
        EOD;

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(146, 200, 33, 63, $fecha, 0, 0, 0, true, '', true);

        $nombre = strtoupper($persona->nombres . ' ' . $persona->apellidos);

        $fnacimiento = new DateTime($persona->fecha_nacimiento);
        $nacimiento = $fnacimiento->format('d/m/Y');

        $test = new DateTime($persona->fecha_recepcion_muestra);
        $fecha = $test->format('d/m/Y');
        $hora = $test->format('H:i:s');
        $fecha_muestra = $fecha . ' ' . $hora;


        $html = <<<EOD
        <div style="text-align: justify;">
        <p style="line-height: 20px;"><b>La SECRETARÍA REGIONAL MINISTERIAL DE SALUD de la Región de Tarapacá</b>, certifica que,<b> $prefix $nombre</b> identificada con <b>OTROS $persona->documento</b> con fecha de nacimiento en <b>$nacimiento ($edad) </b> egresó de una Residencia Sanitaria o Residencia Sanitaria Transitoria según “Protocolo de Residencia Sanitaria – Plan de Acción Coronavirus COVID-19” emitido por el Ministerio de Salud, en Residencia Sanitaria/Residencia Sanitaria Transitoria.</p>
        </div>
        EOD;

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(146, 200, 33, 57, $html, 0, 0, 0, true, '', true);



        $html = <<<EOD
        <div style="text-align: justify;">
            <p style="line-height: 20px;">Hoy cumple condiciones de egreso, puesto que no presenta sintomatología sugerente de SARS-CoV-2 y ha cumplido con el aislamiento efectivo.</p>
        </div>
        EOD;

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(146, 200, 33, 109, $html, 0, 0, 0, true, '', true);

        $html = <<<EOD
        <div style="text-align: justify;">
        <p style="line-height: 20px;">En caso de ameritar evaluación médica post término de cuarentena, deberá concurrir a la Red Asistencial.</p>
        </div>
        EOD;

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(146, 200, 33, 126, $html, 0, 0, 0, true, '', true);


        $html = <<<EOD
        <div style="text-align: justify;">
        <p style="line-height: 20px;">Se recuerda mantener:</p>
        </div>
        EOD;

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(146, 200, 33, 142, $html, 0, 0, 0, true, '', true);


        $html = <<<EOD
        <div>
        <ul><li><ul><li>Uso habitual de mascarilla</li><li>Distaciamiento social</li><li>Lavado frecuente de manos</li></ul><li></ul>
        </div>
        EOD;

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(146, 280, 18, 152, $html, 0, 0, 0, true, '', true);

        $link = env('APP_URL_IP') . '/certificadosEgresosRS/eyJpdiI6IlJ6TFVGdEVEUG9TZzZZa1prcUlcLzVnPT0iLCJ2YWx1ZSI6InhQNFZ6dXdMWDJvOFwvalVsNjZOakZRPT0iLCJtYWMiOiIwZmQ1Y2JmNDk1ZDU0MjNhN2QzMTQxM2QxODFmNzE0Mjg2MDVlNWMyMGE0NGM3NTUzYjg3NmVlNGYwNDA5N2I3In0/' . $persona->_token . '/scanPDF';

        $id = $persona->id;
        QRCode::URL($link)->setSize(10)->setMargin(0)->setOutfile('../storage/app/public/qr/' . $id . '.png')->png();


        $pdf->Image('../storage/app/public/qr/' . $id . '.png', 83, 230, 35, 35);
        $pdf->Output();

        return $pdf->Output(strtoupper($nombre) . '.pdf', 'F');
    }

    public function verRegistroSanitario($token)
    {


        try {

            $persona = Persona::where('_token', $token)->first();

            if ($persona === null) {
                abort(404);
            }
        } catch (\Throwable $th) {
            return abort(404);
        }



        $date1 = new DateTime($persona->fecha_nacimiento);
        $date2 = new DateTime(date("Y-m-d"));

        $interval = $date1->diff($date2);
        $edad = $interval->y . " años";
        $prefix = '';

        // $test = new DateTime($persona->fecha_recepcion_muestra);
        // $fecha = $test->format('d/m/Y');
        // $hora = $test->format('H:i:s');
        // $fecha_muestra = $fecha . ' ' . $hora;

        $test = new DateTime($persona->fecha_recepcion_muestra);
        $fecha_registro = $test->format('l j \d\e  F \d\e\l Y');

        $fecha_nacimiento = (new DateTime($persona->fecha_nacimiento))->format('d/m/Y');

        $nombre = strtoupper($persona->nombres . ' ' . $persona->apellidos);


        if ($persona->sexo === 'MASCULINO' && $interval->y > 20) {
            $prefix = 'DON ';
        }

        if ($persona->sexo === 'FEMENINO' && $interval->y > 20) {
            $prefix = 'DOÑA ';
        }


        return view('persona.certificado', compact('persona', 'prefix', 'fecha_registro', 'edad', 'nombre', 'fecha_nacimiento'));
        // dd($persona);
    }
}
