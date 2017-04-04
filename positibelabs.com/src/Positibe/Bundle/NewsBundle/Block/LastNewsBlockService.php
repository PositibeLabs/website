<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\NewsBundle\Block;

use Positibe\Bundle\NewsBundle\Entity\PostRepository;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class LastNewsBlockService
 * @package Positibe\Bundle\NewsBundle\Block
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class LastNewsBlockService extends AbstractBlockService
{
    protected $template = 'PositibeNewsBundle:Block:block_last_news.html.twig';
    protected $postRepository;

    /**
     * @param string $name
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     * @param PostRepository $postRepository
     */
    public function __construct($name, $templating, PostRepository $postRepository)
    {
        parent::__construct($name, $templating);
        $this->postRepository = $postRepository;
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        if (!$response) {
            $response = new Response();
        }

        $contents = $this->postRepository->findLastNews(
          $blockContext->getSetting('count')
        );

        if (count($contents) > 0) {
            $response = $this->renderResponse(
              $blockContext->getTemplate(),
              array(
                'block' => $blockContext->getBlock(),
                'contents' => $contents
              ),
              $response
            );
        }

        $response->setTtl($blockContext->getSetting('ttl'));

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
          array(
            'template' => $this->template,
            'count' => 3
          )
        );
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    public function getCacheKeys(BlockInterface $block)
    {
        return array('type' => $block->getType());
    }
} 