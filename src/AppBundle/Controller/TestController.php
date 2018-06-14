<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Todo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TestController extends Controller
{
    /**
     * @Route("/", name="test")
     */
    public function testAction(Request $request)
    {
        $todo = $this -> getDoctrine() -> getRepository('AppBundle:Todo') -> findAll();

        return $this -> render('test/index.html.twig', array(
            'todos' => $todo
        ));
    }

    /**
     * @Route("/test/create/{id}", name="create")
     */
    public function createAction(Request $request)
    {
        $todo = new Todo;
        $form = $this->createFormBuilder($todo)
        -> add('name', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        -> add('category', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        -> add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        -> add('priority', ChoiceType::class, array('choices' => array('Low' => 'Low', 'Normal' => 'Normal', 'High' => 'High')))
        -> add('duedate', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        -> add('createdate', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        -> add('Save', SubmitType::class, array('label' => 'Create ToDo', 'attr' => array('class' => 'btn-primary', 'style' => 'margin-bottom:15px')))

        -> getForm();

        $form -> handleRequest($request);
        if( $form -> isSubmitted() && $form -> isValid()){

            $name = $form['name'] -> getData();
            $category = $form['category'] -> getData();
            $description = $form['description'] -> getData();
            $priority = $form['priority'] -> getData();
            $dueDate = $form['duedate'] -> getData();
            $createDate = $form['createdate'] -> getData();

            $todo -> setName($name);
            $todo -> setCategory($category);
            $todo -> setDescription($description);
            $todo -> setPriority($priority);
            $todo -> setDueDate($dueDate);
            $todo -> setCreateDate($createDate);
    
            $em = $this->getDoctrine()->getManager();
            $em -> persist($todo);
            $em -> flush();
            $this -> addFlash(
                'notice',
                'Todo Added'
            );
            return $this -> redirectToRoute('test');
        }

        

        // replace this example code with whatever you need
        return $this->render('test/create.html.twig', array(
            'form' => $form -> createView()
        ));
    }

    /**
     * @Route("/test/edit/{id}", name="edit")
     */
    public function editAction($id, Request $request)
    {
        $todo = $this -> getDoctrine() -> getRepository('AppBundle:Todo') -> find($id);

        $todo -> setName($todo->getName());
        $todo -> setCategory($todo->getCategory());
        $todo -> setDescription($todo->getDescription());
        $todo -> setPriority($todo->getPriority());
        $todo -> setDueDate($todo->GetdueDate());
        $todo -> setCreateDate($todo->GetcreateDate());

        $form = $this->createFormBuilder($todo)
        -> add('name', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        -> add('category', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        -> add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        -> add('priority', ChoiceType::class, array('choices' => array('Low' => 'Low', 'Normal' => 'Normal', 'High' => 'High')))
        -> add('duedate', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        -> add('createdate', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        -> add('Save', SubmitType::class, array('label' => 'Create ToDo', 'attr' => array('class' => 'btn-primary', 'style' => 'margin-bottom:15px')))
        -> getForm();

        $form -> handleRequest($request);
        if( $form -> isSubmitted() && $form -> isValid()){

            $name = $form['name'] -> getData();
            $category = $form['category'] -> getData();
            $description = $form['description'] -> getData();
            $priority = $form['priority'] -> getData();
            $dueDate = $form['duedate'] -> getData();
            $createDate = $form['createdate'] -> getData();

            $em = $this->getDoctrine()->getManager();
            $todo = $em->getRepository('AppBundle:Todo') -> find($id);

            $todo -> setName($name);
            $todo -> setCategory($category);
            $todo -> setDescription($description);
            $todo -> setPriority($priority);
            $todo -> setDueDate($dueDate);
            $todo -> setCreateDate($createDate);
    
            $em -> flush();
            $this -> addFlash(
                'notice',
                'Todo Updated'
            );
            return $this -> redirectToRoute('test');
        }

        return $this -> render('test/edit.html.twig', array(
            'todo' => $todo,
            'form' => $form -> createView()
        ));
    }

    /**
     * @Route("/test/details/{id}", name="detail")
     */
    public function detailAction($id)
    {
        
        $todo = $this -> getDoctrine() -> getRepository('AppBundle:Todo') -> find($id);

        return $this -> render('test/details.html.twig', array(
            'todo' => $todo
        ));
    }

    /**
     * @Route("/test/delete/{id}", name="delete")
    */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $todo = $em->getRepository('AppBundle:Todo') -> find($id);

        $em -> remove($todo);
        $em -> flush();

        $this -> addFlash('notice', 'todo removed');
        return $this -> redirectToRoute('test');
    }
}
