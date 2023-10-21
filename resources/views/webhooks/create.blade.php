@extends('layout')

@section('title', 'Create Webhook')

@section('head')
    <script>
        const headerSectionTemplate = `
        <div class="mb-4 border border-gray-200 p-2 rounded">
            <input
                name="headers[]"
                class="bg-gray-200 text-gray-700 rounded py-2 px-4 w-full mb-1"
                placeholder="Header Key"
                required
            />
            <input
                name="headerValues[]"
                class="bg-gray-200 text-gray-700 rounded py-2 px-4 w-full mb-1"
                placeholder="Header Value"
                required
            />
            <div class="text-end">
                <button onclick="removeHeader(event)" type="button" class="bg-red-500 text-xs text-white rounded py-1 px-2">
                    Remove
                </button>
            </div>
        </div>
        `;

        function addHeader(e) {
            e.preventDefault();
            const headersSection = document.getElementById('headers');
            const div = document.createElement('div');
            div.innerHTML = headerSectionTemplate;
            headersSection.appendChild(div);
        }

        function removeHeader(e) {
            e.preventDefault();
            e.target.parentElement.parentElement.remove();
        }
    </script>
@endsection

@section('content')
    <form
        method="POST"
        action="{{ route('webhooks.store') }}"
        class="bg-white p-8 rounded-lg w-full"
        style="max-width: 640px"
        dir="ltr"
    >
        <h1 class="text-3xl text-center mb-4">Create Webhook</h1>

        @csrf

        <input
            type="url"
            name="url"
            class="bg-gray-200 text-gray-700 rounded py-2 px-4 w-full mb-4"
            placeholder="URL"
            required
        />

        <div>
            <div id="headers" class="p-2"></div>

            <div class="text-end">
                <button onclick="addHeader(event)" type="button" class="bg-green-500 text-sm text-white rounded py-1 px-2">
                    Add Header
                </button>
            </div>
        </div>


        <button type="submit" class="bg-green-500 text-white rounded-lg py-2 px-12">Create</button>
    </form>
@endsection
