<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository;

   

    public function __construct(PropertyRepository $repository)
    {
        $this->repository = $repository;
        
        
        
    }

    /**
     * @Route("/admin", name="admin.property.index")
     * @return \symfony\Component\HttpFoundation\Response
     */

    public function index()
    {
        $properties = $this->repository->findAll();
        return $this->render('admin/index.html.twig', compact('properties'));
    }
    /**
     * @route("/admin/create", name="admin.property.new")
     */
    public function new(Request $request, EntityManagerInterface $manager)
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($property);
            $manager->flush();
            $this->addFlash('success', 'bien créer avec succès');
            return $this->redirectToRoute('admin.property.index');  
        }

            return $this->render('admin/new.html.twig', [
                'porperty' => $property,
                'form' => $form->createView()
            ]);
    }

   

    /**
     * @Route("/admin/{id}", name="admin.property.edit", methods="GET|POST")
     * @param Property $property
     * @param Request $request
     * @return \symfony\Component\HttpFoundation\Response
     */
    public function edit(Property $property, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->flush();
            $this->addFlash('success', 'bien modifier avec suucès ');
            return $this->redirectToRoute('admin.property.index');

           
        }

        return $this->render('admin/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * @route("/admin/{id}", name="admin.property.delete", methods="DELETE")
     * @param Property $property
     * @param Request $request
     */
    public function delete(Property $property, Request $request, EntityManagerInterface $manager)
    {
        if ($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))){
            $manager->remove($property);
            $manager->flush();
            $this->addFlash('success', 'supprimer avec succès');
            return $this->redirectToRoute('admin.property.index');
        }
        
    }
}