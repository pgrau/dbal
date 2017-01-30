<?php

namespace Doctrine\Tests\DBAL\Driver\Mysqli;

use Doctrine\DBAL\Driver\Mysqli\MysqliConnection;
use Doctrine\DBAL\Driver\Mysqli\MysqliException;
use Doctrine\Tests\DbalTestCase;

class MysqliConnectionTest extends DbalTestCase
{
    /**
     * The mysqli driver connection mock under test.
     *
     * @var \Doctrine\DBAL\Driver\Mysqli\MysqliConnection|\PHPUnit_Framework_MockObject_MockObject
     */
    private $connectionMock;

    protected function setUp()
    {
        if (!extension_loaded('mysqli')) {
            $this->markTestSkipped('mysqli is not installed.');
        }

        parent::setUp();

        $this->connectionMock = $this->getMockBuilder(MysqliConnection::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
    }

    public function testDoesNotRequireQueryForServerVersion()
    {
        $this->assertFalse($this->connectionMock->requiresQueryForServerVersion());
    }

    /**
     * @dataProvider secureParamsContainErrorsProvider
     */
    public function testItShouldReturnAnExceptionWhenMissingMandatorySecureParams(array $secureParams)
    {
        $this->expectException(MysqliException::class);
        $this->expectExceptionMessage('ssl_key and ssl_cert are mandatory for secure connections');

        new MysqliConnection($secureParams, 'xxx', 'xxx');
    }

    public function secureParamsContainErrorsProvider()
    {
        return [
            [
                ['ssl_cert' => 'cert.pem']
            ],
            [
                ['ssl_key' => 'key.pem']
            ]
        ];
    }
}

