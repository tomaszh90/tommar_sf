<?php

namespace Tom\SiteBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuBuilder
{
    private $factory;
    
    /**
     *
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private $doctrine;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory, \Doctrine\Bundle\DoctrineBundle\Registry $doctrine)
    {
        $this->factory = $factory;
        $this->doctrine = $doctrine;
    }

    public function createMainMenu(RequestStack $requestStack)
    {
//        $RepoMenu = $this->doctrine->getRepository('TomSiteBundle:Article');
        
        $menu = $this->factory->createItem('root');

        $menu->addChild('Home', array('route' => 'tom_site_homepage'));
        
//    $menu->addChild('About Me', array(
//        'route' => 'page_show',
//        'routeParameters' => array('id' => 42)
//    ));

        return $menu;
    }
}