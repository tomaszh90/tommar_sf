<?php

namespace Tom\SiteBundle\Repository;

use Doctrine\ORM\EntityRepository;


class ArticleRepository extends EntityRepository
{

    public function getPublishedArticle($slug){
        $qb = $this->getQueryBuilder(array(
            'status' => 'published'
        ));
        
        $qb->andWhere('a.slug = :slug')
                ->setParameter('slug', $slug);
        
        return $qb->getQuery()->getOneOrNullResult();
    }


    public function getQueryBuilder(array $params = array()){
        
        $qb = $this->createQueryBuilder('a')
                        ->select('a, c, t, au')
                        ->leftJoin('a.category', 'c')
                        ->leftJoin('a.tags', 't')
                        ->leftJoin('a.author', 'au');
        
        if(!empty($params['status'])){
            if('published' == $params['status']){
                $qb->where('a.publishedDate <= :currDate AND a.publishedDate IS NOT NULL')
                        ->setParameter('currDate', new \DateTime());
            }else if('unpublished' == $params['status']){
                $qb->where('a.publishedDate > :currDate OR a.publishedDate IS NULL')
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
                $qb->andWhere($qb->expr()->isNull('a.category'));
            }else{
                $qb->andWhere('c.id = :categoryId')
                        ->setParameter('categoryId', $params['categoryId']);
            }
        }
        
        if(!empty($params['tagSlug'])){
            $qb->andWhere('t.slug = :tagSlug')
                    ->setParameter('tagSlug', $params['tagSlug']);
        }
        
        if(!empty($params['search'])){
            $searchParam = '%'.$params['search'].'%';
            $qb->andWhere('a.title LIKE :searchParam OR a.content LIKE :searchParam')
                    ->setParameter('searchParam', $searchParam);
        }
        
        if(!empty($params['titleLike'])){
            $titleLike = '%'.$params['titleLike'].'%';
            $qb->andWhere('a.title LIKE :titleLike')
                    ->setParameter('titleLike', $titleLike);
        }
                
        return $qb;
    }
    
    
    public function getRecentCommented($limit = 3) {
        
        $qb = $this->createQueryBuilder('a')
                    ->select('a.title, a.slug, COUNT(c) as commentsCount')
                    ->leftJoin('a.comments', 'c')
                    ->groupBy('a.title')
                    ->having('commentsCount > 0')
                    ->where('a.publishedDate <= :currDate AND a.publishedDate IS NOT NULL')
                    ->setParameter('currDate', new \DateTime())
                    ->orderBy('commentsCount', 'DESC')
                    ->setMaxResults($limit);
        
        return $qb->getQuery()->getArrayResult();
    }
    
    public function getStatistics(){
        $qb = $this->createQueryBuilder('a')
                        ->select('COUNT(a)');
        
        
        $all = (int)$qb->getQuery()->getSingleScalarResult();
        
        $published = (int)$qb->andWhere('a.publishedDate <= :currDate AND a.publishedDate IS NOT NULL')
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
        return $this->createQueryBuilder('a')
                ->update()
                ->set('a.category', ':newCategoryId')
                ->where('a.category = :oldCategoryId')
                ->setParameters(array(
                    'newCategoryId' => $newCategoryId,
                    'oldCategoryId' => $oldCategoryId
                ))
                ->getQuery()
                ->execute();
    }
    
}