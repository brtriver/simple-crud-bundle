<?php

namespace Brtriver\SimpleCrudBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brtriver\SimpleCrudBundle\Controller\SimpleCrudTraitController;
use Brtriver\SimpleCrudBundle\Annotation\Crud;

/**
 * @Route("/demo")
 * @Crud(
 *   entity="Brtriver\SimpleCrudBundle\Entity\Demo",
 *   repository="BrtriverSimpleCrudBundle:Demo",
 *   template_list="BrtriverSimpleCrudBundle:Demo:list.html.twig",
 *   template_new="BrtriverSimpleCrudBundle:Demo:new.html.twig",
 *   template_edit="BrtriverSimpleCrudBundle:Demo:edit.html.twig",
 *   template_show="BrtriverSimpleCrudBundle:Demo:show.html.twig",
 *   form="Brtriver\SimpleCrudBundle\Form\DemoType"
 * )
 */
class DemoController extends Controller
{
    use SimpleCrudTraitController;

    /**
     * override the route('/')
     *
     * @Route("/")
     */
    public function indexAction()
    {
        return new Response('index page');
    }
}
