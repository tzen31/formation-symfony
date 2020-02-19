<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Image;
use App\Repository\BookingRepository;
use App\Form\AdminBookingType;
use App\Service\PaginationService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings/{page<\d+>?1}", name="admin_booking_index")
     */
    public function index(BookingRepository $repo, $page, PaginationService $pagination)
    {
        //$test = $pagination->getInformation();
        //dump($test);
        //die();

        //$limit = 10;
        //$start = $page * $limit - $limit;
        $pagination->setEntityClass(Booking::class)
                   ->setPage($page)
                   //->setRoute('admin_booking_index');
                   ->setTemplatePath('admin/booking/pagination.html.twig'); //Pagination particulière
        //$bookings = $pagination->getData();
        //dump($bookings);
        //die();
        //$total = count($repo->findAll());
        //$pages = ceil($total / $limit); //arrondi au dessus

        return $this->render('admin/booking/index.html.twig', [
            //'bookings' => $repo->findAll()
            //'bookings' => $repo->findBy([], [], $limit, $start),
            //'pages' => $pages,
            //'bookings' => $pagination->getData(),            
            //'pages' => $pagination->getPages(),
            //'page' => $page
            'pagination' => $pagination
        ]);
    }

    /**
	 * Permet d'éditer une réservation
	 *
	 * @Route("/admin/bookings/{id}/edit", name="admin_booking_edit")
	 *
     * @param Booking $booking
	 * @return Response
	 */
    public function edit(Booking $booking, Request $request, EntityManagerInterface $manager) 
    {
    	/*$form = $this->createForm(AdminBookingType::class, $booking, [
            'validation_groups' => ["Default", "front"] 
        ]);*/
        $form = $this->createForm(AdminBookingType::class, $booking);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $booking->setAmount(0);

            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                'success',
                "La réservation n° {$booking->getId()} a bien été modifiée !"
            );

            return $this->redirectToRoute("admin_booking_index");
        }

        return $this->render('admin/booking/edit.html.twig', [    		
            'form' => $form->createView(),
            'booking' => $booking
    	]);
    }

    /**
     * Permet de supprimer une réservation
     *
     * @Route("/admin/bookings/{id}/delete", name="admin_booking_delete")
     * 
     * @param Booking $booking
     * @param ObjectManager $manager
     * @return Response
     */

    public function delete(Booking $booking, EntityManagerInterface $manager) 
    {
        $manager->remove($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            "La réservation de {$booking->getBooker()->getFullName()} a bien été supprimée !"
        );

        return $this->redirectToRoute('admin_booking_index');
    }    
}
