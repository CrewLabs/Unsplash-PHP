<?php

namespace Unsplash\Tests;

use \Unsplash as Unsplash;
use \VCR\VCR;

/**
 * Class SearchTest
 * @package Unsplash\Tests
 */
class SearchTest extends BaseTest
{
    public function setUp(): void
    {
        parent::setUp();

        $connection = new Unsplash\Connection($this->provider, $this->accessToken);
        Unsplash\HttpClient::$connection = $connection;
    }

    public function testSearchPhotos()
    {
        VCR::insertCassette('search.json');
        $photos = Unsplash\Search::photos("paris");
        VCR::eject();

        $photosArrayObject = $photos->getArrayObject();

        $this->assertInstanceOf(Unsplash\Photo::class, $photosArrayObject[0]);
        $this->assertEquals(10, $photosArrayObject->count());
        $this->assertCount(10, $photos->getResults());
        $this->assertEquals(3364, $photos->getTotal());
        $this->assertEquals(337, $photos->getTotalPages());
    }

    public function testSearchCollections()
    {
        VCR::insertCassette('search.json');
        $collections = Unsplash\Search::collections("paris");
        VCR::eject();

        $collectionsArrayObject = $collections->getArrayObject();

        $this->assertInstanceOf(Unsplash\Collection::class, $collectionsArrayObject[0]);
        $this->assertEquals(9, $collectionsArrayObject->count());
        $this->assertEquals(160, $collections->getTotal());
        $this->assertCount(9, $collections->getResults());
        $this->assertEquals(16, $collections->getTotalPages());
    }

    public function testSearchUsers()
    {
        VCR::insertCassette('search.json');
        $users = Unsplash\Search::users("dechuck");
        VCR::eject();

        $usersArrayObject = $users->getArrayObject();

        $this->assertInstanceOf(Unsplash\User::class, $usersArrayObject[0]);
        $this->assertEquals(1, $usersArrayObject->count());
        $this->assertEquals(1, $users->getTotal());
        $this->assertCount(1, $users->getResults());
        $this->assertEquals(1, $users->getTotalPages());
    }

    public function testSearchOffset()
    {
        VCR::insertCassette('search.json');
        $users = Unsplash\Search::users("dechuck", 1, 1);
        $this->assertTrue(isset($users[0]));
        $this->assertFalse(isset($users[1]));
        $this->assertIsArray($users[0]);
        $users[1] = [];
        $this->assertIsArray($users[1]);
        $this->assertSame([], $users[1]);
        $this->assertTrue(isset($users[1]));
        unset($users[1]);
        $this->assertFalse(isset($users[1]));
        VCR::eject();
    }
}
