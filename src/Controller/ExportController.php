<?php

namespace App\Controller;

use App\Service\ExportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/export', name: 'api_export_')]
class ExportController extends AbstractController
{
    public function __construct(
        private ExportService $exportService
    ) {}

    #[Route('/books', name: 'books', methods: ['GET'])]
    public function exportBooks(): Response
    {
        $response = new StreamedResponse(function () {
            $this->exportService->exportBooksToStream(fopen('php://output', 'w'));
        });

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            'books.csv'
        );

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
}
