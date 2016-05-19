<?php

namespace Discutea\DTutoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Discutea\DTutoBundle\Entity\Tutorial;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * 
 * @ORM\Entity(repositoryClass="Discutea\DTutoBundle\Repository\ContributionRepository")
 * @ORM\Table(name="dtuto_contribution")
 * 
 */
class Contribution
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="content", type="text", nullable=false)
     */
    protected $content;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $date;

    /**
     * @ORM\ManyToOne(targetEntity="Discutea\DTutoBundle\Entity\Tutorial", inversedBy="contributions")
     * @ORM\JoinColumn(name="tutorial_id", referencedColumnName="id", nullable=false)
     */
    protected $tutorial;

    /**
     * @ORM\Column(name="current_version", type="boolean", options={"default":false})
     */
    protected $current;

    /**
     * @ORM\ManyToOne(targetEntity="Symfony\Component\Security\Core\User\UserInterface")
     * @ORM\JoinColumn(nullable=true, referencedColumnName="id")
     */
    protected $author;

    /**
     *
     * 1 = Validate
     * 2 = NoValidate
     * 3 = InProgress
     * 
     * @ORM\Column(type="smallint", nullable=false, options={"default" = 2})
     * 
     */
    protected $status = 2;

    public function __construct() {
        $this->setDate(new \DateTime());
    }


    /**
     * Set id
     *
     * @param int $id
     *
     * @return Post
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * Set content
     *
     * @param string $content
     *
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Post
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Post
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set tutorial
     *
     * @param \Discutea\DTutoBundle\Entity\Tutorial $tutorial
     *
     * @return Contribution
     */
    public function setTutorial(Tutorial $tutorial)
    {
        $this->tutorial = $tutorial;

        return $this;
    }

    /**
     * Get tutorial
     *
     * @return \Discutea\DTutoBundle\Entity\Tutorial
     */
    public function getTutorial()
    {
        return $this->tutorial;
    }

    /**
     * Set authos
     *
     * @param \Discutea\UsersBundle\Entity\Users $author
     *
     * @return Contribution
     */
    public function setAuthor(UserInterface $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return Symfony\Component\Security\Core\User\UserInterface
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set current
     *
     * @param string $current
     *
     * @return this
     */
    public function setCurrent($current)
    {
        $this->current = $current;

        return $this;
    }

    /**
     * Get current
     *
     * @return boolean
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * Get Status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status
     * 
     * 1 = Validate
     * 2 = NoValidate
     * 3 = InProgress
     *
     * @return this
     */
    public function setStatus($status)
    {
        if ( ( is_int($status) === false ) || ($status < 1) || ($status > 4) ) {
            throw new \LogicException('The logic of the status property isn\'t respected!');
        }
        
        $this->status = $status;

        return $this;
    }
}
