<?php

namespace Modules\Core\Tests;

use Tests\TestCase;

class BaseTest extends TestCase
{
    /**
     * Initialization level before running tests, after calling setUp().
     *
     * @return void
     */
    protected function init(): void
    {
        //
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->init();
    }
}
