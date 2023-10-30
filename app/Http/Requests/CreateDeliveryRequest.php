<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateDeliveryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, (ValidationRule|string)[]> */
    public function rules(): array
    {
        return [
            'customerId' => ['required', 'string'],

            'data' => ['required', 'array'],

            'data.orderDetails' => ['required', 'array'],
            'data.orderDetails.customerId' => ['required', 'string'],
            'data.orderDetails.customerRefId' => ['required', 'string'],
            'data.orderDetails.city' => ['required', 'string'],
            'data.orderDetails.deliveryCategory' => ['required', 'in:bike-without-box,bike,van,van-heavy'],
            'data.orderDetails.deliveryFarePaymentType' => ['required', 'in:prepaid,cod'],
            'data.orderDetails.isReturn' => ['required', 'boolean'],
            'data.orderDetails.batchable' => ['required', 'boolean'],

            'data.pickUpDetails' => ['required', 'array', 'min:1'],
            'data.pickUpDetails.*.sequenceNumber' => ['required', 'integer', 'distinct'],
            'data.pickUpDetails.*.type' => ['required', 'string', 'in:pickup'],
            'data.pickUpDetails.*.paymentType' => ['required', 'string', 'in:prepaid,cod'],
            'data.pickUpDetails.*.latitude' => ['required', 'numeric'],
            'data.pickUpDetails.*.longitude' => ['required', 'numeric'],
            'data.pickUpDetails.*.address' => ['required', 'string'],
            'data.pickUpDetails.*.contactName' => ['required', 'string'],
            'data.pickUpDetails.*.contactPhoneNumber' => ['sometimes', 'string', 'regex:/^0\d{10}$/'],
            'data.pickUpDetails.*.contactEmail' => ['sometimes', 'string', 'email'],
            'data.pickUpDetails.*.comment' => ['sometimes', 'string'],

            'data.dropOffDetails' => ['required', 'array', 'min:1'],
            'data.dropOffDetails.*.sequenceNumber' => ['required', 'integer', 'distinct'],
            'data.dropOffDetails.*.type' => ['required', 'string', 'in:drop'],
            'data.dropOffDetails.*.paymentType' => ['required', 'string', 'in:prepaid,cod'],
            'data.dropOffDetails.*.latitude' => ['required', 'numeric'],
            'data.dropOffDetails.*.longitude' => ['required', 'numeric'],
            'data.dropOffDetails.*.address' => ['required', 'string'],
            'data.dropOffDetails.*.contactName' => ['required', 'string'],
            'data.dropOffDetails.*.contactPhoneNumber' => ['sometimes', 'string', 'regex:/^0\d{10}$/'],
            'data.dropOffDetails.*.contactEmail' => ['sometimes', 'string', 'email'],
            'data.dropOffDetails.*.comment' => ['sometimes', 'string'],

            'data.dropOffDetails.*.merchandise' => ['sometimes', 'array'],
            'data.dropOffDetails.*.merchandise.storeId' => ['string'],
            'data.dropOffDetails.*.merchandise.description' => ['string'],
            'data.dropOffDetails.*.merchandise.cost' => ['numeric'],

            'data.itemDetails' => ['required', 'array', 'min:1'],
            'data.itemDetails.*.name' => ['required', 'string'],
            'data.itemDetails.*.quantity' => ['required', 'integer'],
            'data.itemDetails.*.quantityMeasuringUnit' => ['required', 'string'],
            'data.itemDetails.*.packageValue' => ['nullable', 'integer'],
            'data.itemDetails.*.dropOffSequenceNumber' => ['required', 'integer'],
            'data.itemDetails.*.pickedUpSequenceNumber' => ['required', 'integer'],

            'data.timeSlotDTO' => ['sometimes', 'array'],
            'data.timeSlotDTO.startTimeSlot' => [
                'required_with:data.timeSlotDTO',
                'date_format:Y-m-d H:i:s',
                'before:data.timeSlotDTO.endTimeSlot',
            ],
            'data.timeSlotDTO.endTimeSlot' => ['required_with:data.timeSlotDTO', 'date_format:Y-m-d H:i:s'],
        ];
    }
}
