<?php
//php app/console doctrine:generate:entities TomSiteBundle:Messenger
//php app/console doctrine:schema:create 
//php app/console doctrine:schema:update --force

namespace Tom\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity
 * @ORM\Table(name="messenger")
 * @ORM\HasLifecycleCallbacks
 */
class Messenger {

    const DEFAULT_IMAGE = 'default-thumbnail.jpeg';
    const UPLOAD_DIR = 'uploads/image-seo/';
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
    private $contents;

  /**
     * @ORM\ManyToOne(
     *      targetEntity = "Common\UserBundle\Entity\User"
     * )
     * 
     * @ORM\JoinColumn(
     *      name = "author_mess_id",
     *      referencedColumnName = "id"
     * )
     */
    private $author_mess;
    
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
     * Set contents
     *
     * @param string $contents
     *
     * @return Messenger
     */
    public function setContents($contents)
    {
        $this->contents = $contents;

        return $this;
    }

    /**
     * Get contents
     *
     * @return string
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     *
     * @return Messenger
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
     * Set authorMess
     *
     * @param \Common\UserBundle\Entity\User $authorMess
     *
     * @return Messenger
     */
    public function setAuthorMess(\Common\UserBundle\Entity\User $authorMess = null)
    {
        $this->author_mess = $authorMess;

        return $this;
    }

    /**
     * Get authorMess
     *
     * @return \Common\UserBundle\Entity\User
     */
    public function getAuthorMess()
    {
        return $this->author_mess;
    }
}
