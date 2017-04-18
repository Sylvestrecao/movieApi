<?php
namespace UserBundle\Controller;

use AppBundle\Form\UserType;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Controller\ProfileController as BaseController;

class ProfileController extends BaseController
{
    public function showAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $user_id = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $favoriteMovies = $em->getRepository('AppBundle:Movie')->getUserFavoriteMovies($user_id);

        return $this->render('@FOSUser/Profile/show.html.twig', array(
            'user' => $user,
            'favoriteMovies' => $favoriteMovies
        ));
    }

    public function editAction(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(UserType::class, $user);
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Votre profil a été modifié avec succès !');

            return $this->redirectToRoute('fos_user_profile_show');
        }

        return $this->render('@FOSUser/Profile/edit_content.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}