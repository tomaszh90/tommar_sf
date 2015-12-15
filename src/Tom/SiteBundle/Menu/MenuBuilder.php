<?php

namespace Tom\SiteBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuBuilder {

    private $factory;

    /**
     *
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private $doctrine;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory, \Doctrine\Bundle\DoctrineBundle\Registry $doctrine) {
        $this->factory = $factory;
        $this->doctrine = $doctrine;
    }

    public function createMainMenu(RequestStack $requestStack) {

        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');
        $MenuPos = $this->getPositionMenu(2);

        foreach ($MenuPos as $item) {
            $level = $menu->addChild($item->getTitle(), array(
                'route' => $item->getRoute(),
                'routeParameters' => $item->getRouteParameters()
            ))->setAttribute('class', 'yamm-fw');

            $this->builSubMenu($level, $item);
        }

        return $menu;
    }

    protected function builSubMenu($menu, $item) {

        if (count($item->getChildren()) > 0) {
            $menu->setAttribute('dropdown', true);
            foreach ($item->getChildren() as $subItem) {

                $level = $menu->addChild($subItem->getTitle(), array(
                    'route' => $subItem->getRoute(),
                    'routeParameters' => $subItem->getRouteParameters()
                ));

                $this->builSubMenu($level, $subItem);
            }
        }
    }

    protected function getPositionMenu($typeId, $parentId = NULL) {
        $RepoMenu = $this->doctrine->getRepository('TomSiteBundle:Menu');

        $params = array(
            'parentId' => $parentId,
            'typeId' => $typeId,
            'status' => 'published'
        );

        $menu = $RepoMenu->getMenuBuilder($params);

        return $menu;
    }

}
