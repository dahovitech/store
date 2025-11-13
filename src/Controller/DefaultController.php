<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur par défaut gérant la redirection locale et le changement de langue.
 *
 * Responsable de la gestion de la page d'accueil par défaut
 * et du switch de locale pour l'internationalisation.
 */
final class DefaultController extends AbstractController
{
    /**
     * Redirige vers la homepage avec la locale appropriée.
     *
     * @param Request $request La requête HTTP
     *
     * @return Response La réponse de redirection
     */
    #[Route('/', name: 'default')]
    public function homepage(Request $request): Response
    {
        $locale = $request->getLocale();

        return $this->redirectToRoute('app_homepage', [
            '_locale' => $locale,
        ]);
    }

    /**
     * Change la locale de l'utilisateur et redirige vers la page d'accueil.
     *
     * Stocke la locale dans la session pour persistence.
     *
     * @param string  $locale  La nouvelle locale à appliquer
     * @param Request $request La requête HTTP
     *
     * @return RedirectResponse La réponse de redirection
     */
    #[Route('/localeswitch/{_locale}', name: 'locale_switch', methods: ['GET'])]
    public function localeSwitch(string $_locale, Request $request): RedirectResponse
    {
        // Stocker la locale dans la session pour persistence
        $request->getSession()->set('_locale', $_locale);

        // Récupérer l'URL de référence pour revenir à la page précédente si possible
        $referer = $request->headers->get('referer');
        
        // Si on a un referer et qu'il est sur le même domaine, rediriger vers lui
        if ($referer !== null && str_starts_with($referer, $request->getSchemeAndHttpHost())) {
            // Remplacer l'ancienne locale par la nouvelle dans l'URL
            $currentLocale = $request->getLocale();
            $newUrl = str_replace(
                '/' . $currentLocale . '/',
                '/' . $_locale . '/',
                $referer
            );
            
            return $this->redirect($newUrl);
        }

        // Sinon, rediriger vers la homepage avec la nouvelle locale
        return $this->redirectToRoute('app_homepage', [
            '_locale' => $_locale,
        ]);
    }

    /**
     * Point d'entrée pour la déconnexion.
     *
     * Cette méthode ne sera jamais exécutée car interceptée par le firewall.
     *
     * @throws \LogicException Toujours, car cette méthode ne devrait jamais être appelée
     */
    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
