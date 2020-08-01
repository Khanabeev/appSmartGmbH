<?php

namespace Modules\Product\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ControllerProductTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testController()
    {
        $response = $this->get('/products');
        $response->assertViewHas('items');
        $response->assertStatus(200);
    }
}
