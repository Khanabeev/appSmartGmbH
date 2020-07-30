<?php

namespace Modules\Product\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ProductController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function index()
    {
        $client = new Client();
        $url = 'https://world.openfoodfacts.org/cgi/search.pl';
        $params = [
            'action' => 'process',
            'sort_by' => 'unique_scans_n',
            'page_size' => 20,
            'json' => 3
        ];
        $link = $url . '?' . http_build_query($params);

        $response = $client->get($link);

        $products = [];

        $properties = [
            'id',
            'product_name',
            'image_url',
            'categories'
        ];

        $isCorrect = true;

        if ($response->getStatusCode() != 200) {
            return abort(500);
        }

        $result = json_decode($response->getBody()->getContents());

        foreach ($result->products as $product) {

            foreach ($properties as $property) {
                if (!property_exists($product, $property)) {
                    $isCorrect = false;
                    break;
                }
            }

            if ($isCorrect) {
                $products[] = [
                    'id' => $product->id,
                    'name' => $product->product_name,
                    'image' => $product->image_front_url,
                    'categories' => $product->categories
                ];
            }
        };
        return view('product::index', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('product::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }
}
