<?php
//php app/console doctrine:generate:entities TomSiteBundle:Seo
//php app/console doctrine:schema:create 
//php app/console doctrine:schema:update --force
namespace Tom\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="seo_site")
 */
class Seo {

    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
   /**
    * @ORM\Column(type="text")
    */
    private $descriptions;

   /**
    * @ORM\Column(type="text")
    */
    private $keywords;
    
   /**
    * @ORM\Column(type="text")
    */
    private $h1_index;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled = false;
    
    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    private $updateDate;


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
     * Set descriptions
     *
     * @param string $descriptions
     *
     * @return Seo
     */
    public function setDescriptions($descriptions)
    {
        $this->descriptions = $descriptions;

        return $this;
    }

    /**
     * Get descriptions
     *
     * @return string
     */
    public function getDescriptions()
    {
        return $this->descriptions;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     *
     * @return Seo
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * Get keywords
     *
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set h1Index
     *
     * @param string $h1Index
     *
     * @return Seo
     */
    public function setH1Index($h1Index)
    {
        $this->h1_index = $h1Index;

        return $this;
    }

    /**
     * Get h1Index
     *
     * @return string
     */
    public function getH1Index()
    {
        return $this->h1_index;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Seo
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     *
     * @return Seo
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
}
