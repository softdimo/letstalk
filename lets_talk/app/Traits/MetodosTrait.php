<?php

namespace App\Traits;

use App\Clases\PDF\FPDF;
use App\Events\NotificacionEvent;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\File;
use App\Clases\CarbonColombia;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

trait MetodosTrait
{
    public function checkDatabaseConnection($rutaPerfil)
    {
        try {
           $pdo = DB::connection()->getPdo();
            return view($rutaPerfil);
        } catch (\Exception $e) {
            return View::make('database_connection');
        }
    }

    public function quitarCaracteresEspeciales($cadena)
    {
        $no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ",
        "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ","Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢",
        "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”","Ã›", "ü", "Ã¶", "Ã–", "Ã¯",
        "Ã¤", "«", "Ò", "Ã", "Ã„", "Ã‹", "ñ", "Ñ", "*");

        $permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U",
                            "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U",
                            "u", "o", "O", "i", "a", "e", "U", "I", "A", "E", "n", "N", "");
        return str_replace($no_permitidas, $permitidas, $cadena);
    }
} // FIN Traits Metodos
