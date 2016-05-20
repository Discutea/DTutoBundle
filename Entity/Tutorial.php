<?php
namespace Discutea\DTutoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \Discutea\DTutoBundle\Entity\Category;
use \Discutea\DTutoBundle\Entity\Contribution;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Discutea\DTutoBundle\Repository\TutorialRepository")
 * @ORM\Table(name="dtuto_tutorial")
 */
class Tutorial
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(length=80, type="string", nullable=false)
     * @Assert\NotBlank()
     */
    protected $title;
    
    /**
     * @var text
     * 
     * @ORM\Column(name="description", length=150, type="string", nullable=true)
     *
     */
    protected $description;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

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
     * Temporary variable used only by onCreate method of TutorialListener
     */
    protected $tmpContrib;

    /**
     * Current Contribution
     */
    protected $currentContribution;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contributions = new ArrayCollection();
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
     * Get tmpContrib
     *
     * @return integer
     */
    public function getTmpContrib()
    {
        /*
    }
        if (NULL === $this->tmpContrib) {
            $this->tmpContrib = $this->setTmpContrib();
        }
        */
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

    /**
     * Get current contribution
     * use singleton
     *
     * @return \Discutea\DTutoBundle\Entity\Contribution
     */
    public function getCurrent()
    {
        if ($this->currentContribution === NULL) {
            $this->currentContribution = $this->setCurrent();
        }

        return $this->currentContribution;
    }
    
    /**
     * Set current contribution
     *
     * @return \Discutea\DTutoBundle\Entity\Contribution
     */
    public function setCurrent()
    {
        
        $contrib = $this->getContributions()->filter(
            function(Contribution $contribution) {
                return in_array($contribution->getCurrent(), array(true));
            }
        );
        
        if ($contrib !== NULL) {
            $contrib = $contrib->first();
        }
        
        return $contrib;
    }
}
