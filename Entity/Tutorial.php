<?php
namespace Discutea\DTutoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \Discutea\DTutoBundle\Entity\Category;
use \Discutea\DTutoBundle\Entity\Contribution;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity(repositoryClass="Discutea\DTutoBundle\Repository\TutorialRepository")
 * @ORM\Table(name="dtuto_tutorial")
 */
class Tutorial
{
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="image_url", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="Discutea\DTutoBundle\Entity\Category", inversedBy="tutorials")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false)
     */
    protected $category;
    
    /**
     * @ORM\OneToMany(targetEntity="Discutea\DTutoBundle\Entity\Contribution", mappedBy="tutorial", cascade={"remove"}))
     * @ORM\OrderBy({"date" = "desc"})
     */
    protected $contributions;

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

    /**
     * Temporary variable used only by onCreate method of TutorialListener
     */
    protected $tmpContrib;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contributions = new ArrayCollection();
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
     * Set category
     *
     * @param \Discutea\DTutoBundle\Entity\Category $category
     *
     * @return Tutorial
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Discutea\DTutoBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add contribution
     *
     * @param \Discutea\DTutoBundle\Entity\Contribution $contribution
     *
     * @return Tutorial
     */
    public function addContribution(Contribution $contribution)
    {
        $this->contributions[] = $contribution;

        return $this;
    }

    /**
     * Remove contribution
     *
     * @param \Discutea\DTutoBundle\Entity\Contribution $contribution
     */
    public function removeContribution(Contribution $contribution)
    {
        $this->Contributions->removeElement($contribution);
    }

    /**
     * Get contributions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContributions()
    {
        return $this->contributions;
    }

    /**
     * Set image url
     *
     * @param string $image
     *
     * @return this
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     */
    public function getImage()
    {
        return $this->image;
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
        if ( ( is_int($status) === false ) || ($status < 1) || ($status > 3) ) {
            throw new \LogicException('The logic of the status property isn\'t respected!');
        }
        
        $this->status = $status;

        return $this;
    }

    /**
     * Get tmpContrib
     *
     * @return integer
     */
    public function getTmpContrib()
    {
        return $this->tmpContrib;
    }

    /**
     * Set tmpContrib
     * 
     * @return this
     */
    public function setTmpContrib($tmpContrib)
    {
        $this->tmpContrib = $tmpContrib;
        $this->tmpContrib->setTutorial($this);
        return $this;
    }
}
