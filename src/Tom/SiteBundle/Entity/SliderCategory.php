<?php

namespace Tom\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="Tom\SiteBundle\Repository\SliderCategoryRepository")
 * @ORM\Table(name="slider_categories")
 * 
 * @UniqueEntity(fields={"name"})
 */
class SliderCategory {
    
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
     *      targetEntity = "Slider",
     *      mappedBy = "category"
     * )
     */
    protected $slider;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->slider = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return SliderCategory
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
     * Add slider
     *
     * @param \Tom\SiteBundle\Entity\Slider $slider
     *
     * @return SliderCategory
     */
    public function addSlider(\Tom\SiteBundle\Entity\Slider $slider)
    {
        $this->slider[] = $slider;

        return $this;
    }

    /**
     * Remove slider
     *
     * @param \Tom\SiteBundle\Entity\Slider $slider
     */
    public function removeSlider(\Tom\SiteBundle\Entity\Slider $slider)
    {
        $this->slider->removeElement($slider);
    }

    /**
     * Get slider
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSlider()
    {
        return $this->slider;
    }
}
