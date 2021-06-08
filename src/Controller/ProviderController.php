<?php

namespace App\Controller;

use App\Entity\Provider;
use App\Form\ProviderType;
use App\Repository\ProviderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/provider")
 */
class ProviderController extends AbstractController
{
    /**
     * @Route("/list", name="provider_list", methods={"GET"})
     */
    public function index(ProviderRepository $providerRepository): Response
    {
        $ecole="sesame";
        $providers = $providerRepository->findAll();
         return $this->render('provider/list.html.twig',
         ['providers'=>$providers, 'ecole'=>$ecole] );
         /**return $this->render('provider/index.html.twig', [
            'providers' => $providerRepository->findAll(),
        ]); */
    }
/**
     * @Route("/ajout", name="provider_ajout", methods={"GET","POST"})
     */
    public function ajout(Request $request,ProviderRepository $providerRepository): Response
    {
        if ($request->getMethod()=="POST")
        {
            $name = $request->get('name');
           
            $email = $request->get('email');
            $adress = $request->get('adress');
            $provider = new Provider();

            $provider->setName($name);
            $provider->setEmail($email);
            $provider->setAdress($adress);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($provider);
            $entityManager->flush();
            return $this->redirectToRoute('provider_list');

           // return new Response("insertion : ".$name);
        }
         return $this->render('provider/ajout.html.twig' );
         /**return $this->render('provider/index.html.twig', [
            'providers' => $providerRepository->findAll(),
        ]); */
    }
    /**
     * @Route("/delete/{id}", name="provider_delete2", methods={"GET"})
     */
    public function suppression(ProviderRepository $providerRepository,$id): Response
    {
       

         // return new Response("ID: ".$id);
           $provider = $providerRepository->find($id);

           $entityManager = $this->getDoctrine()->getManager();
           $entityManager->remove($provider);
           $entityManager->flush();
           return $this->redirectToRoute('provider_list');

        
    }
    /**
     * @Route("/new", name="provider_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $provider = new Provider();
        $form = $this->createForm(ProviderType::class, $provider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($provider);
            $entityManager->flush();

            return $this->redirectToRoute('provider_index');
        }

        return $this->render('provider/new.html.twig', [
            'provider' => $provider,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="provider_show", methods={"GET"})
     */
    public function show(Provider $provider): Response
    {
        return $this->render('provider/show.html.twig', [
            'provider' => $provider,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="provider_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Provider $provider): Response
    {
        $form = $this->createForm(ProviderType::class, $provider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('provider_index');
        }

        return $this->render('provider/edit.html.twig', [
            'provider' => $provider,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="provider_delete", methods={"POST"})
     */
    public function delete(Request $request, Provider $provider): Response
    {
        if ($this->isCsrfTokenValid('delete'.$provider->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($provider);
            $entityManager->flush();

        }

        return $this->redirectToRoute('provider_index');
    }
}
