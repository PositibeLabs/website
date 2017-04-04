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

use Positibe\Bundle\NewsBundle\Form\Type\CommentFormType;
use Positibe\Bundle\UniqueViewsBundle\Model\VisitableInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class NewsController
 * @package Positibe\Bundle\NewsBundle\Controller
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class NewsController extends Controller
{
    /**
     * Render the provided content.
     *
     * When using the publish workflow, enable the publish_workflow.request_listener
     * of the core bundle to have the contentDocument as well as the route
     * checked for being published.
     * We don't need an explicit check in this method.
     *
     * @param Request $request
     * @param object $contentDocument
     * @param string $template Symfony path of the template to render
     *                                 the content document. If omitted, the
     *                                 default template is used.
     *
     * @return Response
     */
    public function indexAction(Request $request, $contentDocument, $template = null)
    {
        $contentTemplate = $template ?: 'post/index.html.twig';

        $contentTemplate = str_replace(
          array('{_format}', '{_locale}'),
          array($request->getRequestFormat(), $request->getLocale()),
          $contentTemplate
        );

        if ($contentDocument instanceof VisitableInterface) {
            $this->get('positibe_unique_views.views_counter')->count($contentDocument);
        }

        $formComment = $this->createForm(CommentFormType::class, null, array(
              'action' => $this->generateUrl('post_comment_create', array('postName' => $contentDocument->getName())),
          ));

        $params = array('post' => $contentDocument, 'formComment' => $formComment->createView());


        return $this->render($contentTemplate, $params);
    }

    /**
     * @param Request $request
     * @param $contentDocument
     * @param null $template
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request, $contentDocument, $template = null)
    {
        $contentTemplate = $template ?: 'post/list.html.twig';

        $contentTemplate = str_replace(
          array('{_format}', '{_locale}'),
          array($request->getRequestFormat(), $request->getLocale()),
          $contentTemplate
        );

        $posts = $this->get('positibe.repository.post')->createPaginator(array(), array('publishStartDate' => 'DESC'));

        $params = array('content' => $contentDocument, 'posts' => $posts);


        return $this->render($contentTemplate, $params);
    }
} 