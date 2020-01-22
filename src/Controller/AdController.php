<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AdType;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class AdController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/ads", name="ads_index")
     */
    //public function index(AdRepository $repo, SessionInterface $session)
    public function index(AdRepository $repo)
    {
    	//$repo = $this->getDoctrine()->getRepository(Ad::class);
		//dump($session);

    	$ads = $repo->findAll();

        //return $this->render('ad/index.html.twig', [
        //    'controller_name' => 'AdController',
        //]);
        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
        ]);

    }

    /**
    * Permet de créér une annonce
    *
    * @Route("/ads/new", name="ads_create")
    *
    * @return Response
    */
    //public function create(Request $request, ObjectManager $manager)
    public function create(Request $request, EntityManagerInterface $manager)    
    {
        $ad= new Ad();

        /*$image = new Image();
        $image->setUrl('http://placehold.it/400x200')
              ->setCaption('Titre 1');

        $image2 = new Image();
        $image2->setUrl('http://placehold.it/400x200')
               ->setCaption('Titre 2');

        $ad->addImage($image)
           ->addImage($image2);*/

        /*$form = $this->createFormBuilder($ad)
                     ->add('title',)
                     ->add('introduction')
                     ->add('content')
                     ->add('rooms')
                     ->add('price')
                     ->add('coverImage')
                     ->add('save', SubmitType::class, [
                        'label' => 'Créer la nouvelle annonce',
                        'attr' => [
                            'class' => 'btn btn-primary'
                        ]
                    ])
                     ->getForm();*/
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !"
            );
            /*
            $this->addFlash(
                    'success',
                    "Deuxième flash"
            );
            $this->addFlash(
                    'danger',
                    "Message d'erreur"
            );*/

            //dump($ad);
        
            foreach($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            //$manager = $this->getDoctrine()->getManager();
            $manager->persist($ad);
            $manager->flush();

            return $this->redirectToRoute('ads_show', [
                'slug'=> $ad->getSlug()
            ]);
        }

        return $this->render('/ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
	* Permet d'afficher une seule annonce
	*
	* @Route("/ads/{slug}", name="ads_show")
	*
	* @return Response
    */
    //public function show($slug, AdRepository $repo)
    public function show(Ad $ad)
    {
    	//Je récupère l'annonce qui correspondau slug !
    	//$ad = $repo->findOneBySlug($slug);

    	return $this->render('ad/show.html.twig', [
    		'ad' => $ad
    	]);
    }


}
