<?php

namespace Tom\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="Tom\SiteBundle\Repository\TagRepository")
 * @ORM\Table(name="article_tags")
 */
class Tag extends AbstractTaxonomy {
    
    /**
     * @ORM\ManyToMany(
     *      targetEntity = "Article",
     *      mappedBy = "tags"
     * )
     */
    protected $articles;
    
}
