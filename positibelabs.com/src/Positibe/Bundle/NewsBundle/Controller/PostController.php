<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\NewsBundle\Controller;

use Positibe\Bundle\NewsBundle\Entity\Post;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;
use Symfony\Cmf\Bundle\SeoBundle\SeoAwareInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Workflow\Exception\ExceptionInterface;


/**
 * Class PostController
 * @package Positibe\Bundle\NewsBundle\Controller
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class PostController extends ResourceController
{
    /**
     * Load the correct locale for seo and menus depend of data_locale http parameter
     *
     * @param Request $request
     * @param array $criteria
     * @return object|void
     */
    /**
     * @param RequestConfiguration $configuration
     * @return Post|\Sylius\Component\Resource\Model\ResourceInterface
     */
    public function findOr404(RequestConfiguration $configuration)
    {
        /** @var Post $post */
        $post = parent::findOr404($configuration);

        if ($post instanceof TranslatableInterface && $dataLocale = $configuration->getRequest()->get(
                'data_locale',
                $this->getParameter('locale')
            )
        ) {
            $post->setLocale($dataLocale);

            if ($post instanceof SeoAwareInterface && $seoMetadata = $post->getSeoMetadata()) {
                $seoMetadata->setLocale($dataLocale);
                $this->get('doctrine.orm.entity_manager')->refresh($seoMetadata);
            }

            foreach ($post->getCollections() as $collection) {
                $collection->setLocale($dataLocale);
                $this->get('doctrine.orm.entity_manager')->refresh($collection);
            }

            $this->get('doctrine.orm.entity_manager')->refresh($post);
        }
        if ($transition = $configuration->getRequest()->request->get('transition')) {
            try {
                $this->get('workflow.registry')->get($post)
                    ->apply($post, $transition);
            } catch (ExceptionInterface $e) {
            }
        }

        return $post;
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function applyTransitionAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        try {
            $resource = $this->findOr404($configuration);

            $this->get('doctrine')->getManager()->flush();
        } catch (ExceptionInterface $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
            $resource = null;
        }

        return $this->redirectHandler->redirectToResource($configuration, $resource);
    }

    public function iframeAction(Post $post)
    {
        return $this->render('@PositibeCms/Page/_iframe.html.twig', ['content' => $post]);
    }
} 