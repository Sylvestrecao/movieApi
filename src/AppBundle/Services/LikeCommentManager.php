<?php
namespace AppBundle\Services;

use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
class LikeCommentManager
{
    protected $requestStack;
    protected $em;

    public function __construct(RequestStack $requestStack, EntityManager $em)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
    }

    public function addLike($id)
    {
        $request = $this->requestStack->getCurrentRequest();

        $comment = $this->em->getRepository('AppBundle:Comment')->find($id);
        $likeNumber = $comment->getCommentLike();
        $username = $comment->getUser()->getUsername();

        $getCookies = $request->cookies->get('intothemovieCookie');
        if(isset($getCookies)){
            $getCookiesArray = json_decode($getCookies, true);
            if(array_key_exists("comment".$id, $getCookiesArray)){
                $data = array('message' => 'Vous ne pouvez pas like ou dislike un même commentaire', 'likeNumber' => $likeNumber, 'class' => 'warning');
            }
            else{
                $newLikeNumber = $likeNumber + 1;
                $comment->setCommentLike($newLikeNumber);
                $this->em->persist($comment);
                $this->em->flush();
                // new key in cookie array
                $getCookiesArray["comment".$id] = 1;
                $cookieJson = json_encode($getCookiesArray);
                $cookie = new Cookie('intothemovieCookie', $cookieJson, strtotime("+1 year"));
                $response = new Response();
                $response->headers->setCookie($cookie);
                $response->send();

                $data = array('message' => 'Vous venez de like le commentaire de : '. $username, 'likeNumber' => $newLikeNumber, 'class' => 'success');
            }
        }
        else{
            $newLikeNumber = $likeNumber + 1;
            $comment->setCommentLike($newLikeNumber);
            $this->em->persist($comment);
            $this->em->flush();

            $setCookieArray = array("comment".$id => 1);
            $cookieJson = json_encode($setCookieArray);
            $cookie = new Cookie('intothemovieCookie', $cookieJson, strtotime("+1 year"));
            $response = new Response();
            $response->headers->setCookie($cookie);
            $response->send();

            $data = array('message' => 'Vous venez de like le commentaire de : '. $username, 'likeNumber' => $newLikeNumber, 'class' => 'success');
        }

        return $data;
    }

    public function addDislike($id)
    {
        $request = $this->requestStack->getCurrentRequest();

        $comment = $this->em->getRepository('AppBundle:Comment')->find($id);
        $dislikeNumber = $comment->getCommentDislike();
        $username = $comment->getUser()->getUsername();

        $getCookies = $request->cookies->get('intothemovieCookie');
        if(isset($getCookies)){
            $getCookiesArray = json_decode($getCookies, true);
            if(array_key_exists("comment".$id, $getCookiesArray)){
                $data = array('message' => 'Vous ne pouvez pas like ou dislike un même commentaire', 'likeNumber' => $dislikeNumber, 'class' => 'warning');
            }
            else{
                $newDislikeNumber = $dislikeNumber + 1;
                $comment->setCommentDislike($newDislikeNumber);
                $this->em->persist($comment);
                $this->em->flush();

                $getCookiesArray["comment".$id] = 1;
                $cookieJson = json_encode($getCookiesArray);
                $cookie = new Cookie('intothemovieCookie', $cookieJson, strtotime("+1 year"));
                $response = new Response();
                $response->headers->setCookie($cookie);
                $response->send();

                $data = array('message' => 'Vous venez de dislike le commentaire de : '. $username, 'dislikeNumber' => $newDislikeNumber, 'class' => 'success');
            }
        }
        else{
            $newDislikeNumber = $dislikeNumber + 1;
            $comment->setCommentDislike($newDislikeNumber);
            $this->em->persist($comment);
            $this->em->flush();

            $setCookieArray = array("comment".$id => 1);
            $cookieJson = json_encode($setCookieArray);
            $cookie = new Cookie('intothemovieCookie', $cookieJson, strtotime("+1 year"));
            $response = new Response();
            $response->headers->setCookie($cookie);
            $response->send();

            $data = array('message' => 'Vous venez de dislike le commentaire de : '. $username, 'dislikeNumber' => $newDislikeNumber, 'class' => 'success');
        }

        return $data;
    }


}
