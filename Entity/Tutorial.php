<?php
namespace Discutea\DTutoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \Discutea\DTutoBundle\Entity\Category;
use \Discutea\DTutoBundle\Entity\Contribution;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * @ORM\OneToMany(targetEntity="Discutea\DTutoBundle\Entity\Contribution", mappedBy="tutorial", cascade={"persist", "remove"}))
     * @ORM\OrderBy({"date" = "desc"})
     */
    protected $contributions;

    /**
     * @ORM\ManyToOne(targetEntity="Symfony\Component\Security\Core\User\UserInterface")
     * @ORM\JoinColumn(name="opening_by", nullable=true, referencedColumnName="id")
     */
    protected $author;

    /**
     * @ORM\ManyToOne(targetEntity="Discutea\DTutoBundle\Entity\Contribution")
     * @ORM\JoinColumn(name="valid_contrib_id", nullable=true, referencedColumnName="id")
     */
    protected $validContribution;

    /**
     *
     * Provisoir contibution
     * 
     */
    protected $tmpContribution;

    /**
     * @ORM\Column(name="opening_at", type="datetime", nullable=true)
     */
    protected $date;

    /**
     * Constructor
     */
    public function __construct(Category $category = NULL, UserInterface $user = null)
    {
        $this->contrubutions = new ArrayCollection();
        $this->setDate(new \DateTime());

        if ($user !== NULL) {
            $this->setAuthor($user);
        }

        if ($category !== NULL) {
            $this->setCategory($category);
        }

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Contribution
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
     * Set author
     *
     * @param \Discutea\UsersBundle\Entity\Users $author
     *
     * @return Tutorial
     */
    public function setAuthor(UserInterface $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Discutea\UsersBundle\Entity\Users
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set validContribution
     *
     * @param \Discutea\DTutoBundle\Entity\Contribution $validContribution
     *
     * @return Tutorial
     */
    public function setValidContribution(Contribution $validContribution)
    {
        $this->validContribution = $validContribution;

        return $this;
    }

    /**
     * Get validContribution
     *
     * @return \Discutea\DTutoBundle\Entity\Contribution
     */
    public function getValidContribution()
    {
        return $this->validContribution;
    }

    /**
     * Get tmpContribution
     *
     * @return \Discutea\DTutoBundle\Entity\Contribution
     */
    public function getTmpContribution()
    {
        return $this->tmpContribution;
    }

    /**
     * Set tmpContribution
     *
     * @param \Discutea\DTutoBundle\Entity\Contribution $tmpContribution
     *
     * @return Tutorial
     */
    public function setTmpContribution(Contribution $tmpContribution)
    {
        $this->tmpContribution = $tmpContribution;
        $this->tmpContribution->setTutorial($this);
        $this->tmpContribution->setAuthor( $this->getAuthor() );
        

        return $this;
    }

}
