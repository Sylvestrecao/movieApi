<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class UserController extends Controller
{
    /**
     * @Route("/user/profile", name="user_profile")
     * @Method("GET")
     */
    public function userIndexAction()
    {
       return $this->render('AppBundle:User:user-profile.html.twig');
    }
}
