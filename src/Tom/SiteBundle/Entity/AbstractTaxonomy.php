<?php

namespace Tom\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields={"name"})
 * @UniqueEntity(fields={"slug"})
 */
abstract class AbstractTaxonomy {
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=120, unique=true)
     * @Assert\NotBlank
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=120, unique=true)
     */
    private $slug;
    
    protected $articles;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add articles
     *
     * @param \Tom\SiteBundle\Entity\Article $articles
     * @return ArticleCategory
     */
    public function addArticle(\Tom\SiteBundle\Entity\Article $articles)
    {
        $this->articles[] = $articles;

        return $this;
    }

    /**
     * Remove articles
     *
     * @param \Tom\SiteBundle\Entity\Article $articles
     */
    public function removeArticle(\Tom\SiteBundle\Entity\Article $articles)
    {
        $this->articles->removeElement($articles);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return AbstractTaxonomy
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return AbstractTaxonomy
     */
    public function setSlug($slug)
    {
        $this->slug = \Tom\SiteBundle\Libs\Utils::sluggify($slug);

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }
    
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function preSave(){
        if(null === $this->slug){
            $this->setSlug($this->getName());
        }
    }
}