<?php

declare(strict_types=1);

namespace GdprExtensionsCom\GdprExtensionsComGm\Tests\Unit\Domain\Model;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 */
class MapTest extends UnitTestCase
{
    /**
     * @var \GdprExtensionsCom\GdprExtensionsComGm\Domain\Model\Map|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            \GdprExtensionsCom\GdprExtensionsComGm\Domain\Model\Map::class,
            ['dummy']
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getTitleReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getTitle()
        );
    }

    /**
     * @test
     */
    public function setTitleForStringSetsTitle(): void
    {
        $this->subject->setTitle('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('title'));
    }

    /**
     * @test
     */
    public function getIiconPathReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getIiconPath()
        );
    }

    /**
     * @test
     */
    public function setIiconPathForStringSetsIiconPath(): void
    {
        $this->subject->setIiconPath('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('iiconPath'));
    }

    /**
     * @test
     */
    public function getMapPathReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getMapPath()
        );
    }

    /**
     * @test
     */
    public function setMapPathForStringSetsMapPath(): void
    {
        $this->subject->setMapPath('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('mapPath'));
    }

    /**
     * @test
     */
    public function getLocationsReturnsInitialValueForMapLocation(): void
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getLocations()
        );
    }

    /**
     * @test
     */
    public function setLocationsForObjectStorageContainingMapLocationSetsLocations(): void
    {
        $location = new \GdprExtensionsCom\GdprExtensionsComGm\Domain\Model\MapLocation();
        $objectStorageHoldingExactlyOneLocations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneLocations->attach($location);
        $this->subject->setLocations($objectStorageHoldingExactlyOneLocations);

        self::assertEquals($objectStorageHoldingExactlyOneLocations, $this->subject->_get('locations'));
    }

    /**
     * @test
     */
    public function addLocationToObjectStorageHoldingLocations(): void
    {
        $location = new \GdprExtensionsCom\GdprExtensionsComGm\Domain\Model\MapLocation();
        $locationsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->onlyMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $locationsObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($location));
        $this->subject->_set('locations', $locationsObjectStorageMock);

        $this->subject->addLocation($location);
    }

    /**
     * @test
     */
    public function removeLocationFromObjectStorageHoldingLocations(): void
    {
        $location = new \GdprExtensionsCom\GdprExtensionsComGm\Domain\Model\MapLocation();
        $locationsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->onlyMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $locationsObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($location));
        $this->subject->_set('locations', $locationsObjectStorageMock);

        $this->subject->removeLocation($location);
    }
}
