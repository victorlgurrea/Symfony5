<?php

namespace App\Controller;
use App\Repository\MarcadorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \Knp\Snappy\Pdf;
class PDFController extends AbstractController
{
    /**
     * @Route("/descargar-informe-pdf", name="app_descargar_informe_pdf" , priority=7)
     */
    public function descargarInforme(Pdf $nappyPdf, MarcadorRepository $marcadorRepository)
    {
        $marcadores = $marcadorRepository->findBy(['usuario' => $this->getUser()]);
        
        $htmlPDF = $this->renderView('pdf/informe.html.twig', [
            'marcadores' => $marcadores
        ]);
            

        return new Response(
                $nappyPdf->getOutputFromHtml($htmlPDF,
                    [
                    ]),
                200,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-disposition' => 'attachment;filename="informe.pdf"',
                ]
            );
    }

     /**
     * @Route("/descargar-terminos", name="app_descargar_terminos" , priority=8)
     */
    public function descargarTerminos(Pdf $nappyPdf)
    {
        
        $htmlPDF = $this->renderView('pdf/terminos.html.twig', []);
            
        return new Response(
                $nappyPdf->getOutputFromHtml($htmlPDF,
                    [
                    ]),
                200,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-disposition' => 'attachment;filename="terminos_de_uso.pdf"',
                ]
            );
    }
}
