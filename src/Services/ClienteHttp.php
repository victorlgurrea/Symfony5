<?php 

namespace App\Services;

use Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\TraceableHttpClient;

class ClienteHttp
{
    private $clienteHttp;

    public function __construct()
    {
        $this->clienteHttp = HttpClient::create();
    }

    public function obtenerCodigoUrl(string $url)
    {
        $codigoEstado = null;
        
        try{
            $respuesta = $this->clienteHttp->request('GET', $url);
            $codigoEstado = $respuesta->getStatusCode();
        }catch(Exception $e){
            $codigoEstado = null;
        }

        return $codigoEstado;

    }
}