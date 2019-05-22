<?php

namespace App\Form;

use App\Entity\Expense;
use App\Entity\Category;
use App\Entity\User;
use App\Form\CategoryType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ExpenseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amout', MoneyType::class, ['label' => 'Kwota', 'currency' => 'PLN', ])
            ->add('date', DateType::class, ['label' => 'Data', 'widget' => 'single_text',

            // prevents rendering it as type="date", to avoid HTML5 date pickers
            'html5' => false,
        
            // adds a class that can be selected in JavaScript
            'attr' => ['class' => 'js-datepicker'],])
            ->add('description', TextType::class, ['label' => 'Opis','required' => false,])
            ->add('category', EntityType::class,
            [ 'class' => Category::class, 'query_builder' => function (EntityRepository $repository) { 
                return $repository->createQueryBuilder('u')->orderBy('u.name', 'ASC');
            }, 'label' => 'Kategoria',] )
            //->add('user', EntityType::class, ['label' => 'Użytkownik', 'class' => User::class, 'attr' => ['readonly']])
            //->add('zapisz', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Expense::class,
        ]);
    }
}
