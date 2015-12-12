<?php

namespace Tom\SiteBundle\Repository;


class MenuRepository extends \Doctrine\ORM\EntityRepository
{
    
    public function getPublishedMenu($id){
        $qb = $this->getQueryBuilder(array(
            'status' => 'published'
        ));
        
        $qb->andWhere('m.id = :id')
                ->setParameter('id', $id);
        
        return $qb->getQuery()->getOneOrNullResult();
    }


    public function getQueryBuilder(array $params = array()){
        
        $qb = $this->createQueryBuilder('m')
                        ->select('m, t')
                        ->leftJoin('m.type', 't');
        
        if(!empty($params['status'])){
            if('published' == $params['status']){
                $qb->where('m.publishedDate <= :currDate AND m.publishedDate IS NOT NULL')
                        ->setParameter('currDate', new \DateTime());
            }else if('unpublished' == $params['status']){
                $qb->where('m.publishedDate > :currDate OR m.publishedDate IS NULL')
                        ->setParameter('currDate', new \DateTime());
            }
        }
        
        if(!empty($params['orderBy'])){
            $orderDir = !empty($params['orderDir']) ? $params['orderDir'] : NULL;
            $qb->orderBy($params['orderBy'], $orderDir);
        }
        
        if(!empty($params['typeSlug'])){
            $qb->andWhere('t.slug = :typeSlug')
                    ->setParameter('typeSlug', $params['typeSlug']);
        }
        
        if(!empty($params['typeId'])){
            if(-1 == $params['typeId']){
                $qb->andWhere($qb->expr()->isNull('m.type'));
            }else{
                $qb->andWhere('t.id = :typeId')
                        ->setParameter('typeId', $params['typeId']);
            }
        }
        
        if(!empty($params['search'])){
            $searchParam = '%'.$params['search'].'%';
            $qb->andWhere('m.title LIKE :searchParam')
                    ->setParameter('searchParam', $searchParam);
        }
        
        if(!empty($params['titleLike'])){
            $titleLike = '%'.$params['titleLike'].'%';
            $qb->andWhere('m.title LIKE :titleLike')
                    ->setParameter('titleLike', $titleLike);
        }
                
        return $qb;
    }
    
    
    public function getStatistics(){
        $qb = $this->createQueryBuilder('m')
                        ->select('COUNT(m)');
        
        
        $all = (int)$qb->getQuery()->getSingleScalarResult();
        
        $published = (int)$qb->andWhere('m.publishedDate <= :currDate AND m.publishedDate IS NOT NULL')
                            ->setParameter('currDate', new \DateTime())
                            ->getQuery()
                            ->getSingleScalarResult();
        
        return array(
            'all' => $all,
            'published' => $published,
            'unpublished' => ($all - $published)
        );
    }
        
    public function moveToType($oldTypeId, $newTypeId) {
        return $this->createQueryBuilder('s')
                ->update()
                ->set('m.type', ':newTypeId')
                ->where('m.type = :oldTypeId')
                ->setParameters(array(
                    'newTypeId' => $newTypeId,
                    'oldTypeId' => $oldTypeId
                ))
                ->getQuery()
                ->execute();
    }
    
}
