<?php

namespace PrenomsApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Year
 *
 * @ORM\Table(name="year")
 * @ORM\Entity(repositoryClass="PrenomsApiBundle\Repository\YearRepository")
 */
class Year
{

    /**
     * @var int
     *
     * @ORM\Column(name="year", type="integer", unique=true)
     * @ORM\Id
     */
    private $year;

    /**
     * @ORM\OneToMany(targetEntity="Metrics", mappedBy="year")
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
     * Set year
     *
     * @param integer $year
     *
     * @return Year
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }
}

