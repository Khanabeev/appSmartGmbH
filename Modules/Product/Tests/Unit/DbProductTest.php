<?php

namespace Modules\Product\Tests\Unit;

use Modules\Product\Entities\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DbProductTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testDb()
    {
        $product = factory(Product::class)->create();
        $this->assertDatabaseHas('products', $product->toArray());

        $product->name = 'test';
        $product->image = 'test';
        $product->product_id = 'test';
        $product->save();
        $this->assertDatabaseHas('products', $product->toArray());

        $product->delete();
        $this->assertDatabaseMissing('products', $product->toArray());
    }
}
