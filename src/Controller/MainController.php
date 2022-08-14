<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class MainController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }

    #[Route('/', name: 'upload', methods: ['POST'])]
    public function upload(Request $request): Response
    {
        /**
         * @var $files array<UploadedFile>
         */
        $files = $request->files->get('myFiles');
        $dest = $this->getParameter('kernel.project_dir').'/public/uploads/';

        if (!empty($files)) {
            foreach ($files as $file) {
                $file->guessExtension();
            }
        }

        return $this->redirectToRoute('index');
    }
}
