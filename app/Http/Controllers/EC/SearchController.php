<?php

namespace App\Http\Controllers\EC;

use App\Http\Controllers\Controller;
use App\Services\SearchService;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    use ErrorHandlingTrait;

    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function search(Request $request)
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($request) {
                $keyword = $request->keyword;
                $result = $this->searchService->searchProducts($keyword);
                
                return view('ec.search', [
                    'products' => $result['products'],
                    'keyword' => $result['keyword']
                ]);
            },
            'product_search',
            ['keyword' => $request->keyword]
        );
    }
}
