<?php
//php app/console doctrine:generate:entities TomSiteBundle:Seo
//php app/console doctrine:schema:create 
//php app/console doctrine:schema:update --force

namespace Tom\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="javascript")
 */
class Javascript {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
   /**
    * @ORM\Column(type="text")
    * @Assert\NotBlank()
    */
    private $script_head;
    
   /**
    * @ORM\Column(type="text")
    * @Assert\NotBlank()
    */
    private $script_bottom;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled = false;

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
     * Set scriptHead
     *
     * @param string $scriptHead
     *
     * @return Javascript
     */
    public function setScriptHead($scriptHead)
    {
        $this->script_head = $scriptHead;

        return $this;
    }

    /**
     * Get scriptHead
     *
     * @return string
     */
    public function getScriptHead()
    {
        return $this->script_head;
    }

    /**
     * Set scriptBottom
     *
     * @param string $scriptBottom
     *
     * @return Javascript
     */
    public function setScriptBottom($scriptBottom)
    {
        $this->script_bottom = $scriptBottom;

        return $this;
    }

    /**
     * Get scriptBottom
     *
     * @return string
     */
    public function getScriptBottom()
    {
        return $this->script_bottom;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Javascript
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
}
