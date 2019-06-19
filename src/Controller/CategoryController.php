<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CategoryType;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();
        
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $category = $repository->findAll();
        
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }
    
    
    /**
     * @Route("/category/edit/{id}", name="cat_edit")
     */
    public function edit(Category $category, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        //$expense = $em->getRepository(Expense::class)->find($id);

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category');
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView() , 'category' => $category ,
        ]);
    }

    /**
     * @Route("/category/new", name="cat_new")
     */
    public function new(Request $request)
    {
        $category = new Category();
        $this->denyAccessUnlessGranted('ROLE_USER');
                        
        $em = $this->getDoctrine()->getManager();
        

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category');
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView() , 'category' => $category ,
        ]);
    }

    /**
     * @Route("/category/delete/{id}", name="cat_delete")
     */
    public function delete(Request $request, Category $category)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        //if($this->isCsrfTokenValid('delete', $expense->getId(), $request->request->get('_token'))){
        try{
            $category->getId();
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        //}
        }
        catch (ForeignKeyConstraintViolationException $e) {
            $this->addFlash('error', "Nie można usunąć kategorii, istnieją powiązane wydatki.");
        }
        return $this->redirectToRoute('category');
    }
}
