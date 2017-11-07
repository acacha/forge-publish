<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;

/**
 * Class ApiPendingServersControllerTest.
 *
 * @package Tests\Feature
 */
class ExampleTest  extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        App::setLocale('en');
//        $this->withoutExceptionHandling();
    }


}