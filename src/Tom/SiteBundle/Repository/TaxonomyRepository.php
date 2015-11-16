<?php

namespace Tom\SiteBundle\Repository;

use Doctrine\ORM\EntityRepository;


class TaxonomyRepository extends EntityRepository {
    
    public function getQueryBuilder(array $params = array()) {
        $qb = $this->createQueryBuilder('t');
        
        $qb->select('t, COUNT(a.id) as articlesCount')
                ->leftJoin('t.articles', 'a')
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