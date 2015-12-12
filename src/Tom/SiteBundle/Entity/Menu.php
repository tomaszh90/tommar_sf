<?php

namespace Tom\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="Tom\SiteBundle\Repository\MenuRepository")
 * @ORM\Table(name="menu")
 * @ORM\HasLifecycleCallbacks
 * 
 * @UniqueEntity(fields={"title"})
 */
class Menu {
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=120, unique=true)
     * 
     * @Assert\NotBlank
     * 
     * @Assert\Length(
     *      max = 120
     * )
     */
    private $title;
    
    /**
     * @ORM\OneToMany(targetEntity="Menu", mappedBy="parent")
     */
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="children")
     * @ORM\JoinColumn(name="parent", referencedColumnName="id")
     */
    private $parent;
    
    /**
     * @ORM\Column(type="string", length=120)
     * 
     * @Assert\Length(
     *      max = 120
     * )
     */
    private $route;

    /**
     * @ORM\Column(type="array")
     */
    private $routeParameters;
    
    /**
     * @ORM\ManyToOne(
     *      targetEntity = "MenuType",
     *      inversedBy = "menu"
     * )
     * 
     * @ORM\JoinColumn(
     *      name = "type_id",
     *      referencedColumnName = "id",
     *      onDelete = "SET NULL"
     * )
     */
    private $type;
    
    /**
     * @ORM\Column(name="create_date", type="datetime")
     */
    private $createDate;
    
    /**
     * @ORM\Column(name="published_date", type="datetime", nullable=true)
     * 
     * @Assert\DateTime
     */
    private $publishedDate = null;    
 

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return Menu
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime 
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Set publishedDate
     *
     * @param \DateTime $publishedDate
     * @return Menu
     */
    public function setPublishedDate($publishedDate)
    {
        $this->publishedDate = $publishedDate;

        return $this;
    }

    /**
     * Get publishedDate
     *
     * @return \DateTime 
     */
    public function getPublishedDate()
    {
        return $this->publishedDate;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function preSave(){
        
        if(null === $this->createDate){
            $this->createDate = new \DateTime();
        }
    }
    
    /**
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
    public function postSave(){
        
    }
    
    /**
     * @ORM\PostRemove
     */
    public function postRemove() {
    }
    
    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     *
     * @return Menu
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
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
     * Set title
     *
     * @param string $title
     *
     * @return Menu
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set route
     *
     * @param string $route
     *
     * @return Menu
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set routeParameters
     *
     * @param array $routeParameters
     *
     * @return Menu
     */
    public function setRouteParameters($routeParameters)
    {
        $this->routeParameters = $routeParameters;

        return $this;
    }

    /**
     * Get routeParameters
     *
     * @return array
     */
    public function getRouteParameters()
    {
        return $this->routeParameters;
    }

    /**
     * Set type
     *
     * @param \Tom\SiteBundle\Entity\MenuType $type
     *
     * @return Menu
     */
    public function setType(\Tom\SiteBundle\Entity\MenuType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Tom\SiteBundle\Entity\MenuType
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add child
     *
     * @param \Tom\SiteBundle\Entity\Menu $child
     *
     * @return Menu
     */
    public function addChild(\Tom\SiteBundle\Entity\Menu $child)
    {
        $this->children[] = $child;
        $child->setParent($this);
        
        return $this;
    }

    /**
     * Remove child
     *
     * @param \Tom\SiteBundle\Entity\Menu $child
     */
    public function removeChild(\Tom\SiteBundle\Entity\Menu $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \Tom\SiteBundle\Entity\Menu $parent
     *
     * @return Menu
     */
    public function setParent(\Tom\SiteBundle\Entity\Menu $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Tom\SiteBundle\Entity\Menu
     */
    public function getParent()
    {
        return $this->parent;
    }
}
