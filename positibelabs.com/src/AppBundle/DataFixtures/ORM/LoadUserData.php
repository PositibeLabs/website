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

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadUserData
 * @package AppBundle\DataFixtures\ORM
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class LoadUserData implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
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
        $sadmin = new User();

        $sadmin->setUsername('sadmin');
        $sadmin->setEmail('sadmin@claim.cu');
        $sadmin->setPlainPassword('123');
        $sadmin->setEnabled(true);
        $sadmin->addRole('ROLE_SUPER_ADMIN');
        $manager->persist($sadmin);

        for ($i = 1; $i <= 10; $i++) {
            $user = new User();

            $user->setUsername('user'.$i);
            $user->setEmail('user'.$i.'@claim.cu');
            $user->setPlainPassword('123');
            $user->setEnabled(true);
            $manager->persist($user);
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
        return 0;
    }


}