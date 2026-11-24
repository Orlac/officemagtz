<?php

namespace App\Http\Controllers;

use App\Application\Discount\DiscountService;
use App\Http\Requests\CheckDiscountRequest;
use DateTime;
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
            'expires_at' => $this->discounts->expiresAt($discount)->format('d.m.Y H:i'),
        ]);
    }

    public function check(CheckDiscountRequest $request): RedirectResponse
    {
        $discount = $this->discounts->getDiscount($request->user()->id, $request->validated('code'));

        if ($discount === null) {
            return redirect()->route('discount.index')
                ->withInput()
                ->with('error', 'Скидка недоступна');
        }

        return redirect()->route('discount.index')
            ->withInput()
            ->with('check', [
                'percent' => $discount->percent,
                'expires_at' => $this->discounts->expiresAt($discount)->format('d.m.Y H:i'),
            ]);
    }
}
