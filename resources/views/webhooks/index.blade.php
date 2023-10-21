@php
    /** @var \App\Models\WebhookSubscriber[] $webhooks */
@endphp

@extends('layout')

@section('title', 'Webhooks')

@section('content')
    <div class="bg-white p-8 rounded-lg" dir="ltr" style="width: 800px">
        <h1 class="text-3xl text-center mb-4">
            Webhooks
        </h1>

        @if(count($webhooks) === 0)
            <div class="text-center text-gray-500">
                No webhooks found.
            </div>
        @else
            <table class="w-full text-sm text-left text-gray-500 rounded-lg">
                <thead class="text-md text-gray-700 bg-gray-100">
                    <tr>
                        <th class="p-4">ID</th>
                        <th class="p-4">URL</th>
                        <th class="p-4">Registered On</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($webhooks as $webhook)
                    <tr class="hover:bg-gray-50 hover:cursor-pointer"
                        onclick="location.href = '{{ route('webhooks.show', $webhook) }}'">
                        <td class="p-4">{{ $webhook->id }}</td>
                        <td class="p-4">{{ $webhook->url }}</td>
                        <td class="p-4">{{ $webhook->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <a
        class="fixed bottom-5 right-5 bg-green-500 text-white text-4xl py-0 pb-1 px-2 rounded"
        href="{{ route('webhooks.create') }}"
    >
        +
    </a>
@endsection
