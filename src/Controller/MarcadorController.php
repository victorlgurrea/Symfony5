<?php

namespace App\Controller;

use App\Entity\Marcador;
use App\Entity\Etiqueta;
use App\Entity\MarcadorEtiqueta;
use App\Form\MarcadorType;
use App\Form\MarcadorEtiquetaType;
use App\Form\EtiquetaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/marcador")
 */
class MarcadorController extends AbstractController
{

    /**
     * @Route("/new", name="marcador_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $marcador = new Marcador();
        $form = $this->createForm(MarcadorType::class, $marcador);
        $form->handleRequest($request);

        
        $etiquetum = new Etiqueta();
        $formEtiqueta = $this->createForm(EtiquetaType::class, $etiquetum, [
            'action' => $this->generateUrl('nueva_etiqueta_ajax')
        ]);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($marcador);
            $etiquetas = $form->get('etiquetas')->getData();
            foreach ($etiquetas as $etiqueta) {
                $marcadorEtiqueta = new MarcadorEtiqueta();
                $marcadorEtiqueta->setMarcador($marcador);
                $marcadorEtiqueta->setEtiqueta($etiqueta);
                $entityManager->persist($marcadorEtiqueta);
            }
            $entityManager->flush();

            $this->addFlash('success', 'Subasta creada correctamente!');

            return $this->redirectToRoute('app_index');
        }

        return $this->render('marcador/new.html.twig', [
            'marcador' => $marcador,
            'form' => $form->createView(),
            'form_etiqueta' => $formEtiqueta->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="marcador_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Marcador $marcador): Response
    {
        $form = $this->createForm(MarcadorType::class, $marcador);
        $form->handleRequest($request);

        $etiquetum = new Etiqueta();
        $formEtiqueta = $this->createForm(EtiquetaType::class, $etiquetum, [
            'action' => $this->generateUrl('nueva_etiqueta_ajax')
        ]);

        $marcadorEtiquetasActuales = $marcador->getMarcadorEtiquetas();
        
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $etiquetas = $form->get('etiquetas')->getData();

                foreach ($marcadorEtiquetasActuales as $marcadorEtiquetaActual) {
                    $eliminar = true;
                    $etiquetaActual = $marcadorEtiquetaActual->getEtiqueta();
                    foreach($etiquetas as $etiqueta) {
                        if($etiquetaActual->getId() == $etiqueta->getId()){
                            $eliminar = false;
                        }
                    }
                    if($eliminar) {
                        $entityManager->remove($marcadorEtiquetaActual);
                    }
                }

                foreach($etiquetas as $etiqueta) {
                    $crear = true;
                    foreach ($marcadorEtiquetasActuales as $marcadorEtiquetaActual) {
                        $etiquetaActual = $marcadorEtiquetaActual->getEtiqueta();
                        if($etiquetaActual->getId() == $etiqueta->getId()){
                            $crear = false;
                        }
                    }

                    if($crear){
                        $marcadorEtiqueta = new MarcadorEtiqueta();
                        $marcadorEtiqueta->setMarcador($marcador);
                        $marcadorEtiqueta->setEtiqueta($etiqueta);
                        $entityManager->persist($marcadorEtiqueta);
                    }
                }

                $entityManager->flush();
                $this->addFlash('success', 'Subasta editada correctamente!');
                return $this->redirectToRoute('app_index');
            }
        } else {
                $etiquetas = [];
                foreach ( $marcadorEtiquetasActuales as $marcadorEtiqueta) {
                    $etiquetas[] = $marcadorEtiqueta->getEtiqueta();
                }
                $form->get('etiquetas')->setData($etiquetas);
        }

        return $this->render('marcador/edit.html.twig', [
            'marcador' => $marcador,
            'form' => $form->createView(),
            'form_etiqueta' => $formEtiqueta->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="marcador_delete", methods={"DELETE"},  priority=10)
     */
    public function delete(Request $request, Marcador $marcador): Response
    {
        if ($this->isCsrfTokenValid('delete'.$marcador->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($marcador);
            $entityManager->flush();
            $this->addFlash('success', 'Subasta eliminada correctamente!');
        }
        

        return $this->redirectToRoute('app_index');
    }
}
