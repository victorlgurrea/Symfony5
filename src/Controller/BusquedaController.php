<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EtiquetaRepository;
use App\Repository\MarcadorRepository;
use App\Form\BuscadorType;


class BusquedaController extends AbstractController
{
    public const ELEMENTOS_POR_PAGINA = 5;
    
    /**
     * @Route("/buscar-etiquetas", name="app_buscar_etiquetas")
     */
    public function buscarEtiquetas(EtiquetaRepository $etiquetaRepository, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
           $busqueda = $request->get('q');
           $etiquetas = $etiquetaRepository->buscarPorNombre($busqueda);
           
           return $this->json($etiquetas);
        }
        
        return $this->createNotFoundException();
    }

    /**
    * @Route("/buscar/{busqueda}/{pagina}", 
    * name="app_busqueda",
    * defaults = {
    *    "busqueda" : "",
    *    "pagina": 1
    *  },
    *  requirements = {"pagina"="\d+"}
    * )
    */
    public function busqueda(string $busqueda, int $pagina, MarcadorRepository $marcadorRepository, Request $request)
    {
        $formularioBusqueda = $this->createForm(BuscadorType::class);
        $formularioBusqueda->handleRequest($request);


        $marcadores = [];

        if ($formularioBusqueda->isSubmitted()) {
            if ($formularioBusqueda->isValid()) {
                $busqueda = $formularioBusqueda->get('busqueda')->getData();
                
            }
        }

        if (! empty($busqueda)) {
            $marcadores = $marcadorRepository->buscarPorNombre($busqueda, $pagina, self::ELEMENTOS_POR_PAGINA);
            
        }

        if (! empty($busqueda) || $formularioBusqueda->isSubmitted()) {
            
            return $this->render("index/index.html.twig", [
                'formulario_busqueda' => $formularioBusqueda->createView(),
                'marcadores' => $marcadores,
                'pagina' => $pagina,
                'elementos_por_pagina' => self::ELEMENTOS_POR_PAGINA,
                'parametros_ruta' => [
                    "busqueda" => $busqueda
                ]
            ]);
        }

        return $this->render("busqueda/_buscador.html.twig", [
            'formulario_busqueda' => $formularioBusqueda->createView()
        ]);
    }

}
