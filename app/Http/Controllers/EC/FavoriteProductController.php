<?php

namespace App\Http\Controllers\EC;

use App\Http\Controllers\Controller;
use App\Services\FavoriteService;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteProductController extends Controller
{
    use ErrorHandlingTrait;

    protected $favoriteService;

    public function __construct(FavoriteService $favoriteService)
    {
        $this->favoriteService = $favoriteService;
    }

    public function show()
    {
        return $this->executeControllerWithErrorHandling(
            function() {
                $userId = Auth::user()->id;
                $result = $this->favoriteService->getUserFavorites($userId);
                
                return view('ec.favorite', [
                    'products' => $result['products']
                ]);
            },
            'favorites_display',
            ['user_id' => Auth::user()->id ?? null]
        );
    }

    public function store($productId)
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($productId) {
                $userId = Auth::user()->id;
                $result = $this->favoriteService->addToFavorites($userId, $productId);
                
                return back()->with('success', $result['message']);
            },
            'add_to_favorites',
            [
                'user_id' => Auth::user()->id ?? null,
                'product_id' => $productId
            ]
        );
    }

    public function destroy($productId)
    {
        return $this->executeControllerWithErrorHandling(
            function() use ($productId) {
                $userId = Auth::user()->id;
                $result = $this->favoriteService->removeFromFavorites($userId, $productId);
                
                return back()->with('success', $result['message']);
            },
            'remove_from_favorites',
            [
                'user_id' => Auth::user()->id ?? null,
                'product_id' => $productId
            ]
        );
    }
}
