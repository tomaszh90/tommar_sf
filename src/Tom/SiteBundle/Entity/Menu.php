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
}
