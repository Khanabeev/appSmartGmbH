<?php

namespace Modules\Product\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\Product\Entities\Product;
use PhpParser\Node\Expr\Cast\Object_;

class ProductController extends Controller
{

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $url = 'https://world.openfoodfacts.org/cgi/search.pl';
        $params = [
            'action' => 'process',
            'sort_by' => 'unique_scans_n',
            'page_size' => 40,
            'json' => 1
        ];
        $ttl = 3200 * 24 * 7;
        $key = sha1($url . implode(',', $params));
        $products = Cache::remember($key, $ttl, function () use ($url, $params) {
            return $this->getProducts($url, $params);
        });

        $propertiesList = [
            'id' => 'id',
            'name' => 'product_name',
            'image' => 'image_thumb_url',
            'categories' => 'categories'
        ];

        $items = [];

        foreach ($products as $product) {
            $items[] = $this->getProperties($product, $propertiesList);
        };

        if (empty($items)) {
            Log::error('ERROR: get empty products array');
        }

        return view('product::index', ['items' => $items]);
    }

    /**
     * @param object $product
     * @param array $properties
     * @return array
     */
    private function getProperties(object $product, array $properties): array
    {
        $arr = [];
        foreach ($properties as $key => $value) {
            if (property_exists($product, $value)) {
                $arr[$key] = $product->$value;
            } else {
                $arr[$key] = '';
            }
        }
        return $arr;
    }

    /**
     * @param string $url
     * @param array $params
     * @return array
     */
    private function getProducts(string $url, array $params): array
    {
        $client = new Client();

        $link = $url . '?' . http_build_query($params);

        $response = $client->get($link);

        if ($response->getStatusCode() != 200) {
            Log::error('ERROR: Get products status - ' . $response->getStatusCode());
            return [];
        }

        $result = json_decode($response->getBody()->getContents());
        return $result->products;
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
            'name' => ['required'],
            'categories' => ['required'],
            'image' => ['url']
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false],\Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST);
        }

        try {
            Product::updateOrCreate(
                ['product_id' => $request->get('id')],
                ['name' => $request->get('name'), 'categories' => $request->get('categories'), 'image' => $request->get('image')]
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['success' => false], \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['success' => true]);
    }
}
