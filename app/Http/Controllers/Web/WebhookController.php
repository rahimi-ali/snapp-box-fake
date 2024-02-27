<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\WebhookSubscriber;
use App\Models\WebhookSubscriberHeader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WebhookController extends Controller
{
    public function index(): View
    {
        if (!Auth::user()->can_view_webhooks && !Auth::user()->can_manage_webhooks) {
            abort(403);
        }

        $webhooks = WebhookSubscriber::query()->get();

        return view('webhooks.index', compact('webhooks'));
    }

    public function create(): View
    {
        if (!Auth::user()->can_manage_webhooks) {
            abort(403);
        }

        return view('webhooks.create');
    }

    public function store(Request $request): RedirectResponse
    {
        if (!Auth::user()->can_manage_webhooks) {
            abort(403);
        }

        $url = $request->get('url');
        $headers = array_combine(
            $request->get('headers', []),
            $request->get('headerValues', [])
        );

        $subscriber = WebhookSubscriber::create([
            'url' => $url
        ]);

        foreach ($headers as $header => $value) {
            WebhookSubscriberHeader::create(
                ['webhook_subscriber_id' => $subscriber->id, 'name' => $header, 'value' => $value]
            );
        }

        return redirect(route('webhooks.index'));
    }

    public function show(WebhookSubscriber $webhook): View
    {
        if (!Auth::user()->can_manage_webhooks) {
            abort(403);
        }

        return view('webhooks.show', compact('webhook'));
    }

    public function destroy(WebhookSubscriber $webhook): RedirectResponse
    {
        if (!Auth::user()->can_manage_webhooks) {
            abort(403);
        }

        $webhook->delete();

        return redirect(route('webhooks.index'));
    }
}
