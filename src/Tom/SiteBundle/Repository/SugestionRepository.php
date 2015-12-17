<?php

namespace Tom\SiteBundle\Repository;


class SugestionRepository extends \Doctrine\ORM\EntityRepository
{
    
    public function getPublishedSugestion($id){
        $qb = $this->getQueryBuilder(array(
            'status' => 'read'
        ));
        
        $qb->andWhere('s.id = :id')
                ->setParameter('id', $id);
        
        return $qb->getQuery()->getOneOrNullResult();
    }


    public function getQueryBuilder(array $params = array()){
        
        $qb = $this->createQueryBuilder('s')
                        ->select('s');
        
        if(!empty($params['status'])){
            if('read' == $params['status']){
                $qb->where('s.readDate IS NOT NULL AND s.notapprovedDate IS NULL AND s.approvedDate IS NULL');
            }
            else if('approved' == $params['status']){
                $qb->where('s.approvedDate IS NOT NULL AND s.readDate IS NULL AND s.notapprovedDate IS NULL');
            }
            else if('notapproved' == $params['status']){
                $qb->where('s.notapprovedDate IS NOT NULL AND s.readDate IS NULL AND s.approvedDate IS NULL');
            }
        }
        
        if(!empty($params['orderBy'])){
            $orderDir = !empty($params['orderDir']) ? $params['orderDir'] : NULL;
            $qb->orderBy($params['orderBy'], $orderDir);
        }

        
        if(!empty($params['search'])){
            $searchParam = '%'.$params['search'].'%';
            $qb->andWhere('s.nameSugestion LIKE :searchParam OR s.descriptionSugestion LIKE :searchParam')
                    ->setParameter('searchParam', $searchParam);
        }
        
        if(!empty($params['nameSugestionLike'])){
            $sugestionLike = '%'.$params['nameSugestionLike'].'%';
            $qb->andWhere('s.nameSugestion LIKE :nameSugestionLike')
                    ->setParameter('nameSugestionLike', $sugestionLike);
        }
                
        return $qb;
    }
    
    
    public function getStatistics(){
        $qb = $this->createQueryBuilder('s')
                        ->select('COUNT(s)');
        
        
        $all = (int)$qb->getQuery()->getSingleScalarResult();
        
        $read = (int)$qb->andWhere('s.readDate IS NOT NULL AND s.notapprovedDate IS NULL AND s.approvedDate IS NULL')
                            ->getQuery()
                            ->getSingleScalarResult();
        
        $qb_ap = $this->createQueryBuilder('s')
                        ->select('COUNT(s)');
        
        $approved = (int)$qb_ap->andWhere('s.approvedDate IS NOT NULL AND s.readDate IS NULL AND s.notapprovedDate IS NULL')
                            ->getQuery()
                            ->getSingleScalarResult();
        
        $qb_aps = $this->createQueryBuilder('s')
                        ->select('COUNT(s)');
        
        $notapproved = (int)$qb_aps->andWhere('s.notapprovedDate IS NOT NULL AND s.readDate IS NULL AND s.approvedDate IS NULL')
                            ->getQuery()
                            ->getSingleScalarResult();
        
        return array(
            'all' => $all,
            'read' => $read,
            'approved' => $approved,
            'notapproved' => $notapproved
        );
    }
    
}
