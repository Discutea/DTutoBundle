<?php
namespace Discutea\DTutoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Discutea\DTutoBundle\Entity\Tutorial;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Discutea\DTutoBundle\Repository\CategoryRepository")
 * @ORM\Table(name="dtuto_category")
 * 
 * @package  DTutoBundle
 * @author   David Verdier <contact@discutea.com>
 * https://www.linkedin.com/in/verdierdavid
 *
 */
class Category 
{
    /**
     * @var smallint
     *
     * @ORM\Column(type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="disp_position", type="integer", options={"unsigned"=true})
     */
    protected $position = 0;

    /**
     * @ORM\OneToMany(targetEntity="Discutea\DTutoBundle\Entity\Tutorial", mappedBy="category", cascade={"persist", "remove"})
     */
    protected $tutorials;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tutorials = new ArrayCollection();
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
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param  string
     * @return null
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return Category
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Add tutorial
     *
     * @param \Discutea\DTutoBundle\Entity\Tutorial $tutorial
     *
     * @return Category
     */
    public function addTutorial(Tutorial $tutorial)
    {
        $this->tutorials[] = $tutorial;

        return $this;
    }

    /**
     * Remove tutorial
     *
     * @param \Discutea\DTutoBundle\Entity\Tutorial $tutorial
     */
    public function removeTutorial(Tutorial $tutorial)
    {
        $this->tutorials->removeElement($tutorial);
    }

    /**
     * Get tutorials
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTutorials()
    {
        return $this->tutorials;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }


}
