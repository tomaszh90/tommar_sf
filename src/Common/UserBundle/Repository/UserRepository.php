<?php

namespace Common\UserBundle\Repository;


class UserRepository extends \Doctrine\ORM\EntityRepository
{
    
    public function getQueryBuilder(array $params = array()) {
        $qb = $this->createQueryBuilder('u');
        
        if(!empty($params['usernameLike'])){
            $usernameLike = '%'.$params['usernameLike'].'%';
            $qb->andWhere('u.username LIKE :usernameLike')
                    ->setParameter('usernameLike', $usernameLike);
        }
        
        return $qb;
    }
    
}