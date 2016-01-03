<?php

namespace Tom\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * @ORM\Entity(repositoryClass="Tom\SiteBundle\Repository\ArticleRepository")
 * @ORM\Table(name="articles")
 * @ORM\HasLifecycleCallbacks
 * 
 * @UniqueEntity(fields={"title"})
 * @UniqueEntity(fields={"slug"})
 */
class Article {
    
    const DEFAULT_IMAGE = 'default-thumbnail.jpeg';
    const UPLOAD_DIR = 'uploads/article/';
    
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
     * @ORM\Column(type="string", length=120, unique=true)
     * 
     * @Assert\Length(
     *      max = 120
     * )
     */
    private $slug;
    
    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * 
     * @Assert\Length(
     *      max = 120
     * )
     */
    private $source = null;
    
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
     *      minHeight = 480,
     *      maxWidth = 1920,
     *      maxHeight = 1200,
     *      maxSize = "1M"
     * )
     */
    private $imageFile;
    
    private $imageTemp;
    
    /**
     * @ORM\ManyToOne(
     *      targetEntity = "ArticleCategory",
     *      inversedBy = "articles"
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
     * @ORM\ManyToMany(
     *      targetEntity = "Tag",
     *      inversedBy = "articles"
     * )
     * 
     * @ORM\JoinTable(
     *      name = "tom_articles_tags"
     * )
     * 
     * @Assert\Count(
     *      min=2
     * )
     */
    private $tags;
    
    /**
     * @ORM\ManyToOne(
     *      targetEntity = "Common\UserBundle\Entity\User"
     * )
     * 
     * @ORM\JoinColumn(
     *      name = "author_id",
     *      referencedColumnName = "id"
     * )
     */
    private $author;
    
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
     * @ORM\OneToMany(
     *      targetEntity = "Comment",
     *      mappedBy = "article"
     * )
     * 
     * @ORM\OrderBy({"createDate" = "DESC"})
     */
    private $comments;
    
    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $metaDescription = null;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $hits = 0;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Article
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
     * Set slug
     *
     * @param string $slug
     * @return Article
     */
    public function setSlug($slug)
    {
        $this->slug = \Tom\SiteBundle\Libs\Utils::sluggify($slug);

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Article
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
     * @return Article
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
            return Article::DEFAULT_IMAGE;
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
     * Set author
     *
     * @param \Common\UserBundle\Entity\User $author
     * @return Article
     */
    public function setAuthor(\Common\UserBundle\Entity\User  $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Common\UserBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return Article
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
     * @return Article
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
     * Set category
     *
     * @param \Tom\SiteBundle\Entity\ArticleCategory $category
     * @return Article
     */
    public function setCategory(\Tom\SiteBundle\Entity\ArticleCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Tom\SiteBundle\Entity\ArticleCategory 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add tags
     *
     * @param \Tom\SiteBundle\Entity\Tag $tags
     * @return Article
     */
    public function addTag(\Tom\SiteBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param \Tom\SiteBundle\Entity\Tag $tags
     */
    public function removeTag(\Tom\SiteBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Add comments
     *
     * @param \Tom\SiteBundle\Entity\Comment $comments
     * @return Article
     */
    public function addComment(\Tom\SiteBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Tom\SiteBundle\Entity\Comment $comments
     */
    public function removeComment(\Tom\SiteBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }
    
    
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function preSave(){
        if(null === $this->slug){
            $this->setSlug($this->getTitle());
        }
        
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
        \Tom\SiteBundle\Libs\Utils::imageResize($savePath, $imageName, 'th_', 100, 100);
        \Tom\SiteBundle\Libs\Utils::imageResize($savePath, $imageName, 'sm_', 768, 512);
        \Tom\SiteBundle\Libs\Utils::imageResize($savePath, $imageName, 'md_', 768, 768);
    }
    
    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     *
     * @return Article
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
     * Set metaDescription
     *
     * @param string $metaDescription
     *
     * @return Article
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set source
     *
     * @param string $source
     *
     * @return Article
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set hits
     *
     * @param integer $hits
     *
     * @return Article
     */
    public function setHits($hits)
    {
        $this->hits = $hits;

        return $this;
    }

    /**
     * Get hits
     *
     * @return integer
     */
    public function getHits()
    {
        return $this->hits;
    }
}
