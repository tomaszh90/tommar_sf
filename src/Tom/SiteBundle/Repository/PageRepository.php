<?php

namespace Tom\SiteBundle\Repository;

use Doctrine\ORM\EntityRepository;


class PageRepository extends EntityRepository
{
    public function getPublishedArticle($slug){
        $qb = $this->getQueryBuilder(array(
            'status' => 'published'
        ));
        
        $qb->andWhere('p.slug = :slug')
                ->setParameter('slug', $slug);
        
        return $qb->getQuery()->getOneOrNullResult();
    }


    public function getQueryBuilder(array $params = array()){
        
        $qb = $this->createQueryBuilder('p')
                        ->select('p');
        
        if(!empty($params['status'])){
            if('published' == $params['status']){
                $qb->where('p.publishedDate <= :currDate AND p.publishedDate IS NOT NULL')
                        ->setParameter('currDate', new \DateTime());
            }else if('unpublished' == $params['status']){
                $qb->where('p.publishedDate > :currDate OR p.publishedDate IS NULL')
                        ->setParameter('currDate', new \DateTime());
            }
        }
        
        if(!empty($params['orderBy'])){
            $orderDir = !empty($params['orderDir']) ? $params['orderDir'] : NULL;
            $qb->orderBy($params['orderBy'], $orderDir);
        }

        if(!empty($params['search'])){
            $searchParam = '%'.$params['search'].'%';
            $qb->andWhere('p.title LIKE :searchParam OR p.content LIKE :searchParam')
                    ->setParameter('searchParam', $searchParam);
        }
        
        if(!empty($params['titleLike'])){
            $titleLike = '%'.$params['titleLike'].'%';
            $qb->andWhere('p.title LIKE :titleLike')
                    ->setParameter('titleLike', $titleLike);
        }
                
        return $qb;
    }
    
    
    public function getStatistics(){
        $qb = $this->createQueryBuilder('p')
                        ->select('COUNT(p)');
        
        
        $all = (int)$qb->getQuery()->getSingleScalarResult();
        
        $published = (int)$qb->andWhere('p.publishedDate <= :currDate AND p.publishedDate IS NOT NULL')
                            ->setParameter('currDate', new \DateTime())
                            ->getQuery()
                            ->getSingleScalarResult();
        
        return array(
            'all' => $all,
            'published' => $published,
            'unpublished' => ($all - $published)
        );
    }
}
