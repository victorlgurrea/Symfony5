<?php

namespace App\Controller;

use App\Repository\CategoriaRepository;
use App\Repository\MarcadorRepository;
use App\Repository\EtiquetaRepository;
use App\Form\BuscadorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class IndexController extends AbstractController
{
    public const ELEMENTOS_POR_PAGINA = 5;
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
    public function index(
        string $categoria, 
        int $pagina, 
        CategoriaRepository $categoriaRepository, 
        MarcadorRepository $marcadorRepository,
        TranslatorInterface $translator,
        Security $security
        ): Response
    {
        $user = $security->getUser();

        if(! $user) {
           return $this->redirectToRoute('app_login');
        }

        $categoria = (int) $categoria > 0 ? (int) $categoria : $categoria;
        if(is_int($categoria)) {
            $categoria = "todas";
            $pagina = $categoria;
        }
        if ($categoria && "todas" != $categoria) {
            if (! $categoriaRepository->findBy([
                'nombre' => $categoria,
                'usuario' => $security->getUser()
                ])) {
                throw $this->createNotFoundException($translator->trans("La categoría \"{categoria}\" no existe!",
                [
                    '{categoria}' => $categoria
                ],
                    'messages'
            ));
            }
            $marcadores = $marcadorRepository->buscarCategoriaPorNombre($categoria , $pagina, self::ELEMENTOS_POR_PAGINA);
        } else {
            $marcadores = $marcadorRepository->buscarTodos($pagina, self::ELEMENTOS_POR_PAGINA);
        }
        $marcador = $marcadorRepository->findAll();
        dump($marcador);
        //die();
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
