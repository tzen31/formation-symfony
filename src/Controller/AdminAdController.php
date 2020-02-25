<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Service\PaginationService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     */
    public function index(AdRepository $repo, $page,PaginationService $pagination)
    {
        /*
        // Méthode find : permet de retrouver un enregistrement par son identifiant 
        $ad = $repo->find(432);
        $ad = $repo->findOneBy([
            'title' => "Annonce corrigée !",
            'id' => 433
        ]);

        $ads = $repo->findBy([], [], 5,0);
        dump($ads);
        */
        //$limit = 10;
        //$start = $page * $limit - $limit;
        //$total = count($repo->findAll());
        //$pages = ceil($total / $limit); //arrondi au dessus
        $pagination->setEntityClass(Ad::class)
                   ->setPage($page);
                   //->setRoute('admin_ads_index');

        return $this->render('admin/ad/index.html.twig', [
            //'ads' => $repo->findAll()
            //'ads' => $repo->findBy([], [], $limit, $start),
            //'pages' => $pages,
            //'page' => $page
            'pagination' => $pagination
        ]);
    }

    /**
	 * Permet d'afficher le formulaire d'édition
	 *
	 * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
	 *
	 * @param Ad $ad
	 * @return Response
	 */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager) {
    	$form = $this->createForm(AdType::class, $ad);

    	$form->handleRequest($request);

    	if ($form->isSubmitted() && $form->isValid()) {
    		$manager->persist($ad);
    		$manager->flush();

    		$this->addFlash(
    			'success',
    			"L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !"
    		);
    	}

    	return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
    		'form' => $form->createView()
    	]);
    }

    /**
    * Permet de supprimer une annonce
    *
    * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
    * 
    * @param Ad $ad
    * @param ObjectManager $manager
    * @return Response
    */
    public function delete(Ad $ad, EntityManagerInterface $manager) {
        if (count($ad->getBookings()) > 0) 
        {
             $this->addFlash(
            'warning',
            "Vous ne pouvez pas supprimer l'annonce <strong>{$ad->getTitle()}</strong> car elle possède déjà des réservations !"
            );
        }
        else
        {
            $manager->remove($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !"
            );
        }

        return $this->redirectToRoute('admin_ads_index');
    }
}
