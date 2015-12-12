<?php
//php app/console doctrine:generate:entities TomSiteBundle:Sugestion
//php app/console doctrine:schema:create 
//php app/console doctrine:schema:update --force

namespace Tom\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="Tom\SiteBundle\Repository\SugestionRepository")
 * @ORM\Table(name="sugestion")
 * @ORM\HasLifecycleCallbacks
 */
class Sugestion {

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
    private $name_sugestion;

    /**
    * @ORM\Column(type="text")
    * @Assert\NotBlank()
    */
    private $email_sugestion;
    
   /**
    * @ORM\Column(type="text")
    * @Assert\NotBlank()
    */
    private $description_sugestion;
    
   /**
    * @ORM\Column(type="text")
    * @Assert\NotBlank()
    */
    private $authorization;
    
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
     * Set nameSugestion
     *
     * @param string $nameSugestion
     *
     * @return Sugestion
     */
    public function setNameSugestion($nameSugestion)
    {
        $this->name_sugestion = $nameSugestion;

        return $this;
    }

    /**
     * Get nameSugestion
     *
     * @return string
     */
    public function getNameSugestion()
    {
        return $this->name_sugestion;
    }

    /**
     * Set emailSugestion
     *
     * @param string $emailSugestion
     *
     * @return Sugestion
     */
    public function setEmailSugestion($emailSugestion)
    {
        $this->email_sugestion = $emailSugestion;

        return $this;
    }

    /**
     * Get emailSugestion
     *
     * @return string
     */
    public function getEmailSugestion()
    {
        return $this->email_sugestion;
    }

    /**
     * Set descriptionSugestion
     *
     * @param string $descriptionSugestion
     *
     * @return Sugestion
     */
    public function setDescriptionSugestion($descriptionSugestion)
    {
        $this->description_sugestion = $descriptionSugestion;

        return $this;
    }

    /**
     * Get descriptionSugestion
     *
     * @return string
     */
    public function getDescriptionSugestion()
    {
        return $this->description_sugestion;
    }

    /**
     * Set authorization
     *
     * @param string $authorization
     *
     * @return Sugestion
     */
    public function setAuthorization($authorization)
    {
        $this->authorization = $authorization;

        return $this;
    }

    /**
     * Get authorization
     *
     * @return string
     */
    public function getAuthorization()
    {
        return $this->authorization;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     *
     * @return Sugestion
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
