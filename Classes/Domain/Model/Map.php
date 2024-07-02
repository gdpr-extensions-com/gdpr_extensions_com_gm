<?php

declare(strict_types=1);

namespace GdprExtensionsCom\GdprExtensionsComGm\Domain\Model;


/**
 * This file is part of the "gdpr-extensions.com_gdpr_map" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2023
 */

/**
 * Map
 */
class Map extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * title
     *
     * @var string
     */
    protected $title = '';

    /**
     * iconPath
     *
     * @var string
     */
    protected $iconPath = '';

    /**
     * mapPath
     *
     * @var string
     */
    protected $mapPath = '';

    /**
     * lat
     *
     * @var int
     */
    protected $lat = 0;

    /**
     * lon
     *
     * @var int
     */
    protected $lon = 0;

    /**
     * zoom
     *
     * @var int
     */
    protected $zoom = 0;

    /**
     * locations
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GdprExtensionsCom\GdprExtensionsComGm\Domain\Model\MapLocation>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $locations = null;

    /**
     * __construct
     */
    public function __construct()
    {

        // Do not remove the next line: It would break the functionality
        $this->initializeObject();
    }

    /**
     * Initializes all ObjectStorage properties when model is reconstructed from DB (where __construct is not called)
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    public function initializeObject()
    {
        $this->locations = $this->locations ?: new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * Adds a MapLocation
     *
     * @param \GdprExtensionsCom\GdprExtensionsComGm\Domain\Model\MapLocation $location
     * @return void
     */
    public function addLocation(\GdprExtensionsCom\GdprExtensionsComGm\Domain\Model\MapLocation $location)
    {
        $this->locations->attach($location);
    }

    /**
     * Removes a MapLocation
     *
     * @param \GdprExtensionsCom\GdprExtensionsComGm\Domain\Model\MapLocation $locationToRemove The MapLocation to be removed
     * @return void
     */
    public function removeLocation(\GdprExtensionsCom\GdprExtensionsComGm\Domain\Model\MapLocation $locationToRemove)
    {
        $this->locations->detach($locationToRemove);
    }

    /**
     * Returns the locations
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GdprExtensionsCom\GdprExtensionsComGm\Domain\Model\MapLocation>
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * Sets the locations
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GdprExtensionsCom\GdprExtensionsComGm\Domain\Model\MapLocation> $locations
     * @return void
     */
    public function setLocations(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $locations)
    {
        $this->locations = $locations;
    }

    /**
     * @return int
     */
    public function getLat(): ?int
    {
        return $this->lat;
    }

    /**
     * @param int $lat
     */
    public function setLat(?int $lat): void
    {
        $this->lat = $lat;
    }

    /**
     * @return int
     */
    public function getLon(): int
    {
        return $this->lon;
    }

    /**
     * @param int $lon
     */
    public function setLon(int $lon): void
    {
        $this->lon = $lon;
    }

    /**
     * @return int
     */
    public function getZoom(): int
    {
        return $this->zoom;
    }

    /**
     * @param int $zoom
     */
    public function setZoom(int $zoom): void
    {
        $this->zoom = $zoom;
    }

    /**
     * @return string
     */
    public function getIconPath(): string
    {
        return $this->iconPath;
    }

    /**
     * @param string $iconPath
     */
    public function setIconPath(string $iconPath): void
    {
        $this->iconPath = $iconPath;
    }

    /**
     * @return string
     */
    public function getMapPath(): string
    {
        return $this->mapPath;
    }

    /**
     * @param string $mapPath
     */
    public function setMapPath(string $mapPath): void
    {
        $this->mapPath = $mapPath;
    }
}
