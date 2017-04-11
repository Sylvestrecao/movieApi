<?php

namespace AppBundle\Repository;

/**
 * MovieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MovieRepository extends \Doctrine\ORM\EntityRepository
{
    public function getUserFavoriteMovies($id)
    {
        return $this->createQueryBuilder('m')
            ->innerJoin('m.users', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findMovieInDatabase($id)
    {
        return $this->createQueryBuilder('m')
            ->where('m.movieDbId = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
            ;
    }
}
