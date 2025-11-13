<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Entity\CompanyInfo;
use App\Entity\Setting;
use App\Form\CompanyInfoType;
use App\Form\SettingType;
use App\Repository\OrderRepository;
use App\Repository\PaymentRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\LanguageRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CompanyInfoRepository;
use App\Repository\SettingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function dashboard(
    ): Response {
       
        return $this->render('admin/dashboard.html.twig', [
        ]);
    }

     #[Route('/setting', name: 'setting', methods: ["GET", "POST"])]
    public function setting(Request $request, SettingRepository $settingRepository, EntityManagerInterface $entityManager): Response
    {
        $setting = $settingRepository->findOneBy([], ['id' => 'desc']);

        if ($setting === null) {
            $setting = new Setting();
        }

        $form = $this->createForm(SettingType::class, $setting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Utilisation de Doctrine ORM pour la mise à jour de l'entité
            $entityManager->persist($setting);
            $entityManager->flush();

            $this->addFlash('success', "setting.success");
        }

        return $this->render('admin/setting.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
