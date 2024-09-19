<?php

declare(strict_types=1);

namespace DZunke\NovDoc\Web\Controller\Application;

use DZunke\NovDoc\Infrastructure\Application\Importer;
use DZunke\NovDoc\Infrastructure\Application\ImportSettings;
use DZunke\NovDoc\Web\FlashMessages\HandleFlashMessages;
use DZunke\NovDoc\Web\Form\Application\ImportType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

use function assert;
use function is_array;

#[Route('/application/import', name: 'application_import')]
class Import
{
    use HandleFlashMessages;

    public function __construct(
        private readonly Environment $environment,
        private readonly FormFactoryInterface $formFactory,
        private readonly RouterInterface $router,
        private readonly Importer $importer,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(ImportType::class, ['overwrite_settings' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $archive = $form->get('archive')->getData();
            assert($archive instanceof UploadedFile);

            $fullFormData = $form->getData();
            assert(is_array($fullFormData));

            $this->importer->import($archive->getRealPath(), ImportSettings::fromArray($fullFormData));

            return new RedirectResponse($this->router->generate('library'));
        }

        return new Response($this->environment->render(
            'settings/import.html.twig',
            ['form' => $form->createView()],
        ));
    }
}
