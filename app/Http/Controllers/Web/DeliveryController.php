<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Exception;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeliveryController extends Controller
{
    private readonly Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fa_IR');
    }

    public function index(Request $request): View
    {
        $query = Delivery::query()
            ->when(
                strtolower($request->get('q', '')),
                fn($query, $q) => $query->where(
                    fn($qb) => $qb->where('maskedId', 'like', "%{$q}%")
                        ->orWhere('id', $q)
                )
            )
            ->when(
                $request->get('status', ''),
                fn($query, $status) => $query->where('status', $status)
            );

        $total = $query->count();

        $deliveries = $query
            ->with(['terminals'])
            ->limit(10)
            ->offset(($request->get('page', 1) - 1) * 10)
            ->orderBy('id', 'desc')
            ->get();

        return view('deliveries.index', compact('deliveries', 'total'));
    }

    public function show(string $maskedId): View
    {
        $delivery = Delivery::query()
            ->where('maskedId', $maskedId)
            ->orWhere('id', $maskedId)
            ->with(['items', 'terminals', 'statusHistories'])
            ->firstOrFail();

        return view('deliveries.show', compact('delivery'));
    }

    public function updateStatus(string $maskedId, Request $request): RedirectResponse
    {
        $status = $request->get('status');

        /** @var Delivery $delivery */
        $delivery = Delivery::query()
            ->where('maskedId', $maskedId)
            ->orWhere('id', $maskedId)
            ->firstOrFail();

        if ($status === 'ACCEPTED') {
            $delivery->update([
                'bikerId' => $this->faker->numberBetween(1, 3000),
                'bikerName' => $this->faker->name('male'),
                'bikerPhoneNumber' => $this->faker->numerify('09#########'),
                'bikerPhotoUrl' => $this->faker->imageUrl(),
                'status' => 'ACCEPTED',
            ]);
        } elseif ($status === 'PENDING') {
            $delivery->update([
                'bikerId' => null,
                'bikerName' => null,
                'bikerPhoneNumber' => null,
                'bikerPhotoUrl' => null,
                'status' => 'PENDING',
            ]);
        } else {
            $delivery->update(['status' => $status]);
        }

        return redirect(route('deliveries.show', $maskedId));
    }

    public function payInvoice(string $maskedId): RedirectResponse
    {
        /** @var Delivery $delivery */
        $delivery = Delivery::query()
            ->where('maskedId', $maskedId)
            ->orWhere('id', $maskedId)
            ->firstOrFail();

        if ($delivery->invoiceStatus === 'SUCCESS') {
            return redirect(route('deliveries.show', $maskedId));
        }

        if ($delivery->merchandiseCost === null) {
            throw new Exception('This delivery has no invoice to pay!');
        }

        $delivery->update([
            'invoiceStatus' => 'SUCCESS',
        ]);

        return redirect(route('deliveries.show', $maskedId));
    }
}
