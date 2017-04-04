<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\NewsBundle\Factory;

use Doctrine\ORM\EntityManager;
use Positibe\Bundle\NewsBundle\Entity\Comment;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * Class CommentFactory
 * @package Positibe\Bundle\NewsBundle\Factory
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class CommentFactory implements FactoryInterface
{
    protected $entityManager;
    protected $defaultState;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return Comment
     */
    public function createNew()
    {
        return new Comment();
    }

    public function createByPostName($postName)
    {
        if (!$post = $this->entityManager->getRepository('PositibeNewsBundle:Post')->findOneBy(['name' => $postName])) {
            throw new NotFoundHttpException('No se encontró la entrada con nombre '.$postName);
        }

        if (!$post->isCommentsEnabled()) {
            throw new AccessDeniedHttpException('La entrada no es comentable');
        }

        $comment = $this->createNew();

        $comment->setPost($post);
        $comment->setState($this->defaultState);

        return $comment;

    }

    /**
     * @param mixed $defaultState
     */
    public function setDefaultState($defaultState)
    {
        $this->defaultState = $defaultState;
    }

} 