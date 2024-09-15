<?php

declare(strict_types=1);

namespace DZunke\NovDoc\Web\Controller\Library;

use DZunke\NovDoc\Domain\Document\Document;
use DZunke\NovDoc\Infrastructure\Repository\FilesystemDocumentRepository;
use DZunke\NovDoc\Infrastructure\Repository\FilesystemVectorDocumentRepository;
use DZunke\NovDoc\Web\FlashMessages\Alert;
use DZunke\NovDoc\Web\FlashMessages\HandleFlashMessages;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Routing\RouterInterface;

#[Route(
    '/library/document/{document}/delete',
    name: 'library_document_delete',
    requirements: ['directory' => Requirement::UUID],
)]
class DocumentDeletion
{
    use HandleFlashMessages;

    public function __construct(
        private readonly RouterInterface $router,
        private readonly FilesystemDocumentRepository $documentRepository,
        private readonly FilesystemVectorDocumentRepository $vectorDocumentRepository,
    ) {
    }

    public function __invoke(Request $request, Document $document): Response
    {
        $vectorDocuments = $this->vectorDocumentRepository->findAllByDocumentId($document->id);

        foreach ($vectorDocuments as $vectorDocument) {
            $this->vectorDocumentRepository->remove($vectorDocument);
        }

        $this->documentRepository->remove($document);

        $this->addFlashMessage(
            $request,
            Alert::SUCCESS,
            'Das Dokument "' . $document->title . '" wurde erfolgreich gelöscht.',
        );

        return new RedirectResponse($this->router->generate('library', ['directory' => $document->directory->id]));
    }
}
