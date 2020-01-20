<?php

namespace App\Controller;

use App\Entity\Bookin;
use App\Form\BookinType;
use App\Repository\BookinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/home", name="home_article")
     */
    public function home(BookinRepository $repo){

        $bookins = $repo->findAll();

        return $this->render('home/home.html.twig',[
            'bookins' => $bookins
        ]);
    }

    /**
     * @Route("/home/new", name="home_create")
     * @Route("/home/{id}/edit", name="home_edit")
     */
    public function form(Bookin $bookin = null, Request $request, EntityManagerInterface $manager){

        if (!$bookin) {
            $bookin = new Bookin();
        }
        $form = $this->createForm(BookinType::class, $bookin);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            if (!$bookin->getId()) {
                $bookin->setCreatedAt(new \DateTime());
            }
        
            $manager->persist($bookin);
            $manager->flush();

            return $this->redirectToRoute('home_read', [
                'id' => $bookin->getId()
            ]);
        }
        
        return $this->render('home/create.html.twig',[
            'formBookin' => $form->createView(),
            'editMode' => $bookin->getId() !== null
        ]);
    }


    /**
     * @Route("/home/{id}", name="home_read")
     */
    public function read(BookinRepository $repo,$id){

        $bookin = $repo->find($id);
        return $this->render('home/read.html.twig',[
            'bookin' => $bookin
        ]);
    }
}
