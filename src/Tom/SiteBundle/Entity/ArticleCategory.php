<?php

namespace Tom\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="Tom\SiteBundle\Repository\ArticleCategoryRepository")
 * @ORM\Table(name="article_categories")
 */
class ArticleCategory extends AbstractTaxonomy {
    
    /**
     * @ORM\OneToMany(
     *      targetEntity = "Article",
     *      mappedBy = "category"
     * )
     */
    protected $articles;
    
    
}
