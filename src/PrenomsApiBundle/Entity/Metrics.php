<?php

namespace PrenomsApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Metrics
 *
 * @ORM\Table(name="metrics")
 * @ORM\Entity(repositoryClass="PrenomsApiBundle\Repository\MetricsRepository")
 */
class Metrics
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
     * @var int
     *
     * @ORM\Column(name="count", type="integer")
     */
    private $count;

    /**
     *
     * @ORM\ManyToOne(targetEntity="FirstName", inversedBy="metrics")
     * @ORM\JoinColumn(name="first_name_id", referencedColumnName="id")
     *
     */
    private $firstname;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Sex", inversedBy="metrics")
     * @ORM\JoinColumn(name="sex", referencedColumnName="id")
     */
    private $sex;
    /**
     *
     * @ORM\ManyToOne(targetEntity="Year", inversedBy="metrics")
     * @ORM\JoinColumn(name="year", referencedColumnName="year")
     */
    private $year;


    /**
     * Get first name
     *
     * @return FirstName
     */
    public function getFirstName()
    {
        return $this->firstname;
    }

    /**
     * Get sex
     *
     * @return Sex
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Get year
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year->getYear();
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
     * Set count
     *
     * @param integer $count
     *
     * @return Metrics
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @param mixed $sex
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }
}

