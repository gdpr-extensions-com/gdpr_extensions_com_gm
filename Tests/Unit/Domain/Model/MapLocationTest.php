<?php

declare(strict_types=1);

namespace GdprExtensionsCom\GdprExtensionsComGm\Tests\Unit\Domain\Model;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 */
class MapLocationTest extends UnitTestCase
{
    /**
     * @var \GdprExtensionsCom\GdprExtensionsComGm\Domain\Model\MapLocation|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            \GdprExtensionsCom\GdprExtensionsComGm\Domain\Model\MapLocation::class,
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
    public function getAddressReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getAddress()
        );
    }

    /**
     * @test
     */
    public function setAddressForStringSetsAddress(): void
    {
        $this->subject->setAddress('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('address'));
    }

    /**
     * @test
     */
    public function getLatReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getLat()
        );
    }

    /**
     * @test
     */
    public function setLatForStringSetsLat(): void
    {
        $this->subject->setLat('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('lat'));
    }

    /**
     * @test
     */
    public function getLonReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getLon()
        );
    }

    /**
     * @test
     */
    public function setLonForStringSetsLon(): void
    {
        $this->subject->setLon('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('lon'));
    }

    /**
     * @test
     */
    public function getMapReturnsInitialValueForMap(): void
    {
        self::assertEquals(
            null,
            $this->subject->getMap()
        );
    }

    /**
     * @test
     */
    public function setMapForMapSetsMap(): void
    {
        $mapFixture = new \GdprExtensionsCom\GdprExtensionsComGm\Domain\Model\Map();
        $this->subject->setMap($mapFixture);

        self::assertEquals($mapFixture, $this->subject->_get('map'));
    }
}
