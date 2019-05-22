<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Expense;
use App\Entity\Category;
use App\Form\ExpenseType;
use App\Form\SearchedType;
use Symfony\Component\Validator\Constraints\DateTime;



class ExpenseController extends AbstractController
{
    /**
     * @Route("/expense", name="expense")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        
        $repository = $this->getDoctrine()->getRepository(Expense::class);
        $sum = $repository->sumAll($user->getId());
        //$expense = $repository->findAll();
        $expense = $repository->findByUser($user->getId());

        return $this->render('expense/show.html.twig', [
            'expenses' => $expense, 'sum' => $sum,
        ]);
    }

    /**
     * @Route("/expense/show", name="show")
     */
    public function show()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();
        $startDate = date("Y-m-d", strtotime("-1 Month -1 Day"));
        $endDate = date("Y-m-d");

        $repository = $this->getDoctrine()->getRepository(Expense::class);

        $sum = $repository->sumOneMonth($user->getId(), $startDate, $endDate);

        //$expense = $repository->findAll();
        $expense = $repository->findByUserOneMonth($user->getId(), $startDate, $endDate);
        
        return $this->render('expense/show.html.twig', [
            'expenses' => $expense, 'sum' => $sum,
        ]);
    }

    /**
     * @Route("/expense/edit/{id}", name="edit")
     */
    public function edit(Expense $expense, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        //$expense = $em->getRepository(Expense::class)->find($id);

        $form = $this->createForm(ExpenseType::class, $expense);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $expense->setUser($user);
            $em->persist($expense);
            $em->flush();

            return $this->redirectToRoute('expense');
        }

        return $this->render('expense/edit.html.twig', [
            'form' => $form->createView() , 'expense' => $expense ,
        ]);
    }

    /**
     * @Route("/expense/new", name="new")
     */
    public function new(Request $request)
    {
        $expense = new Expense();
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        //$expense = $em->getRepository(Expense::class)->find($id);

        $form = $this->createForm(ExpenseType::class, $expense);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $expense->setUser($user);
            $em->persist($expense);
            $em->flush();

            return $this->redirectToRoute('expense');
        }

        return $this->render('expense/edit.html.twig', [
            'form' => $form->createView() , 'expense' => $expense ,
        ]);
    }

    /**
     * @Route("/expense/delete/{id}", name="delete")
     */
    public function delete(Request $request, Expense $expense)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        //if($this->isCsrfTokenValid('delete', $expense->getId(), $request->request->get('_token'))){
            $expense->getId();
            $em = $this->getDoctrine()->getManager();
            $em->remove($expense);
            $em->flush();
        //}
        return $this->redirectToRoute('expense');
    }

    /**
     * @Route("/expense/search", name="search")
     */
    public function search(Request $request)
    {
        //$expense = new Expense();
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        $repository = $this->getDoctrine()->getRepository(Expense::class);

        $em = $this->getDoctrine()->getManager();
        //$expense = $em->getRepository(Expense::class)->find($id);

        $form = $this->createForm(SearchedType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
                $expense = $repository->findBySearchParamAll(
                    $user->getId(),
                    $form["datestart"]->getData(),
                    $form["dateend"]->getData(),
                    $form["description"]->getData(),
                    $form["category"]->getData()
                 );
                $sum = $repository->SumForSearchParamAll(
                    $user->getId(),
                    $form["datestart"]->getData(),
                    $form["dateend"]->getData(),
                    $form["description"]->getData(),
                    $form["category"]->getData()
                );
        
             return $this->render('expense/show.html.twig', [
            'expenses' => $expense, 'sum' => $sum, 
        ]);
        }

        return $this->render('expense/search.html.twig', [
            'form' => $form->createView() , 
        ]);
    }
}
