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
 * MapLocation
 */
class MapLocation extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * title
     *
     * @var string
     */
    protected $title = '';

    /**
     * address
     *
     * @var string
     */
    protected $address = '';

    /**
     * lat
     *
     * @var int
     */
    protected $lat = null;

    /**
     * lon
     *
     * @var int
     */
    protected $lon = null;

    /**
     * map
     *
     * @var \GdprExtensionsCom\GdprExtensionsComGm\Domain\Model\Map
     */
    protected $map = null;

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
     * Returns the address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets the address
     *
     * @param string $address
     * @return void
     */
    public function setAddress(string $address)
    {
        $this->address = $address;
    }


    /**
     * Returns the lat
     *
     * @return int
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Sets the lat
     *
     * @param int $lat
     * @return void
     */
    public function setLat(int $lat)
    {
        $this->lat = $lat;
    }

    /**
     * Returns the lon
     *
     * @return int
     */
    public function getLon()
    {
        return $this->lon;
    }

    /**
     * Sets the lon
     *
     * @param int $lon
     * @return void
     */
    public function setLon(int $lon)
    {
        $this->lon = $lon;
    }

    /**
     * Returns the map
     *
     * @return \GdprExtensionsCom\GdprExtensionsComGm\Domain\Model\Map
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * Sets the map
     *
     * @param \GdprExtensionsCom\GdprExtensionsComGm\Domain\Model\Map $map
     * @return void
     */
    public function setMap(\GdprExtensionsCom\GdprExtensionsComGm\Domain\Model\Map $map)
    {
        $this->map = $map;
    }
}
