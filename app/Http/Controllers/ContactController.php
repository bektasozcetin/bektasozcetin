<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\NewContactMessage;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function __invoke(ContactRequest $request): JsonResponse|RedirectResponse
    {
        $message = ContactMessage::create([
            ...$request->safe()->only(['name', 'email', 'project_type', 'message']),
            'locale' => app()->getLocale(),
            'ip' => $request->ip(),
        ]);

        // Mesaj veritabanına kaydedildi; e-posta gönderilemese bile kaybolmaz.
        if ($to = config('site.notify_email')) {
            try {
                Mail::to($to)->send(new NewContactMessage($message));
            } catch (\Throwable $e) {
                Log::warning('İletişim bildirimi gönderilemedi', ['id' => $message->id, 'error' => $e->getMessage()]);
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->to(localized_route('home').'#iletisim')->with('contact_sent', true);
    }
}
