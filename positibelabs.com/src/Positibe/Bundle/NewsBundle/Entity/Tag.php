<?php

namespace Positibe\Bundle\NewsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Positibe\Bundle\ClassificationBundle\Model\Tag as BaseTag;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Tag
 *
 * @ORM\Table(name="positibe_news_tag")
 * @ORM\Entity
 */
class Tag extends BaseTag implements TranslatableInterface, ResourceInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Post[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="tags")
     */
    protected $posts;

    /**
     * @Gedmo\Translatable
     */
    protected $name;

    /**
     * @var string
     *
     * @Gedmo\Locale
     */
    protected $locale;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return ArrayCollection|Post[]
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param ArrayCollection|Post[] $posts
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;
    }

    /**
     * @param $post
     * @return $this
     */
    public function addPost($post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * @param $post
     * @return $this
     */
    public function removePost($post)
    {
        $this->posts->removeElement($post);

        return $this;
    }
}
