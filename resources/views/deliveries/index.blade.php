@php
    /** @var \App\Models\Delivery[] $deliveries */
    /** @var int $total */

    $currentPage = request()->get('page', 1);
    $totalPages = (int) ceil($total / 10);
    $currentQuery = request()->get('q', '');
    $currentStatus = request()->get('status', '');

    $hasNextPage = $currentPage < $totalPages;
    $nextPage = !$hasNextPage ? '#' : route('deliveries.index') . '?page=' . ($currentPage + 1);

    $hasPreviousPage = $currentPage > 1;
    $previousPage = !$hasPreviousPage ? '#' : route('deliveries.index') . '?page=' . ($currentPage - 1);
@endphp

@extends('layout')

@section('title', 'لیست سفارش‌ها')

@section('content')
    <div class="bg-white p-8 rounded-lg">
        {{--    Search    --}}
        <form class="flex flex-row justify-center mb-4">
            <input name="q"
                   value="{{ $currentQuery }}"
                   class="bg-gray-200 text-gray-700 rounded-r-lg py-2 px-4" placeholder="شناسه یا شناسه مخفی"
            />
            <select name="status" class="bg-gray-200 text-gray-700 py-2 px-4 border border-2">
                <option value="" @if($currentStatus === '') selected @endif>
                    همه وضعیت ها
                </option>
                <option value="PENDING" @if($currentStatus === 'PENDING') selected @endif>
                    {{ __('values.delivery_statuses.' . 'PENDING') }}
                </option>
                <option value="ACCEPTED" @if($currentStatus === 'ACCEPTED') selected @endif>
                    {{ __('values.delivery_statuses.' . 'ACCEPTED') }}
                </option>
                <option value="ARRIVED_AT_PICK_UP" @if($currentStatus === 'ARRIVED_AT_PICKUP') selected @endif>
                    {{ __('values.delivery_statuses.' . 'ARRIVED_AT_PICKUP') }}
                </option>
                <option value="PICKED_UP" @if($currentStatus === 'PICKED_UP') selected @endif>
                    {{ __('values.delivery_statuses.' . 'PICKED_UP') }}
                </option>
                <option value="ARRIVED_AT_DROP_OFF" @if($currentStatus === 'ARRIVED_AT_DROP_OFF') selected @endif>
                    {{ __('values.delivery_statuses.' . 'ARRIVED_AT_DROP_OFF') }}
                </option>
                <option value="DELIVERED" @if($currentStatus === 'DELIVERED') selected @endif>
                    {{ __('values.delivery_statuses.' . 'DELIVERED') }}
                </option>
                <option value="CANCELLED" @if($currentStatus === 'CANCELLED') selected @endif>
                    {{ __('values.delivery_statuses.' . 'CANCELLED') }}
                </option>
            </select>
            <button type="submit" class="bg-green-500 text-white rounded-l-lg py-2 px-12">جستجو</button>
        </form>

        {{--    Data    --}}
        @if(count($deliveries) === 0)
            <div class="text-center text-gray-500 text-md" dir="ltr">No deliveries found.</div>
        @else
            <table class="w-full text-sm text-left text-gray-500 rounded-lg text-right">
                <thead class="text-lg text-gray-700 uppercase bg-gray-100">
                <tr>
                    <th class="p-4">شناسه</th>
                    <th class="p-4">شناسه مخفی</th>
                    <th class="p-4">شناسه مرجع</th>
                    <th class="p-4">تحویل گیرنده</th>
                    <th class="p-4">وضعیت</th>
                    <th class="p-4">تاریخ ثبت</th>
                    <th class="p-4">آخرین تغییر</th>
                </tr>
                </thead>
                <tbody>
                @foreach($deliveries as $delivery)
                    <tr class="hover:bg-gray-50 hover:cursor-pointer"
                        onclick="location.href = '{{ route('deliveries.show', $delivery) }}'">
                        <td class="p-4">{{ $delivery->id }}</td>
                        <td class="p-4">{{ $delivery->maskedId }}</td>
                        <td class="p-4">{{ $delivery->customerRefId }}</td>
                        <td class="p-4">{{ $delivery->terminals->firstWhere('type', 'drop')->contactName }}</td>
                        <td class="p-4">{{ __('values.delivery_statuses.' . $delivery->status) }}</td>
                        <td class="p-4" dir="ltr">{{ $delivery->created_at->format('Y-m-d H:i:s') }}</td>
                        <td class="p-4" dir="ltr">{{ $delivery->updated_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif


        {{--    Pagination    --}}
        @if(count($deliveries) !== 0)
            <div class="flex justify-center items-center mt-4">
                <a
                    class="text-lg @if($hasPreviousPage) text-gray-900 hover:text-blue-500 @else text-gray-300 @endif"
                    href="{{$previousPage}}"
                >
                    <
                </a>
                <span class="text-sm text-gray-500 px-4">
                    صفحه {{ $currentPage }} از {{ $totalPages }}
                </span>
                <a
                    class="text-lg @if($hasNextPage) text-gray-900 hover:text-blue-500 @else text-gray-300 @endif"
                    href="{{ $nextPage }}"
                >
                    >
                </a>
            </div>
        @endif
    </div>
@endsection
