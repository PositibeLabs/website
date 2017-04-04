<?php

namespace Positibe\Bundle\NewsBundle\Entity;

use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Positibe\Component\Publishable\Entity\StatePublishableTrait;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishableReadInterface;

/**
 * Class Comment
 * @package Positibe\Bundle\NewsBundle\Entity
 *
 * @ORM\Table(name="positibe_news_comment")
 * @ORM\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class Comment implements ResourceInterface, PublishableReadInterface
{
    use StatePublishableTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Name of the author
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * Email of the author
     *
     * @var string
     * @ORM\Column(name="email", type="string", length=255)
     */
    protected $email;

    /**
     * Website url of the author
     *
     * @var string
     * @ORM\Column(name="url", type="string", length=255, nullable=TRUE)
     */
    protected $url;

    /**
     * Comment content
     *
     * @var string
     * @ORM\Column(name="message", type="text")
     */
    protected $message;

    /**
     * Comment created date
     *
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="createdAt", type="datetime")
     */
    protected $createdAt;

    /**
     * Last update date
     *
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updatedAt", type="datetime", nullable=TRUE)
     */
    protected $updatedAt;

    /**
     * News for which the comment is related to
     *
     * @var Post
     *
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="comments")
     */
    protected $post;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    protected $user;
    /**
     * @var Comment
     *
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="children")
     */
    protected $parent;

    /**
     * @var Comment[]| ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="parent")
     */
    protected $children;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }



    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * {@inheritdoc}
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(\DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }


    public function preUpdate()
    {
        $this->setUpdatedAt(new \DateTime);
    }

    /**
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param Post $post
     */
    public function setPost($post)
    {
        $this->post = $post;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getName() ?: 'n-a';
    }

    /**
     * @return Comment
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Comment $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return Comment[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param Comment[] $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @param $child
     * @return $this
     */
    public function addChild($child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * @param $child
     * @return $this
     */
    public function removeChildren($child)
    {
        $this->children->removeElement($child);

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}
