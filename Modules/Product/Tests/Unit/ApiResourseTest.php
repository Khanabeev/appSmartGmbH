<?php

namespace Modules\Product\Tests\Unit;

use GuzzleHttp\Client;
use Modules\Product\Entities\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiResourseTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testApi()
    {
        $url = 'https://world.openfoodfacts.org/cgi/search.pl';
        $params = [
            'action' => 'process',
            'sort_by' => 'unique_scans_n',
            'page_size' => 10,
            'json' => 1
        ];
        $client = new Client();
        $link = $url . '?' . http_build_query($params);

        $response = $client->get($link);
        $this->assertTrue($response->getStatusCode() == 200);
    }

    public function testStoreItem()
    {
        $payload = [
          'id' => 'test',
          'name' => 'test',
          'categories' => 'test',
          'image' => 'https://picsum.photos/200/300'
        ];
        $response = $this->json('POST',route('api.product.store'),$payload);
        $response->assertStatus(200);
        $response->assertJsonStructure(['success']);

        $this->assertTrue(Product::where('product_id','test')->first()->delete());
    }
}
