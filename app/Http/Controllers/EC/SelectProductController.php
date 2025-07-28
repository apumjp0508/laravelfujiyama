<?php

namespace App\Http\Controllers\EC;

use App\Http\Controllers\Controller;
use App\Services\SelectProductService;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SelectProductController extends Controller
{
    use ErrorHandlingTrait;

    protected $selectProductService;

    public function __construct(SelectProductService $selectProductService)
    {
        $this->selectProductService = $selectProductService;
    }

    public function index()
    {
        return $this->executeControllerWithErrorHandling(
            function() {
                $data = $this->selectProductService->getBadgesAndUser();
                return view('ec.select', $data);
            },
            'product_selection_page_display',
            ['user_id' => Auth::user()->id ?? null]
        );
    }

    public function store(Request $request)
    {
        return $this->executeControllerWithErrorHandlingAndInput(
            function() use ($request) {
                $selectedBadgeIds = $request->input('selected_badges', []);
                $this->selectProductService->createSelectedBadges($selectedBadgeIds);
                return redirect()->route('cart.index')->with('success', 'バッジを選択しました。');
            },
            'selected_badges_creation',
            [
                'user_id' => Auth::user()->id ?? null,
                'selected_badge_ids' => $request->input('selected_badges', [])
            ]
        );
    }
}


