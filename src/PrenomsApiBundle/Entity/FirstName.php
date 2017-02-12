<?php

namespace PrenomsApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * FirstName
 *
 * @ORM\Table(name="first_name")
 * @ORM\Entity(repositoryClass="PrenomsApiBundle\Repository\FirstNameRepository")
 */
class FirstName
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=100, unique=true)
     */
    private $firstName;

    /**
     * @ORM\OneToMany(targetEntity="Metrics", mappedBy="firstname")
     */
    protected $metrics;

    public function __construct()
    {
        $this->metrics = new ArrayCollection();
    }

    /**
     * Get first name metrics
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMetrics()
    {
        return $this->metrics;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return FirstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }
}

