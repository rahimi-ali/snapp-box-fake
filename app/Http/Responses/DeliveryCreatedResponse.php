<?php

namespace App\Http\Responses;

use App\Models\Delivery;
use App\Models\DeliveryTerminal;
use Illuminate\Http\JsonResponse;

class DeliveryCreatedResponse extends JsonResponse
{
    public function __construct(Delivery $delivery)
    {
        parent::__construct(
            [
                'api_status' => 'success',
                'status_code' => '201',
                'key' => 'ORDER_CREATED',
                'message' => 'سفارش با موفقیت ایجاد شد.',
                'data' => [
                    'allocationTimeout' => config('allocation.allocation_timeout'),
                    'orderId' => (string)$delivery->id,
                    'finalCustomerFare' => $delivery->deliveryFare,
                    'details' => [
                        'customerName' => $delivery->customerName,
                        'customerPhonenumber' => $delivery->customerPhoneNumber,
                        'deliveryFare' => $delivery->deliveryFare,
                        'deliveryCategory' => $delivery->deliveryCategory,
                        'paymentType' => $delivery->deliveryFarePaymentType,
                        'walletType' => 'SNAPP_BOX',
                        'hasReturn' => $delivery->isReturn,
                        'allocationTtl' => 200,
                        'loadAssistance' => false,
                        'status' => $delivery->status,
                        'hasDriver' => false,
                        'driverVehicleType' => __('values.vehicle_types.' . $delivery->deliveryCategory),
                        'createdAt' => $delivery->created_at->toIso8601String(),
                        'createdAtJalali' => $delivery->created_at->locale('fa')->toDateTimeLocalString(),
                        'terminals' => $delivery->terminals->map(function (DeliveryTerminal $terminal) use ($delivery) {
                            $commonTerminalData = [
                                'id' => $terminal->id,
                                'sequenceNumber' => $terminal->sequence,
                                'type' => strtoupper($terminal->type),
                                'status' => $terminal->status,
                                'contactName' => $terminal->contactName,
                                'contactPhoneNumber' => $terminal->contactPhoneNumber,
                                'latitude' => $terminal->latitude,
                                'longitude' => $terminal->longitude,
                                'address' => $terminal->address,
                                'statusText' => __('values.terminal_statuses.' . $terminal->status),
                                'verboseAddress' => $terminal->address,
                                'editMerchandiseInfo' => 'DISABLED',
                            ];

                            if ($terminal->type === 'drop' && $delivery->merchandiseCost) {
                                return [...$commonTerminalData, [
                                    'merchandiseInvoiceId' => (string) $delivery->id,
                                    'merchandiseInvoiceLink' => route('deliveries.show', $delivery),
                                    'merchandiseInvoiceLinkRecipient' => route('deliveries.show', $delivery),
                                    'merchandiseInvoiceStatus' => $delivery->invoiceStatus,
                                ]];
                            } else {
                                 return $commonTerminalData;
                            }
                        }),
                        'owner' => true,
                        'ongoing' => true,
                        'returning' => false,
                        'canEdit' => true,
                        'canCancel' => true,
                        'canResubmit' => false,
                        'canEnterNullLocation' => false,
                        'canRemoveTerminal' => false,
                        'canAddTerminal' => true,
                        'canAddReturn' => false,
                        'statusText' => __('values.delivery_statuses.' . $delivery->status),
                        'paymentSummary' => __('values.payment_types.' . $delivery->deliveryFarePaymentType),
                        'isCommentImportant' => false,
                        'trackingUrl' => route('deliveries.show', $delivery->id),
                        'maskedId' => $delivery->maskedId,
                        'canViewItems' => false,
                        'customerId' => $delivery->customerId,
                        'customerRefId' => $delivery->customerRefId,
                        'scheduling' => true,
                        'reservationDate' => $delivery->startTimeSlot->toIso8601String(),
                        'reservationDateJalali' => $delivery->startTimeSlot->locale('fa')->toDateTimeLocalString(),
                        'reservationDayOfWeek' => $delivery->startTimeSlot->locale('fa')->dayName,
                        'canChangePaymentMode' => true,
                        'canChangePaymentParty' => true,
                        'addMerchandiseInfo' => 'DISABLED',
                        'orderSteps' => [
                            [
                                'title' => 'ثبت سفارش',
                                'date' => $delivery->created_at->toIso8601String(),
                                'status' => 'FINISHED',
                            ],
                            [
                                'title' => 'زمان رزرو شده',
                                'date' => $delivery->startTimeSlot->toIso8601String(),
                                'status' => 'FINISHED',
                            ],
                            [
                                'title' => 'پذیرش راننده',
                                'status' => 'CURRENT',
                            ],
                            [
                                'title' => 'رسیدن به مبدأ',
                                'status' => 'PENDING',
                            ],
                            [
                                'title' => 'دریافت مرسوله',
                                'status' => 'PENDING',
                            ],
                            [
                                'title' => 'رسیدن به مقصد',
                                'status' => 'PENDING',
                            ],
                            [
                                'title' => 'اتمام سفارش',
                                'status' => 'PENDING',
                            ],
                        ],
                        'comments' => [],
                        'inHurryStatus' => 'AVAILABLE',
                        'commentImportant' => false,
                    ]
                ]
            ],
            201,
        );
    }
}
