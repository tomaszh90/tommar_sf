<?php

namespace Tom\SiteBundle\Repository;

class TagRepository extends TaxonomyRepository
{
    public function getTagsListOcc(){
        $qb = $this->createQueryBuilder('t')
                        ->select('t.slug, t.name, COUNT(p) as occ')
                        ->leftJoin('t.articles', 'a')
                        ->groupBy('t.name');
        
        return $qb->getQuery()->getArrayResult();
    }
    
}
