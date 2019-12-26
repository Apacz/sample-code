<?php

namespace AppBundle\Entity;

use Apacz\FeedBundle\Entity\SchemaConnect;
use Apacz\MapBundle\Interfaces\iGeographicalCoordinates;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * City
 *
 * @ORM\Table(name="city")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CityRepository")
 */
class City  implements iGeographicalCoordinates
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var SchemaConnect
     *
     * Many Features have One Product.
     * @ORM\ManyToMany(targetEntity="Apacz\FeedBundle\Entity\SchemaConnect")
     * @ORM\JoinTable(name="feed_city",
     *      joinColumns={@ORM\JoinColumn(name="city_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="feed_id", referencedColumnName="id")}
     *)
     */
    private $schemaConnect;

    /**
     * @var int
     *
     * @ORM\Column(name="longitude", type="decimal", scale=6, precision=9)
     */
    private $longitude = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="latitude", type="decimal", scale=6, precision=9)
     */
    private $latitude  = 0;

    /**
     * City constructor.
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->schemaConnect = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return City
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Collection
     */
    public function getSchemaConnect() : Collection
    {
        return $this->schemaConnect;
    }

    /**
     * @param Collection $schemaConnect
     */
    public function setSchemaConnect(Collection $schemaConnect)
    {
        $this->schemaConnect = $schemaConnect;
    }

    /**
     * @return int
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @param int $longitude
     */
    public function setLongitude(float $longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return int
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @param int $latitude
     */
    public function setLatitude(float $latitude)
    {
        $this->latitude = $latitude;
    }

    public function __toString()
    {
        return $this->name;
    }
}

