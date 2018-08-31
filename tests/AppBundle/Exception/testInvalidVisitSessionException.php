<?php

namespace tests\AppBundle\Exception;


use AppBundle\Exception\InvalidVisitSessionException;
use PHPUnit\Framework\TestCase;

class testInvalidVisitSessionException extends TestCase
{

    /**
     *
     * @expectedException InvalidVisitSessionException
     */
    public function testException()
    {
        $this->expectException(InvalidVisitSessionException::class)
        ->expectExceptionCode()
        ->expectExceptionMessage();

        $this->assertException();
    }
}