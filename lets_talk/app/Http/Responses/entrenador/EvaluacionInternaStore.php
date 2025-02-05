<?php

namespace App\Http\Responses\entrenador;

use App\User;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Log\Logger;
use App\Models\entrenador\EvaluacionInterna;
use App\Traits\FileUploadTrait;

class EvaluacionInternaStore implements Responsable
{
    use FileUploadTrait;

    public function toResponse($request)
    {
        $messages = [
            'evaluacion_interna.required' => 'La evaluación interna es obligatoria.',
            'archivo_evaluacion.file' => 'Por favor, sube un archivo PDF o imagen (jpg, jpeg, png).',
            'archivo_evaluacion.max' => 'El tamaño máximo permitido para el archivo es de 2MB.',
            // 'evaluacion_interna.string' => 'La evaluación interna debe ser un texto.',
            // 'archivo_evaluacion.mimes' => 'Por favor, sube un archivo PDF o imagen (jpg, jpeg, png).',
        ];

        $request->validate([
            'evaluacion_interna' => 'required|string',
            'archivo_evaluacion' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // tamaño máximo de archivo de 2MB
        ], $messages);

        $evaluacionInterna = request('evaluacion_interna', null);
        $idEstudiante = request('id_estudiante', null);
        $idInstructor = session('usuario_id');
        $idTrainerHorario = request('id_trainer_horario', null);
        
        $carpetaArchivos = '/upfiles/evaluacion_interna';
        $fechaActual = Carbon::now()->format('d-m-Y_H_i_s');
        $fileName = $fechaActual.'-'.$idInstructor.'-'.$idEstudiante;
        
        DB::connection('mysql')->beginTransaction();
        
        try {
            $archivoEvaluacion= '';
            if ($request->hasFile('archivo_evaluacion')) {
                $archivoEvaluacion = $this->upfileWithName($fileName, $carpetaArchivos, $request,'archivo_evaluacion', 'evaluacion');
            } else {
                $archivoEvaluacion = null;
            }

            $evaluacionInternaCreate = EvaluacionInterna::create([
                'evaluacion_interna' => $evaluacionInterna,
                'id_estudiante' => $idEstudiante,
                'id_instructor' => $idInstructor,
                'archivo_evaluacion' => $archivoEvaluacion,
                'id_trainer_horario' => $idTrainerHorario
            ]);

            if ($evaluacionInternaCreate) {
                DB::connection('mysql')->commit();
                alert()->success('Successful Process', 'Internal valuation created');
                return redirect()->to(route('trainer.index'));
            } else {
                DB::connection('mysql')->rollback();
                alert()->error('Error', 'An error has occurred creating the user, please contact support.');
                return redirect()->to(route('entrenador.index'));
            }

        } catch (Exception $e) {
            DB::connection('mysql')->rollback();
            alert()->error('Error', 'An error has occurred creating the user, try again, if the problem persists contact support.');
            return back();
        }
    }
}
