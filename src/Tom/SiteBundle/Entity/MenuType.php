<?php

namespace Tom\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="Tom\SiteBundle\Repository\MenuTypeRepository")
 * @ORM\Table(name="menu_types")
 * 
 * @UniqueEntity(fields={"name"})
 */
class MenuType {
    
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
     * @ORM\OneToMany(
     *      targetEntity = "Menu",
     *      mappedBy = "type"
     * )
     */
    protected $menu;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->menu = new \Doctrine\Common\Collections\ArrayCollection();
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
     *
     * @return MenuType
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
     * Add menu
     *
     * @param \Tom\SiteBundle\Entity\Menu $menu
     *
     * @return MenuType
     */
    public function addMenu(\Tom\SiteBundle\Entity\Menu $menu)
    {
        $this->menu[] = $menu;

        return $this;
    }

    /**
     * Remove menu
     *
     * @param \Tom\SiteBundle\Entity\Menu $menu
     */
    public function removeMenu(\Tom\SiteBundle\Entity\Menu $menu)
    {
        $this->menu->removeElement($menu);
    }

    /**
     * Get menu
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMenu()
    {
        return $this->menu;
    }
}
