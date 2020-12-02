<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TiempoExtension extends AbstractExtension
{
    CONST CONFIGURACION = [
        'formato' => 'Y/m/d H:i:s',
    ];

    public function getFilters()
    {
        return [
            new TwigFilter('tiempo', [$this, 'formatearTiempo']), 
        ];
    }

    public function formatearTiempo($fecha, $configuracion = [])
    {
        $configuracion = array_merge(self::CONFIGURACION, $configuracion);
        $fechaActual = new \DateTime();

        $fechaFormateada = $fecha->format($configuracion['formato']);
        $diferenciaFechasSegundos = $fechaActual->getTimestamp() - $fecha->getTimestamp();
        if($diferenciaFechasSegundos < 60) {
            $fechaFormateada = "Creado ahora mismo";
        } elseif ($diferenciaFechasSegundos < 3600) {
            $fechaFormateada = "Creado recientemente";
        } elseif ($diferenciaFechasSegundos < 14400) {
            $fechaFormateada = "Creado hace unas horas";
        }

        return $fechaFormateada;
    }
}
