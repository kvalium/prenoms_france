<?php

namespace PrenomsApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Sexe
 *
 * @ORM\Table(name="sex")
 * @ORM\Entity(repositoryClass="PrenomsApiBundle\Repository\SexRepository")
 */
class Sex
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", unique=true)
     * @ORM\Id
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="sex", type="string", unique=true)
     */
    private $sex;


    /**
     * @ORM\OneToMany(targetEntity="Metrics", mappedBy="sex")
     */
    protected $metrics;


    public function __construct()
    {
        $this->metrics = new ArrayCollection();
    }

    /**
     * Get sex metrics
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
     * Set sexe
     *
     * @param integer $sex
     *
     * @return Sex
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return int
     */
    public function getSex()
    {
        return $this->sex;
    }
}

