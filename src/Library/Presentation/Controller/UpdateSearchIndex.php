<?php

declare(strict_types=1);

namespace DZunke\NovDoc\Library\Presentation\Controller;

use DZunke\NovDoc\Library\Infrastructure\VectorStorage\Updater;
use DZunke\NovDoc\Shared\Presentation\FlashMessages\Alert;
use DZunke\NovDoc\Shared\Presentation\FlashMessages\HandleFlashMessages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;

#[Route('/reload', name: 'update_search_index')]
class UpdateSearchIndex extends AbstractController
{
    use HandleFlashMessages;

    public function __construct(
        private Updater $updater,
        private RouterInterface $router,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $this->updater->updateAll();
        $this->addFlashMessage(
            $request,
            Alert::SUCCESS,
            'Veränderte Dokumente wurden in den Suchindex geladen, ab in die Schenke zu einem Plausch!',
        );

        return new RedirectResponse($this->router->generate('chat'));
    }
}
