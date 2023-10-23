@php
    /** @var \App\Models\Delivery $delivery */

    $pickup = $delivery->terminals->firstWhere('type', 'pickup');
    $drop = $delivery->terminals->firstWhere('type', 'drop');
@endphp

@extends('layout')

@section('title', 'جزییات سفارش')

@section('content')
    <div class="bg-white p-8 rounded-lg w-full my-4" style="max-width: 1080px">
        <h2 class="mb-4 text-2xl">اطلاعات کلی</h2>
        <div class="grid grid-cols-4 gap-4 w-full">
            <div>
                <span class="text-gray-900">شناسه: </span>
                <span class="text-sm text-gray-500">{{ $delivery->id }}</span>
            </div>
            <div class="col-span-2">
                <span class="text-gray-900">شناسه مخفی: </span>
                <span class="text-sm text-gray-500">{{ $delivery->maskedId }}</span>
            </div>
            <div>
                <span class="text-gray-900">شناسه مرجع: </span>
                <span class="text-sm text-gray-500">{{ $delivery->customerRefId }}</span>
            </div>

            <div>
                <span class="text-gray-900">وضعیت: </span>
                <span class="text-sm text-gray-500">{{ __('values.delivery_statuses.' . $delivery->status) }}</span>
            </div>
            <div>
                <span class="text-gray-900">ساخته شده در: </span>
                <span class="text-sm text-gray-500" dir="ltr">{{ $delivery->created_at->format('Y-m-d H:i:s') }}</span>
            </div>
            <div>
                <span class="text-gray-900">آخرین تغییر: </span>
                <span class="text-sm text-gray-500" dir="ltr">{{ $delivery->updated_at->format('Y-m-d H:i:s') }}</span>
            </div>
            <div>
                <span class="text-gray-900">شناسه مشتری: </span>
                <span class="text-sm text-gray-500" dir="ltr">{{ $delivery->customerId }}</span>
            </div>

            <div>
                <span class="text-gray-900">نوع: </span>
                <span class="text-sm text-gray-500" dir="ltr">{{ $delivery->deliveryCategory }}</span>
            </div>
            <div>
                <span class="text-gray-900">نوع پرداخت: </span>
                <span class="text-sm text-gray-500" dir="ltr">{{ $delivery->deliveryFarePaymentType }}</span>
            </div>
            <div>
                <span class="text-gray-900">هزینه ارسال: </span>
                <span class="text-sm text-gray-500" dir="ltr">{{ $delivery->deliveryFare }}</span>
            </div>
            <div>
                <span class="text-gray-900">قابل بچ: </span>
                <span class="text-sm text-gray-500" dir="ltr">{{ $delivery->batchable ? 'YES' : 'NO' }}</span>
            </div>

            <div>
                <span class="text-gray-900">شناسه راننده: </span>
                <span class="text-sm text-gray-500" dir="ltr">{{ $delivery->bikerId ?? 'تعیین نشده' }}</span>
            </div>
            <div>
                <span class="text-gray-900">اسم راننده: </span>
                <span class="text-sm text-gray-500" dir="ltr">{{ $delivery->bikerName ?? 'تعیین نشده' }}</span>
            </div>
            <div>
                <span class="text-gray-900">شماره راننده: </span>
                <span class="text-sm text-gray-500" dir="ltr">{{ $delivery->bikerPhoneNumber ?? 'تعیین نشده'}}</span>
            </div>
            <div>
                <span class="text-gray-900">عکس راننده: </span>
                <span class="text-sm text-gray-500" dir="ltr">
                    @if($delivery->bikerPhotoUrl)
                        <a href="{{ $delivery->bikerPhotoUrl }}" class="hover:text-blue-500">لینک</a>
                    @else
                        تعیین نشده
                    @endif
                </span>
            </div>

            <div>
                <span class="text-gray-900">شناسه فروشگاه پرداخت: </span>
                <span class="text-sm text-gray-500" dir="ltr">{{ $delivery->merchandiseStoreId ?? 'پرداخت ندارد' }}</span>
            </div>
            <div>
                <span class="text-gray-900">مبلع پرداخت هزینه سفارش: </span>
                <span class="text-sm text-gray-500" dir="ltr">{{ $delivery->merchandiseCost ?? 'پرداخت ندارد' }}</span>
            </div>
            <div>
                <span class="text-gray-900">وضعیت پرداخت هزینه سفارش: </span>
                <span class="text-sm text-gray-500" dir="ltr">{{ $delivery->invoiceStatus ?? 'پرداخت ندارد' }}</span>
            </div>
            <div>
                <span class="text-gray-900">توضیح پرداخت سفارش: </span>
                <span class="text-sm text-gray-500" dir="ltr">{{ $delivery->merchandiseDescription ?? 'پرداخت ندارد' }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white p-8 rounded-lg w-full my-4" style="max-width: 1080px">
        <h2 class="mb-4 text-2xl">عملیات ها</h2>
        <div class="grid grid-cols-4 gap-4 w-full">
            @if($delivery->status === 'PENDING')
                <form method="POST" action="{{ route('deliveries.update-status', $delivery) }}">
                    @csrf
                    <input name="status" type="hidden" value="ACCEPTED"/>
                    <button type="submit" class="bg-green-500 text-white rounded-lg py-2 px-12">قبول راننده</button>
                </form>
            @endif

            @if(in_array($delivery->status, ['ACCEPTED', 'ARRIVED_AT_PICK_UP']))
                <form method="POST" action="{{ route('deliveries.update-status', $delivery) }}">
                    @csrf
                    <input name="status" type="hidden" value="PENDING"/>
                    <button type="submit" class="bg-orange-500 text-white rounded-lg py-2 px-12">کنسل راننده</button>
                </form>
            @endif

            @if($delivery->status === 'ACCEPTED')
                <form method="POST" action="{{ route('deliveries.update-status', $delivery) }}">
                    @csrf
                    <input name="status" type="hidden" value="ARRIVED_AT_PICKUP"/>
                    <button type="submit" class="bg-blue-500 text-white rounded-lg py-2 px-12">رسیدن به مبدا</button>
                </form>
            @endif

            @if($delivery->status === 'ARRIVED_AT_PICKUP')
                <form method="POST" action="{{ route('deliveries.update-status', $delivery) }}">
                    @csrf
                    <input name="status" type="hidden" value="PICKED_UP"/>
                    <button type="submit" class="bg-blue-500 text-white rounded-lg py-2 px-12">گرفتن مرسوله</button>
                </form>
            @endif

            @if($delivery->status === 'PICKED_UP')
                <form method="POST" action="{{ route('deliveries.update-status', $delivery) }}">
                    @csrf
                    <input name="status" type="hidden" value="ARRIVED_AT_DROP_OFF"/>
                    <button type="submit" class="bg-blue-500 text-white rounded-lg py-2 px-12">رسیدن به مقصد</button>
                </form>
            @endif

            @if($delivery->status === 'ARRIVED_AT_DROP_OFF')
                <form method="POST" action="{{ route('deliveries.update-status', $delivery) }}">
                    @csrf
                    <input name="status" type="hidden" value="DELIVERED"/>
                    <button type="submit" class="bg-blue-500 text-white rounded-lg py-2 px-12">تحویل دادن</button>
                </form>
            @endif

            @if(in_array($delivery->status, ['ACCEPTED', 'ARRIVED_AT_PICK_UP']))
                <form method="POST" action="{{ route('deliveries.update-status', $delivery) }}">
                    @csrf
                    <input name="status" type="hidden" value="CANCELLED"/>
                    <button type="submit" class="bg-green-500 text-white rounded-lg py-2 px-12">کنسل سفارش</button>
                </form>
            @endif

            @if($delivery->invoiceStatus === 'PENDING')
                <form method="POST" action="{{ route('deliveries.pay-invoice', $delivery) }}">
                    @csrf
                    <button type="submit" class="bg-green-500 text-white rounded-lg py-2 px-12">پرداخت موفق</button>
                </form>
            @endif
        </div>
    </div>

    <div class="bg-white p-8 rounded-lg w-full my-4" style="max-width: 1080px">
        <h2 class="mb-4 text-2xl">اطلاعات مبدا</h2>
        <div class="grid grid-cols-4 gap-4 w-full">
            <div>
                <span class="text-gray-900">نام: </span>
                <span class="text-sm text-gray-500">{{ $pickup->contactName }}</span>
            </div>
            <div>
                <span class="text-gray-900">شماره تلفن: </span>
                <span class="text-sm text-gray-500" dir="ltr">{{ $pickup->contactPhoneNumber }}</span>
            </div>
            <div>
                <span class="text-gray-900">عرض: </span>
                <span class="text-sm text-gray-500" dir="ltr">{{ $pickup->latitude }}</span>
            </div>
            <div>
                <span class="text-gray-900">طول: </span>
                <span class="text-sm text-gray-500" dir="ltr">{{ $pickup->longitude }}</span>
            </div>

            <div class="col-span-4">
                <span class="text-gray-900">آدرس: </span>
                <span class="text-sm text-gray-500" dir="ltr">{{ $pickup->address }}</span>
            </div>

            <div class="col-span-4">
                <span class="text-gray-900">کامنت: </span>
                <span class="text-sm text-gray-500" dir="ltr">{{ $pickup->comment ?: '' }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white p-8 rounded-lg w-full my-4" style="max-width: 1080px">
        <h2 class="mb-4 text-2xl">اطلاعات مقصد</h2>
        <div class="grid grid-cols-4 gap-4 w-full">
            <div>
                <span class="text-gray-900">نام: </span>
                <span class="text-sm text-gray-500">{{ $drop->contactName }}</span>
            </div>
            <div>
                <span class="text-gray-900">شماره تلفن: </span>
                <span class="text-sm text-gray-500" dir="ltr">{{ $drop->contactPhoneNumber }}</span>
            </div>
            <div>
                <span class="text-gray-900">عرض: </span>
                <span class="text-sm text-gray-500" dir="ltr">{{ $drop->latitude }}</span>
            </div>
            <div>
                <span class="text-gray-900">طول: </span>
                <span class="text-sm text-gray-500" dir="ltr">{{ $drop->longitude }}</span>
            </div>

            <div class="col-span-4">
                <span class="text-gray-900">آدرس: </span>
                <span class="text-sm text-gray-500" dir="ltr">{{ $drop->address }}</span>
            </div>

            <div class="col-span-4">
                <span class="text-gray-900">کامنت: </span>
                <span class="text-sm text-gray-500" dir="ltr">{{ $drop->comment ?: '' }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white p-8 rounded-lg w-full my-4" style="max-width: 1080px">
        <h2 class="mb-4 text-2xl">تغییرات</h2>
        <div class="grid grid-cols-2 gap-4 w-full">
            @foreach($delivery->statusHistories as $statusHistory)
                <div>
                    <span
                        class="text-sm text-gray-600">{{ __('values.delivery_statuses.' . $statusHistory->status) }}</span>
                </div>
                <div>
                    <span class="text-sm text-gray-600"
                          dir="ltr">{{ $statusHistory->created_at->format('Y-m-d H:i:s') }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <a href="{{ route('deliveries.index') }}" class="fixed top-5 right-5 p-2 bg-gray-700 rounded-full">
        <svg fill="#FFFFFF" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink"
             width="30px" height="30px" viewBox="0 0 495.398 495.398"
             xml:space="preserve"
        >
            <g>
                <g>
                    <g>
                        <path d="M487.083,225.514l-75.08-75.08V63.704c0-15.682-12.708-28.391-28.413-28.391c-15.669,0-28.377,12.709-28.377,28.391
                            v29.941L299.31,37.74c-27.639-27.624-75.694-27.575-103.27,0.05L8.312,225.514c-11.082,11.104-11.082,29.071,0,40.158
                            c11.087,11.101,29.089,11.101,40.172,0l187.71-187.729c6.115-6.083,16.893-6.083,22.976-0.018l187.742,187.747
                            c5.567,5.551,12.825,8.312,20.081,8.312c7.271,0,14.541-2.764,20.091-8.312C498.17,254.586,498.17,236.619,487.083,225.514z"/>
                        <path d="M257.561,131.836c-5.454-5.451-14.285-5.451-19.723,0L72.712,296.913c-2.607,2.606-4.085,6.164-4.085,9.877v120.401
                            c0,28.253,22.908,51.16,51.16,51.16h81.754v-126.61h92.299v126.61h81.755c28.251,0,51.159-22.907,51.159-51.159V306.79
                            c0-3.713-1.465-7.271-4.085-9.877L257.561,131.836z"/>
                    </g>
                </g>
            </g>
        </svg>
    </a>
@endsection
