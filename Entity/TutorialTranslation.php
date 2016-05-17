<?php
namespace Discutea\DTutoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="dtuto_trans_tutorial")
 */
class TutorialTranslation
{
    use ORMBehaviors\Translatable\Translation;

    /**
     * @var string
     *
     * @ORM\Column(name="name", length=80, type="string")
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "4",
     *      max = "80",
     *      minMessage = "Le nom du forum doit contenir au moins {{ limit }} caractères",
     *      maxMessage = "Le nom du forum ne peut pas être plus long que {{ limit }} caractères"
     * )
     */
    protected $title;
    
    /**
     * @var text
     * 
     * @ORM\Column(name="description", length=150, type="string")
     * @Assert\Length(
     *      max = "150",
     *      maxMessage = "La description ne doit pas contenir plus de {{ limit }} caractères"
     * )
     */
    protected $description;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Tutorial
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Tutorial
     */
    public function setdescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getdescription()
    {
        return $this->description;
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
