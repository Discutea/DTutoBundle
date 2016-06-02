<?php

namespace Discutea\DTutoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Discutea\DTutoBundle\Entity\Tutorial;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * 
 * @ORM\Entity(repositoryClass="Discutea\DTutoBundle\Repository\ContributionRepository")
 * @ORM\Table(name="dtuto_contribution")
 * 
 * 
 * @package  DTutoBundle
 * @author   David Verdier <contact@discutea.com>
 * https://www.linkedin.com/in/verdierdavid
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
     * @ORM\Column(length=20, unique=false, nullable=true)
     */
    private $version;

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
    protected $current = false;

    /**
     * @ORM\ManyToOne(targetEntity="Symfony\Component\Security\Core\User\UserInterface")
     * @ORM\JoinColumn(nullable=true, referencedColumnName="id")
     */
    protected $contributor;

    /**
     *
     * 0 = InProgress ( contribution being written )
     * 1 = Rejected   ( Contribution rejected not validated 'Requires reason $rejected' )
     * 2 = Submitted  ( Contribution submitted and not verified by a moderator )
     * 3 = Validated  ( Contribution submitted and approved by a moderator visible by all )
     * 
     * @ORM\Column(type="smallint", nullable=false)
     * 
     */
    protected $status = 0;

    public function __construct(UserInterface $user = null) {
        
        if ($user !== NULL) {
            $this->setContributor($user);
        }
        
        $this->setDate(new \DateTime());
    }


    /**
     * Set id
     *
     * @param int $id
     *
     * @return this
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
     * @return this
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
     * Set version
     *
     * @param string $version
     * examples: 1.0.0 or 1.0
     *
     * @return this
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return this
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
     * @return this
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
     * @return this
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
     * @param \Discutea\UsersBundle\Entity\Users $contributor
     *
     * @return this
     */
    public function setContributor(UserInterface $contributor)
    {
        $this->contributor = $contributor;

        return $this;
    }

    /**
     * Get author
     *
     * @return Symfony\Component\Security\Core\User\UserInterface
     */
    public function getContributor()
    {
        return $this->contributor;
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
     * @return this
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status
     * 
     * 0 = InProgress ( contribution being written )
     * 1 = Rejected   ( Contribution rejected not validated 'Requires reason $rejected' )
     * 2 = Submitted  ( Contribution submitted and not verified by a moderator )
     * 3 = Validated  ( Contribution submitted and approved by a moderator visible by all )
     * 
     * @return this
     */
    public function setStatus($status)
    {
        if ( ( is_int($status) === false ) || ($status < 0) || ($status > 4) ) {
            throw new \LogicException('The logic of the status property isn\'t respected!');
        }
        
        $this->status = $status;

        return $this;
    }
}
