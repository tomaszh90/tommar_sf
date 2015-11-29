<?php

namespace Tom\SiteBundle\Twig\Extension;


class SiteExtension extends \Twig_Extension {
    
    /**
     *
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private $doctrine;
    
    /**
     *
     * @var \Twig_Environment
     */
    private $environment;
    
    function __construct(\Doctrine\Bundle\DoctrineBundle\Registry $doctrine) {
        $this->doctrine = $doctrine;
    }

    public function initRuntime(\Twig_Environment $environment) {
        $this->environment = $environment;
    }


    public function getName() {
        return 'tom_site_extension';
    }
    
    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('articlesListModule', 
                        array($this, 'articlesListModule'), 
                        array('is_safe' => array('html'))
                    ),
        );
    }
    
    public function getFilters() {
        return array(
            'site_format_date' => new \Twig_Filter_Method($this, 'siteFormatDate')
        );
    }
    
//    private $categoryArticles;
    
    public function articlesListModule($categoryId, $limit) {
            $RepoArticle = $this->doctrine->getRepository('TomSiteBundle:Article');
            $Articles = $RepoArticle->getQueryBuilder(array(
                'categoryId' => $categoryId,
                'status'     => 'published',
                'orderBy'    => 'a.publishedDate',
                'orderDir'   => 'DESC',
                'limit'      => $limit
            ));

        
        return array(
            'articles' => $Articles
        );
    }


//    private $navigationArticle;
//
//    public function navigation() {
//        if(!isset($this->navigationArticle)) {
//            $RepoArticle = $this->doctrine->getRepository('TomSiteBundle:Article');
//            $this->navigationArticle = $RepoArticle->getStatistics();
//        }
//        
//        return $this->environment->render('TomAdminBundle:Template:navigation.html.twig', array());
//    }
//    

        
    public function shorten($text, $length = 200, $wrapTag = 'p') {
        
        $text = html_entity_decode($text);
        $text = strip_tags($text);
        if(strlen($text) > $length) {
            $text = substr($text, 0, $length).'...';
        }
        $openTag = "<{$wrapTag}>";
        $closeTag = "</{$wrapTag}>";
        
        return $openTag.$text.$closeTag;
    }
    
    
    public function siteFormatDate(\DateTime $datetime) {
        return $datetime->format('d/m/Y');
    }
    
}
