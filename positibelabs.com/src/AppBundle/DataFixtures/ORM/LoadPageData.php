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
use Ferrandini\Urlizer;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Cmf\Component\Routing\RouteReferrersInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class LoadPageData
 * @package AppBundle\DataFixtures\ORM
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class LoadPageData implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /** @var RouteReferrersInterface[] $createdPages */
        $createdPages = [];
        $this->manager = $manager;

        $menuBuilder = $this->container->get('positibe.factory.menu');
        $contentRepository = $this->container->get('cmf_routing.content_repository');

        $footerMenu = $menuBuilder->createMenu('nosotros', ['class' => 'footer-menu']);
//        $archivosMenu = $menuBuilder->createMenu('archivos', ['class' => 'footer-menu']);
//        $comunidadMenu = $menuBuilder->createMenu('comunidad', ['class' => 'footer-menu']);
//        $otrosMenu = $menuBuilder->createMenu('otros', ['class' => 'footer-menu']);

        $manager->persist($footerMenu);
//        $manager->persist($archivosMenu);
//        $manager->persist($comunidadMenu);
//        $manager->persist($otrosMenu);
        $manager->flush();

        $mainMenu = $menuBuilder->createMenu('main', ['class' => 'nav navbar-nav']);
        $manager->persist($mainMenu);

        $homepage = $this->container->get('positibe.factory.page')->createPage(
            'Inicio',
            null,
            '/',
            $mainMenu,
            'es'
        );
        $createdPages[] = $homepage;

        $contacto = $this->container->get('positibe.factory.page')->createPage(
            'Sobre nosotros',
            null,
            '/contacto',
            $mainMenu,
            'es'
        );

        $createdPages[] = $contacto;

        $manager->persist($homepage);
        $manager->persist($contacto);

        $noticias = $this->container->get('positibe.factory.page')->createPageCategory(
            'Noticias',
            null,
            '/'.Urlizer::urlize('Noticias'),
            null,
            'es'
        );
        $createdPages[] = $noticias;
        $manager->persist($noticias);
        $servicios = $this->container->get('positibe.factory.page')->createPageCategory(
            'Servicios',
            null,
            '/'.Urlizer::urlize('Servicios'),
            null,
            'es'
        );
        $createdPages[] = $servicios;

        $manager->persist($servicios);

        $manager->flush();

        foreach ($createdPages as $page) {
            foreach ($page->getRoutes() as $route) {
                $route->setDefault(RouteObjectInterface::CONTENT_ID, $contentRepository->getContentId($page));
                $manager->persist($route);
            }
        }

        $manager->flush();


    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }

}