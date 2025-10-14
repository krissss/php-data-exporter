<?php

namespace Kriss\DataExporter\Tests;

use Kriss\DataExporter\DataExporter;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        DataExporter::clean();
    }
}
