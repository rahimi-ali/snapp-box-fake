@extends('layout')

@section('title', 'Webhook Details')

@section('head')
    <script>
        function deleteWebhook() {
            if (confirm('Are you sure you want to delete this webhook?')) {
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
@endsection

@section('content')
    <div class="bg-white p-8 rounded-lg w-full" style="max-width: 640px" dir="ltr">
        <h1 class="text-3xl text-center mb-4">Webhook Details</h1>

        <div class="text-md text-gray-600">URL</div>
        <div class="text-sm text-gray-500 mb-4">{{ $webhook->url }}</div>

        <div>
            <h2 class="text-xl">Headers</h2>
            @if(count($webhook->headers) === 0)
                <div class="text-gray-500 text-center">No headers set.</div>
            @else
                @foreach($webhook->headers as $header)
                    <div class="border border-gray-200 p-2 my-2 rounded">
                        <span class="text-md text-gray-600">{{ $header->name }}: </span>
                        <span class="text-sm text-gray-500 block overflow-x-scroll">{{ $header->value }}</span>
                    </div>
                @endforeach
            @endif

        </div>

        <div class="text-center">
            <button class="bg-red-500 text-sm text-white rounded py-1 px-2" onclick="deleteWebhook()">Delete</button>
            <form id="deleteForm" method="POST" action="{{ route('webhooks.destroy', $webhook) }}" class="hidden">
                @csrf
                @method('delete')
            </form>
        </div>
    </div>
@endsection
