<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Positibe\Bundle\NewsBundle\Entity\AuthorInterface;
use Positibe\Bundle\UserBundle\Entity\Traits\UserTrait;
use Positibe\Bundle\UserBundle\Entity\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="app_user")
 */
class User extends BaseUser implements UserInterface, AuthorInterface /*, UserContactInterface,*/
{
    use UserTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
} 