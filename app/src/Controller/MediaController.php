<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\DocumentImage;
use App\Entity\DocumentVideo;
use App\Entity\DocumentAudio;
use App\Entity\DocumentFichier;
use App\Enum\DocumentNiveauConfidentialiteEnum;
use App\Enum\DocumentLangueDocumentEnum;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class MediaController extends AbstractController
{
    #[Route('/media', name: 'app_media')]
    public function index(DocumentRepository $documentRepository): Response
    {
        $media = $documentRepository->findAll();
        
        $mediaData = [];
        foreach ($media as $document) {
            $mediaData[] = [
                'id' => $document->getId(),
                'title' => $document->getNom() ?? 'Sans titre',
                'type' => $this->getDocumentType($document),
                'date' => $document->getDateCreation() ? $document->getDateCreation()->format('d/m/Y') : 'N/A',
                'offense' => $document->getDelit() ? $this->truncateText($document->getDelit()->getDescription(), 50) : 'Aucun',
                'confidentiality' => $document->getNiveauConfidentialite() ? ucfirst($document->getNiveauConfidentialite()->value) : 'Public',
                'filename' => $document->getNom()
            ];
        }

        return $this->render('media/media.html.twig', [
            'media' => $mediaData,
        ]);
    }

    #[Route('/media/upload', name: 'app_media_upload', methods: ['POST'])]
public function upload(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): JsonResponse
{
    try {
        $uploadedFile = $request->files->get('file');
        
        if (!$uploadedFile) {
            return new JsonResponse(['success' => false]);
        }

        $originalFilename = $uploadedFile->getClientOriginalName();
        $fileSize = $uploadedFile->getSize() ?: 0;
        $extension = strtolower(pathinfo($originalFilename, PATHINFO_EXTENSION));
        
        $document = $this->createDocumentByExtension($extension);
        
        if (!$document) {
            return new JsonResponse(['success' => false]);
        }

        $filenameWithoutExtension = pathinfo($originalFilename, PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($filenameWithoutExtension);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$extension;

        $uploadsDirectory = $this->getParameter('uploads_directory') ?? 'public/uploads';
        if (!is_dir($uploadsDirectory)) {
            mkdir($uploadsDirectory, 0755, true);
        }

        $uploadedFile->move($uploadsDirectory, $newFilename);

        $document->setNom($filenameWithoutExtension);
        $document->setChemin('uploads/' . $newFilename);
        $document->setDateCreation(new \DateTime());
        $document->setNiveauConfidentialite(DocumentNiveauConfidentialiteEnum::Public);
        $document->setTailleFichier((string)$fileSize);
        $document->setEstArchive(false);
        $document->setLangueDocument(DocumentLangueDocumentEnum::FR);

        $entityManager->persist($document);
        $entityManager->flush();

        return new JsonResponse(['success' => true]);

    } catch (\Exception $e) {
        return new JsonResponse(['success' => false]);
    }
}

    private function createDocumentByExtension(string $extension): ?Document
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];
        $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm', 'mkv'];
        $audioExtensions = ['mp3', 'wav', 'ogg', 'flac', 'aac', 'm4a'];
        
        if (in_array($extension, $imageExtensions)) {
            return new DocumentImage();
        } elseif (in_array($extension, $videoExtensions)) {
            return new DocumentVideo();
        } elseif (in_array($extension, $audioExtensions)) {
            return new DocumentAudio();
        } else {
            return new DocumentFichier();
        }
    }

    private function getDocumentType(Document $document): string
    {
        return match (get_class($document)) {
            DocumentImage::class => 'Image',
            DocumentVideo::class => 'Vidéo',
            DocumentAudio::class => 'Audio',
            DocumentFichier::class => 'Fichier',
            default => 'Document'
        };
    }

    private function truncateText(string $text, int $length): string
    {
        if (strlen($text) <= $length) {
            return $text;
        }
        return substr($text, 0, $length) . '...';
    }
}