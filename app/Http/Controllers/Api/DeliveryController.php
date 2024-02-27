<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDeliveryRequest;
use App\Http\Responses\DeliveryCreatedResponse;
use App\Models\Delivery;
use App\Models\DeliveryItem;
use App\Models\DeliveryTerminal;
use Carbon\Carbon;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Location\Coordinate;
use Location\Distance\Vincenty;
use Throwable;

class DeliveryController extends Controller
{
    private readonly Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fa_IR');
    }

    public function index()
    {
        // TODO
    }

    public function store(CreateDeliveryRequest $request): DeliveryCreatedResponse
    {
        $request = $request->validated();
        $data = $request['data'];

        DB::beginTransaction();
        try {
            $delivery = new Delivery();

            $delivery->customerId = $request['customerId'];
            $delivery->maskedId = Str::uuid()->toString();
            $delivery->status = Delivery::STATUS_PENDING;
            $delivery->city = $data['orderDetails']['city'];
            $delivery->customerRefId = $data['orderDetails']['customerRefId'];
            $delivery->customerName = $data['orderDetails']['customerName'] ?? '';
            $delivery->customerPhoneNumber = $data['orderDetails']['customerPhoneNumber'] ?? null;
            $delivery->customerEmail = $data['orderDetails']['customerEmail'] ?? null;
            $delivery->deliveryCategory = $data['orderDetails']['deliveryCategory'];
            $delivery->deliveryFarePaymentType = $data['orderDetails']['deliveryFarePaymentType'];
            $delivery->isReturn = $data['orderDetails']['isReturn'];
            $delivery->batchable = $data['orderDetails']['batchable'];
            $delivery->startTimeSlot = new Carbon($data['timeSlotDTO']['startTimeSlot'] ?? 'now');
            $delivery->endTimeSlot = new Carbon($data['timeSlotDTO']['endTimeSlot'] ?? 'now');

            $delivery->deliveryFare = (new Vincenty())->getDistance(
                new Coordinate($data['pickUpDetails'][0]['latitude'], $data['pickUpDetails'][0]['longitude']),
                new Coordinate($data['dropOffDetails'][0]['latitude'], $data['dropOffDetails'][0]['longitude'])
            ) * Config::get('allocation.fee_per_meter');

            if (isset($data['dropOffDetails'][0]['merchandise'])) {
                $delivery->merchandiseCost =  $data['dropOffDetails'][0]['merchandise']['cost'];
                $delivery->merchandiseStoreId = $data['dropOffDetails'][0]['merchandise']['storeId'];
                $delivery->merchandiseDescription = $data['dropOffDetails'][0]['merchandise']['description'];
                $delivery->invoiceStatus = 'PENDING';
            }

            $delivery->save();

            foreach ($data['itemDetails'] as $item) {
                $deliveryItem = new DeliveryItem();
                $deliveryItem->delivery_id = $delivery->id;
                $deliveryItem->dropOffSequenceNumber = $item['dropOffSequenceNumber'];
                $deliveryItem->pickedUpSequenceNumber = $item['pickedUpSequenceNumber'];
                $deliveryItem->name = $item['name'];
                $deliveryItem->quantity = $item['quantity'];
                $deliveryItem->quantityMeasuringUnit = $item['quantityMeasuringUnit'];
                $deliveryItem->packageValue = $item['packageValue'] ?? null;
                $deliveryItem->save();
            }

            foreach (array_merge($data['pickUpDetails'], $data['dropOffDetails']) as $terminal) {
                $deliveryTerminal = new DeliveryTerminal();
                $deliveryTerminal->delivery_id = $delivery->id;
                $deliveryTerminal->sequence = $terminal['sequenceNumber'];
                $deliveryTerminal->type = $terminal['type'];
                $deliveryTerminal->paymentType = $terminal['paymentType'];
                $deliveryTerminal->status = 'pending';
                $deliveryTerminal->contactName = $terminal['contactName'];
                $deliveryTerminal->contactPhoneNumber = $terminal['contactPhoneNumber'] ?? null;
                $deliveryTerminal->latitude = $terminal['latitude'];
                $deliveryTerminal->longitude = $terminal['longitude'];
                $deliveryTerminal->address = $terminal['address'];
                $deliveryTerminal->comment = $terminal['comment'] ?? null;
                $deliveryTerminal->save();
            }

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return new DeliveryCreatedResponse($delivery);
    }

    public function show(string $id)
    {
        // TODO
    }

    public function update(Request $request, string $id)
    {
        // TODO
    }

    public function destroy(string $id)
    {
        // TODO
    }
}
