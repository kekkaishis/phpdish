<?php
/**
 * PHPDish comment component
 * @author Tao <taosikai@yeah.net>
 */
namespace  PHPDish\Bundle\PostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PHPDish\Bundle\CoreBundle\Model\Comment as BaseComment;
use PHPDish\Bundle\CoreBundle\Model\VotableTrait;
use PHPDish\Bundle\PostBundle\Model\PostCommentInterface;
use PHPDish\Bundle\PostBundle\Model\PostInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="post_comments")
 */
class PostComment extends BaseComment implements PostCommentInterface
{
    use VotableTrait;
    
    /**
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="comments")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    protected $post;

    /**
     * {@inheritdoc}
     */
    public function setPost(PostInterface $post)
    {
        $this->post = $post;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPost()
    {
        return $this->post;
    }
}