<?php

namespace Tom\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * @ORM\Entity(repositoryClass="Tom\SiteBundle\Repository\SliderRepository")
 * @ORM\Table(name="slider")
 * @ORM\HasLifecycleCallbacks
 * 
 * @UniqueEntity(fields={"title"})
 */
class Slider {
    
    const DEFAULT_IMAGE = 'default-thumbnail.jpeg';
    const UPLOAD_DIR = 'uploads/slider/';
    
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
     * @ORM\Column(type="text")
     * 
     * @Assert\NotBlank
     */
    private $content;
    
    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    private $image = null;
    
    /**
     * @Assert\Image(
     *      minWidth = 600,
     *      minHeight = 200,
     *      maxSize = "1M"
     * )
     */
    private $imageFile;
    
    private $imageTemp;
    
    /**
     * @ORM\ManyToOne(
     *      targetEntity = "SliderCategory",
     *      inversedBy = "slider"
     * )
     * 
     * @ORM\JoinColumn(
     *      name = "category_id",
     *      referencedColumnName = "id",
     *      onDelete = "SET NULL"
     * )
     */
    private $category;
    
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
     * @ORM\Column(name="update_date", type="datetime", nullable=true)
     */
    private $updateDate = null;

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        if(null == $this->image){
            return Slider::DEFAULT_IMAGE;
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
        
        if(null === $this->createDate){
            $this->createDate = new \DateTime();
        }
    }
    
    /**
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
    public function postSave(){
        if(NULL !== $this->getImageFile()){
            $this->getImageFile()->move($this->getUploadRootDir(), $this->image);
            $this->imageResize($this->getUploadRootDir(), $this->image);
                        
            unset($this->imageFile);
            
            if(isset($this->imageTemp)){
                \Tom\SiteBundle\Libs\Utils::removeImage($this->getUploadRootDir(), $this->imageTemp);
                unset($this->imageTemp);
            }
        }
    }
    
    /**
     * @ORM\PostRemove
     */
    public function postRemove() {
        if(null !== $this->image){
            \Tom\SiteBundle\Libs\Utils::removeImage($this->getUploadRootDir(), $this->image);
        }
    }
    
    public function getUploadRootDir(){
        return __DIR__.'/../../../../web/'.self::UPLOAD_DIR;
    }
    
    protected function imageResize($savePath, $imageName = '') 
    {       
        \Tom\SiteBundle\Libs\Utils::imageResize($savePath, $imageName, 'th_', 150, 150);
        \Tom\SiteBundle\Libs\Utils::imageResize($savePath, $imageName, 'sm_', 768, 512);
        \Tom\SiteBundle\Libs\Utils::imageResize($savePath, $imageName, 'md_', 768, 768);
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
     * @return Slider
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
     * Set content
     *
     * @param string $content
     *
     * @return Slider
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Slider
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Set publishedDate
     *
     * @param \DateTime $publishedDate
     *
     * @return Slider
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
     * Set updateDate
     *
     * @param \DateTime $updateDate
     *
     * @return Slider
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
     * Set category
     *
     * @param \Tom\SiteBundle\Entity\SliderCategory $category
     *
     * @return Slider
     */
    public function setCategory(\Tom\SiteBundle\Entity\SliderCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Tom\SiteBundle\Entity\SliderCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return Slider
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
}
