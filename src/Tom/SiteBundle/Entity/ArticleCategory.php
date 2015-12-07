<?php

namespace Tom\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Tom\SiteBundle\Repository\ArticleCategoryRepository")
 * @ORM\Table(name="article_categories")
 * @ORM\HasLifecycleCallbacks
 */
class ArticleCategory extends AbstractTaxonomy {
    
    const DEFAULT_IMAGE = 'default.jpeg';
    const UPLOAD_DIR = 'uploads/article/category/';
    
    /**
     * @ORM\OneToMany(
     *      targetEntity = "Article",
     *      mappedBy = "category"
     * )
     */
    protected $articles;
    
    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    private $image = null;
    
    /**
     * @Assert\Image(
     *      maxWidth = 1920,
     *      maxHeight = 1200,
     *      maxSize = "1M"
     * )
     */
    private $imageFile;
    
    private $imageTemp;
    
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
            return ArticleCategory::DEFAULT_IMAGE;
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
    public function preSaveCat(){
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
    public function postSaveCat(){
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
    public function postRemoveCat() {
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
     * Set image
     *
     * @param string $image
     *
     * @return ArticleCategory
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     *
     * @return ArticleCategory
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
