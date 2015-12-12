<?php

namespace Tom\SiteBundle\Repository;


class MenuTypeRepository extends \Doctrine\ORM\EntityRepository
{
    public function getQueryBuilder(array $params = array()) {
        $qb = $this->createQueryBuilder('t');
        
        $qb->select('t, COUNT(m.id) as menuCount')
                ->leftJoin('t.menu', 'm')
                ->groupBy('t.id');
        
        if(!empty($params['nameLike'])){
            $nameLike = '%'.$params['nameLike'].'%';
            $qb->andWhere('t.name LIKE :nameLike')
                    ->setParameter('nameLike', $nameLike);
        }
        
        return $qb;
    }
    
    public function getAsArray() {
        return $this->createQueryBuilder('t')
                        ->select('t.id, t.name')
                        ->getQuery()
                        ->getArrayResult();
    }
}
