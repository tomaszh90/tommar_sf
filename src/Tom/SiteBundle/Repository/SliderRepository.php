<?php

namespace Tom\SiteBundle\Repository;


class SliderRepository extends \Doctrine\ORM\EntityRepository
{
    
    public function getPublishedSlider($slug){
        $qb = $this->getQueryBuilder(array(
            'status' => 'published'
        ));
        
        $qb->andWhere('s.id = :id')
                ->setParameter('id', $id);
        
        return $qb->getQuery()->getOneOrNullResult();
    }


    public function getQueryBuilder(array $params = array()){
        
        $qb = $this->createQueryBuilder('s')
                        ->select('s, c')
                        ->leftJoin('s.category', 'c');
        
        if(!empty($params['status'])){
            if('published' == $params['status']){
                $qb->where('s.publishedDate <= :currDate AND s.publishedDate IS NOT NULL')
                        ->setParameter('currDate', new \DateTime());
            }else if('unpublished' == $params['status']){
                $qb->where('s.publishedDate > :currDate OR s.publishedDate IS NULL')
                        ->setParameter('currDate', new \DateTime());
            }
        }
        
        if(!empty($params['orderBy'])){
            $orderDir = !empty($params['orderDir']) ? $params['orderDir'] : NULL;
            $qb->orderBy($params['orderBy'], $orderDir);
        }
        
        if(!empty($params['categorySlug'])){
            $qb->andWhere('c.slug = :categorySlug')
                    ->setParameter('categorySlug', $params['categorySlug']);
        }
        
        if(!empty($params['categoryId'])){
            if(-1 == $params['categoryId']){
                $qb->andWhere($qb->expr()->isNull('s.category'));
            }else{
                $qb->andWhere('c.id = :categoryId')
                        ->setParameter('categoryId', $params['categoryId']);
            }
        }
        
        if(!empty($params['search'])){
            $searchParam = '%'.$params['search'].'%';
            $qb->andWhere('s.title LIKE :searchParam OR s.content LIKE :searchParam')
                    ->setParameter('searchParam', $searchParam);
        }
        
        if(!empty($params['titleLike'])){
            $titleLike = '%'.$params['titleLike'].'%';
            $qb->andWhere('s.title LIKE :titleLike')
                    ->setParameter('titleLike', $titleLike);
        }
                
        return $qb;
    }
    
    
    public function getStatistics(){
        $qb = $this->createQueryBuilder('s')
                        ->select('COUNT(s)');
        
        
        $all = (int)$qb->getQuery()->getSingleScalarResult();
        
        $published = (int)$qb->andWhere('s.publishedDate <= :currDate AND s.publishedDate IS NOT NULL')
                            ->setParameter('currDate', new \DateTime())
                            ->getQuery()
                            ->getSingleScalarResult();
        
        return array(
            'all' => $all,
            'published' => $published,
            'unpublished' => ($all - $published)
        );
    }
        
    public function moveToCategory($oldCategoryId, $newCategoryId) {
        return $this->createQueryBuilder('s')
                ->update()
                ->set('s.category', ':newCategoryId')
                ->where('s.category = :oldCategoryId')
                ->setParameters(array(
                    'newCategoryId' => $newCategoryId,
                    'oldCategoryId' => $oldCategoryId
                ))
                ->getQuery()
                ->execute();
    }
    
}
