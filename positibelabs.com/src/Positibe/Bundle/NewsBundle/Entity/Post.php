<?php

namespace Positibe\Bundle\NewsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Positibe\Bundle\CmsBundle\Entity\BaseContent;
use Positibe\Bundle\MediaBundle\Entity\Media;
use Positibe\Bundle\UniqueViewsBundle\Model\VisitableInterface;
use Positibe\Bundle\UniqueViewsBundle\Model\VisitableTrait;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Post
 *
 * @ORM\Table(name="positibe_news")
 * @ORM\Entity
 *
 * @ORM\EntityListeners({"Positibe\Bundle\CmfRoutingExtraBundle\EventListener\RoutingAutoEntityListener"})
 */
class Post extends BaseContent implements VisitableInterface, ResourceInterface
{
    use VisitableTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Media
     *
     * @ORM\ManyToOne(targetEntity="Positibe\Bundle\MediaBundle\Entity\Media", cascade="all")
     */
    protected $image;

    /**
     * @var boolean
     *
     * @ORM\Column(name="featured", type="boolean")
     */
    protected $featured = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="comments_enabled", type="boolean")
     */
    protected $commentsEnabled = true;

    /**
     * @var AuthorInterface
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="AuthorInterface")
     */
    protected $author;

    /**
     * @var Comment[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="post", cascade={"remove"}, fetch="EXTRA_LAZY")
     */
    protected $comments;

    /**
     * @var ArrayCollection|RouteObjectInterface[]
     *
     * @ORM\ManyToMany(targetEntity="Positibe\Bundle\CmfRoutingExtraBundle\Entity\AutoRoute", orphanRemoval=TRUE, cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="positibe_news_routes",
     *      joinColumns={@ORM\JoinColumn(name="news_id", referencedColumnName="id", onDelete="cascade")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="route_id", referencedColumnName="id", unique=true, onDelete="cascade")}
     * )
     */
    protected $routes;

    /**
     * @var ArrayCollection|Tag[]
     *
     * @ORM\ManyToMany(targetEntity="Tag", cascade={"persist"}, fetch="EXTRA_LAZY", inversedBy="posts")
     * @ORM\JoinTable(name="positibe_news_tags")
     */
    protected $tags;

    /**
     * @var Collection[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Collection",cascade={"persist"}, inversedBy="posts")
     * @ORM\JoinTable(name="positibe_news_collections")
     */
    protected $collections;

    public function __construct()
    {
        parent::__construct();
        $this->tags = new ArrayCollection();
        $this->collections = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getCategoryName()
    {
        if ($collection = $this->collections->first()) {
            return $collection->getName();
        }

        return '';
    }

    public function getJoinedTags()
    {
        return join(', ', $this->tags->toArray());
    }

    public function getJoinedCollections()
    {
        return join(', ', $this->collections->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function getVisitableId()
    {
        return $this->getName();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return boolean
     */
    public function isFeatured()
    {
        return $this->featured;
    }

    /**
     * @param boolean $featured
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;
    }

    /**
     * @return Media
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param Media $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return Collection[]|ArrayCollection
     */
    public function getCollections()
    {
        return $this->collections;
    }

    public function addCollection($collection)
    {
        $this->collections[] = $collection;

        return $this;
    }

    public function removeCollection($collection)
    {
        $this->collections->removeElement($collection);

        return $this;
    }

    /**
     * @param Media $collections
     */
    public function setCollections($collections)
    {
        $this->collections = $collections;
    }

    /**
     * @return ArrayCollection|Tag[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param ArrayCollection|Tag[] $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @param $tag
     * @return $this
     */
    public function addTag($tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * @param $tag
     * @return $this
     */
    public function removeTag($tag)
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * @return AuthorInterface
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param AuthorInterface $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return ArrayCollection|Comment[]
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param ArrayCollection|Comment[] $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @param $comment
     * @return $this
     */
    public function addComment($comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * @param $comment
     * @return $this
     */
    public function removeComment($comment)
    {
        $this->comments->removeElement($comment);

        return $this;
    }

    /**
     * @return boolean
     */
    public function isCommentsEnabled()
    {
        return $this->commentsEnabled;
    }

    /**
     * @param boolean $commentsEnabled
     */
    public function setCommentsEnabled($commentsEnabled)
    {
        $this->commentsEnabled = $commentsEnabled;
    }

}
