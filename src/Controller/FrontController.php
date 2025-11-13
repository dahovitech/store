<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\LanguageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur principal du frontend gérant les pages publiques.
 *
 * Responsable de l'affichage de la homepage, page de contact et à propos.
 *
 * @author MiniMax Agent
 */
#[Route('/{_locale}', requirements: ['_locale' => '[a-z]{2}'])]
final class FrontController extends AbstractController
{
    public function __construct(
        private readonly LanguageRepository $languageRepository
    ) {}

    /**
     * Homepage avec locale
     */
    #[Route('/', name: 'app_homepage')]
    public function homepage(Request $request): Response
    {
        $locale = $request->getLocale();
        $currentLanguage = $this->languageRepository->findByCode($locale);

        return $this->render('frontend/homepage.html.twig', [
            'currentLanguage' => $currentLanguage,
            'locale' => $locale
        ]);
    }

}
