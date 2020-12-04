<?php

namespace App\Controller;

use App\Form\BuscadorType;
use App\Repository\CategoriaRepository;
use App\Repository\MarcadorRepository;
use App\Repository\EtiquetaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    public const ELEMENTOS_POR_PAGINA = 4;

    /**
     * @Route("/buscar-etiquetas", name="app_buscar_etiquetas")
     */
    public function buscarEtiquetas(EtiquetaRepository $etiquetaRepository, Request $request)
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

        return $this->render("comunes/_buscador.html.twig", [
            'formulario_busqueda' => $formularioBusqueda->createView()
        ]);
    }

    /**
    * @Route("/editar-favorito", name="app_editar_favorito")
    */
    public function editarfavorito(MarcadorRepository $marcadorRepository, Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $actualizado = true;
            $idMarcador = $request->get('id');
            $entityManager = $this->getDoctrine()->getManager();
            $marcador = $marcadorRepository->findOneById($idMarcador);
            $marcador->setFavorito(! $marcador->getFavorito());
            
            try{
                $entityManager->flush();
            }catch(\Exception $e) {
                $actualizado = false;
            }
            

            return $this->json([
                'actualizado' => $actualizado,
            ]);
        }
        throw $this->createNotFoundException();
    }

    /**
     * @Route(
     * "/favoritos/{pagina}", 
     * name="app_favoritos",
     * defaults = {
     *      "pagina": 1,
     *  },
     *  requirements = {"pagina"="\d+"}
     * )
     */
    public function favoritos(int $pagina, MarcadorRepository $marcadorRepository)
    {
        $marcadores = $marcadorRepository->buscarPorFavoritos($pagina, self::ELEMENTOS_POR_PAGINA);

        return $this->render('index/index.html.twig', [
            'marcadores' => $marcadores,
            'pagina' => $pagina,
            'elementos_por_pagina' => self::ELEMENTOS_POR_PAGINA
        ]);
    }

    /** Panel
     * @Route(
     * "/{categoria}/{pagina}", 
     * name="app_index",
     * defaults = {
     *      "categoria": "todas",
     *      "pagina": 1,
     *  },
     *  requirements = {"pagina"="\d+"}
     * )
     */
    public function index(string $categoria, int $pagina, CategoriaRepository $categoriaRepository, MarcadorRepository $marcadorRepository): Response
    {
        $categoria = (int) $categoria > 0 ? (int) $categoria : $categoria;
        if(is_int($categoria)) {
            $categoria = "todas";
            $pagina = $categoria;
        }
        if ($categoria && "todas" != $categoria) {
            if (! $categoriaRepository->findByNombre($categoria)) {
                throw $this->createNotFoundException("La categoría '$categoria' no existe!");
            }
            $marcadores = $marcadorRepository->buscarCategoriaPorNombre($categoria , $pagina, self::ELEMENTOS_POR_PAGINA);
        } else {
            $marcadores = $marcadorRepository->buscarTodos($pagina, self::ELEMENTOS_POR_PAGINA);
        }
        
        return $this->render('index/index.html.twig', [
            'marcadores' => $marcadores,
            'pagina' => $pagina,
            'parametros_ruta' => [
                'categoria' => $categoria,
            ],
            'elementos_por_pagina' => self::ELEMENTOS_POR_PAGINA,
        ]);
    }
}
