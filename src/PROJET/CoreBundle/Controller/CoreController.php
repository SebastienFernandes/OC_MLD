<?php

namespace PROJET\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CoreController extends Controller
{
    public function indexAction()
    {
        return $this->render('PROJETCoreBundle:Core:index.html.twig');
    }
}
