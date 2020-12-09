<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MarcadorRepository;
use Symfony\Component\HttpFoundation\Request;

class FavoritoController extends AbstractController
{
    public const ELEMENTOS_POR_PAGINA = 4;
    
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

}
