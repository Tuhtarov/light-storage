<?php

namespace App\Controller;

use Gedmo\Sluggable\Util\Urlizer;
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
        $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/';

        $files = scandir($destination);

        if (!empty($files)) {
            $this->addFlash('info', 'total files in your pc: ' . count($files));
        }

        return $this->render('main/index.html.twig');
    }

    #[Route('/', name: 'upload', methods: ['POST'])]
    public function upload(Request $request): Response
    {
        /** @var $files array<UploadedFile> */
        $files = $request->files->get('myFiles');
        $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/';
        $filesCnt = 0;

        if (!empty($files)) {
            foreach ($files as $file) {
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                if (!$file->isReadable()) {
                    $file->move($destination, $file->getClientOriginalName());
                } else {
                    $fileExtension = $file->guessExtension();

                    if (!empty($fileExtension)) {
                        $file->move($destination, $this->getNewFileName($originalName, $fileExtension));
                        $filesCnt++;
                    }
                }
            }
        }

        $this->addFlash($filesCnt === 0 ? 'error' : 'success', "Total files has been uploaded: $filesCnt");

        return $this->redirectToRoute('index');
    }

    private function getNewFileName(string $originalName, string $extension): string
    {
//        return Urlizer::urlize($originalName) . '-' . uniqid('') . '.' . $extension;
        return Urlizer::urlize($originalName) . '.' . $extension;
    }
}
