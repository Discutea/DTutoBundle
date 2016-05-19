<?php
namespace Discutea\DTutoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Discutea\DTutoBundle\Entity\Tutorial;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity(repositoryClass="Discutea\DTutoBundle\Repository\CategoryRepository")
 * @ORM\Table(name="dtuto_category")
 */
class Category 
{
use ORMBehaviors\Translatable\Translatable;
    /**
     * @var smallint
     *
     * @ORM\Column(type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

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
     * Constructor
     */
    public function __construct()
    {
        $this->tutorials = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    public function __call($method, $arguments)
    {
        return \Symfony\Component\PropertyAccess\PropertyAccess::createPropertyAccessor()->getValue($this->translate(), $method);
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

}
