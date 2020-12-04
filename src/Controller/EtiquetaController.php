<?php

namespace App\Controller;

use App\Entity\Etiqueta;
use App\Form\EtiquetaType;
use App\Repository\EtiquetaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/etiqueta")
 */
class EtiquetaController extends AbstractController
{
    /**
     * @Route("/", name="etiqueta_index", methods={"GET"})
     */
    public function index(EtiquetaRepository $etiquetaRepository): Response
    {
        return $this->render('etiqueta/index.html.twig', [
            'etiquetas' => $etiquetaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="etiqueta_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $etiquetum = new Etiqueta();
        $form = $this->createForm(EtiquetaType::class, $etiquetum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($etiquetum);
            $entityManager->flush();

            return $this->redirectToRoute('etiqueta_index');
        }

        return $this->render('etiqueta/new.html.twig', [
            'etiquetum' => $etiquetum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="etiqueta_show", methods={"GET"})
     */
    public function show(Etiqueta $etiquetum): Response
    {
        return $this->render('etiqueta/show.html.twig', [
            'etiquetum' => $etiquetum,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="etiqueta_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Etiqueta $etiquetum): Response
    {
        $form = $this->createForm(EtiquetaType::class, $etiquetum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('etiqueta_index');
        }

        return $this->render('etiqueta/edit.html.twig', [
            'etiquetum' => $etiquetum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="etiqueta_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Etiqueta $etiquetum): Response
    {
        if ($this->isCsrfTokenValid('delete'.$etiquetum->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($etiquetum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('etiqueta_index');
    }

    

    /**
     * @Route("/", name="app_buscar_etiquetas")
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
    * @Route("/{id}/eliminar", name="app_eliminar_etiqueta")
    */
    public function eliminar(Etiqueta $etiqueta, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($etiqueta);
        $entityManager->flush();
        $this->addFlash('success', "Etiqueta eliminada correctamente");
        
        return $this->redirectToRoute('etiqueta_index');

    }
}
