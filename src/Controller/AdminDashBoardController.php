<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\StatsService;

class AdminDashBoardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(EntityManagerInterface $manager, StatsService $statsService)
    {
        //$users = $manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
        //$ads = $manager->createQuery('SELECT COUNT(a) FROM App\Entity\Ad a')->getSingleScalarResult();
        //$bookings = $manager->createQuery('SELECT COUNT(b) FROM App\Entity\Booking b')->getSingleScalarResult();
        //$comments = $manager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')->getSingleScalarResult();
        /*$users    = $statsService->getUsersCount();
        $ads      = $statsService->getAdsCount();
        $bookings = $statsService->getBookingsCount();
        $comments = $statsService->getCommentsCount();*/
        $stats = $statsService->getStats();

        //dump($users);

        /*$bestAds = $manager->createQuery(
        	'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture 
        	FROM App\Entity\Comment c
        	JOIN c.ad a
        	JOIN a.author u
        	GROUP BY a
        	ORDER BY note DESC'
        )
        ->setMaxResults(5)
        ->getResult();*/

        //$bestAds = $statsService->getBestAds();
        $bestAds = $statsService->getAdsStats('DESC');

        //dump($bestAds);

		/*$worstAds = $manager->createQuery(
        	'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture 
        	FROM App\Entity\Comment c
        	JOIN c.ad a
        	JOIN a.author u
        	GROUP BY a
        	ORDER BY note DESC'
        )
        ->setMaxResults(5)
        ->getResult();*/
        $worstAds =$statsService->getAdsStats('ASC');

        return $this->render('admin/dashboard/index.html.twig', [
        	/*'stats' => [
	            'users' => $users,
	            'ads' => $ads,
	            'bookings' => $bookings,
	            'comments' => $comments
	        ]
	        */
	           //'stats' => compact('users', 'ads', 'bookings', 'comments'),
	           'stats' => $stats,
	           'bestAds' => $bestAds,
	           'worstAds' => $worstAds     	
        ]);
    }
}
