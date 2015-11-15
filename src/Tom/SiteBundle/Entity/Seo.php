<?php
//php app/console doctrine:generate:entities TomSiteBundle:Seo
//php app/console doctrine:schema:create 
//php app/console doctrine:schema:update --force

namespace Tom\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * @ORM\Entity
 * @ORM\Table(name="seo_site")
 * @ORM\HasLifecycleCallbacks
 */
class Seo {

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
    private $descriptions;

   /**
    * @ORM\Column(type="text")
    * @Assert\NotBlank()
    */
    private $keywords;
    
   /**
    * @ORM\Column(type="string", length=250)
    * @Assert\NotBlank()
    */
    private $h1_index;
    
   /**
    * @ORM\Column(type="text")
    * @Assert\NotBlank()
    */
    private $searchAction;
    
    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    private $image = null;
    
    /**
     * @Assert\Image(
     *      minWidth = 100,
     *      minHeight = 180,
     *      maxWidth = 1920,
     *      maxHeight = 1800,
     *      maxSize = "1M"
     * )
     */
    private $imageFile;
    
    private $imageTemp;
    
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
     * Set searchAction
     *
     * @param string $searchAction
     *
     * @return Seo
     */
    public function setSearchAction($searchAction)
    {
        $this->searchAction = $searchAction;

        return $this;
    }

    /**
     * Get searchAction
     *
     * @return string
     */
    public function getSearchAction()
    {
        return $this->searchAction;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Seo
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        if(null == $this->image){
            return Seo::DEFAULT_IMAGE;
        }
        
        return $this->image;
    }
    
    
    public function getImageFile() {
        return $this->imageFile;
    }

    public function setImageFile(UploadedFile $imageFile) {
        $this->imageFile = $imageFile;
        $this->updateDate = new \DateTime();
        return $this;
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
    
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function preSave(){
        
        if(null !== $this->getImageFile()){
            
            if(null !== $this->image){
                $this->imageTemp = $this->image;
            }
            
            $fileName = sha1(uniqid(null, true));
            $this->image = $fileName.'.'.$this->getImageFile()->guessExtension();
        }
    }
    
    /**
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
    public function postSave(){
        if(NULL !== $this->getImageFile()){
            $this->getImageFile()->move($this->getUploadRootDir(), $this->image);
                                   
            unset($this->imageFile);
            
            if(isset($this->imageTemp)){
                \Tom\SiteBundle\Libs\Utils::removeImage($this->getUploadRootDir(), $this->imageTemp);
                unset($this->imageTemp);
            }
        }
    }
    
    public function getUploadRootDir(){
      return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/image-seo/';
    }
    
}
