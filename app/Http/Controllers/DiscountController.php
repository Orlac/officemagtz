<?php

namespace App\Http\Controllers;

use App\Application\Discount\DiscountService;
use App\Http\Requests\CheckDiscountRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiscountController extends Controller
{
    public function __construct(private readonly DiscountService $discounts)
    {
    }

    public function index(): View
    {
        return view('discount.index');
    }

    public function issue(Request $request): RedirectResponse
    {
        $discount = $this->discounts->issue($request->user()->id);

        return redirect()->route('discount.index')->with('issued', [
            'code' => $discount->code,
            'percent' => $discount->percent,
        ]);
    }

    public function check(CheckDiscountRequest $request): RedirectResponse
    {
        $percent = $this->discounts->check($request->user()->id, $request->validated('code'));

        return redirect()->route('discount.index')
            ->withInput()
            ->with('check', ['percent' => $percent]);
    }
}
