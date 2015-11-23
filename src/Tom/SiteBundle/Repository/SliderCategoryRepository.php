<?php

namespace Tom\SiteBundle\Repository;


class SliderCategoryRepository extends \Doctrine\ORM\EntityRepository
{
    public function getQueryBuilder(array $params = array()) {
        $qb = $this->createQueryBuilder('c');
        
        $qb->select('c, COUNT(s.id) as slidersCount')
                ->leftJoin('c.slider', 's')
                ->groupBy('c.id');
        
        if(!empty($params['nameLike'])){
            $nameLike = '%'.$params['nameLike'].'%';
            $qb->andWhere('c.name LIKE :nameLike')
                    ->setParameter('nameLike', $nameLike);
        }
        
        return $qb;
    }
    
    public function getAsArray() {
        return $this->createQueryBuilder('c')
                        ->select('c.id, c.name')
                        ->getQuery()
                        ->getArrayResult();
    }
}
