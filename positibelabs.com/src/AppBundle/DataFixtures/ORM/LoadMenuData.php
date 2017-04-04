<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Positibe\Bundle\MenuBundle\Doctrine\Orm\MenuNode;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadMenuData
 * @package AppBundle\DataFixtures\ORM
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class LoadMenuData implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    /** @var  ContainerInterface */
    protected $container;

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $menuClass = $this->container->getParameter('positibe.menu_node.class');
        // Creating the root menu that is a container for submenus
        /** @var \Positibe\Bundle\MenuBundle\Doctrine\Orm\MenuNode $menu */
        $menu = new $menuClass('footer');
        $menu->setChildrenAttributes(['class' => 'nav navbar-nav']); //You can set the ul attributes here

        $manager->persist($menu);

        //Creating an URI menu, that link to a external or internal full url.
        /** @var \Positibe\Bundle\MenuBundle\Doctrine\Orm\MenuNode $menuExternalUrl */
        $menuExternalUrl = new $menuClass('Github');
        $menuExternalUrl->setLinkUri('https://github.com/Positibe/MenuBundle');
        $menu->addChild($menuExternalUrl);

        $manager->persist($menuExternalUrl); //The menu is configured with cascade persist, so you don't need to do this

        // Creating a route menu, that link to a route in the routing configuration of your application
        /** @var \Positibe\Bundle\MenuBundle\Doctrine\Orm\MenuNode $menuHomePage */
        $menuHomePage = new $menuClass();
        $menuHomePage->setName('homepage'); //You can define a code name to have better control of the menus
        $menuHomePage->setLabel('Inicio'); //And you can define a proper label to show in the views
        $menuHomePage->setLinkRoute('homepage');
        $menu->addChild($menuHomePage);

        $manager->persist($menuHomePage); //The menu is configured with cascade persist, so you don't need to do this

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 0;
    }


}