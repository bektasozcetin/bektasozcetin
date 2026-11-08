<?php

namespace App\Http\Controllers;

use App\Http\Requests\MeetingRequest;
use App\Mail\NewMeeting;
use App\Models\Meeting;
use App\Support\BookingCalendar;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MeetingController extends Controller
{
    public function slots(): JsonResponse
    {
        abort_unless(booking_mode() === 'internal', 404);

        return response()->json([
            'duration' => (int) setting('booking_duration', 30),
            'days' => BookingCalendar::days(),
        ]);
    }

    public function store(MeetingRequest $request): JsonResponse
    {
        abort_unless(booking_mode() === 'internal', 404);
        $data = $request->validated();

        // Aynı saate aynı anda iki talep gelirse yalnızca biri kaydedilir.
        $meeting = Cache::lock('booking-'.$data['date'].'-'.$data['time'], 5)->block(3, function () use ($data, $request) {
            if (! BookingCalendar::isAvailable($data['date'], $data['time'])) {
                return null;
            }

            return Meeting::create([
                'starts_at' => CarbonImmutable::createFromFormat('Y-m-d H:i', $data['date'].' '.$data['time'], BookingCalendar::TZ)->setTimezone(config('app.timezone')),
                'duration' => (int) setting('booking_duration', 30),
                'name' => $data['name'],
                'email' => $data['email'],
                'topic' => $data['topic'] ?? null,
                'note' => $data['note'] ?? null,
                'locale' => app()->getLocale(),
                'ip' => $request->ip(),
            ]);
        });

        if (! $meeting) {
            return response()->json(['errors' => ['slot' => [__('site.booking.errors.taken')]]], 422);
        }

        if ($to = config('site.notify_email')) {
            try {
                Mail::to($to)->send(new NewMeeting($meeting));
            } catch (\Throwable $e) {
                Log::warning('Görüşme bildirimi gönderilemedi', ['id' => $meeting->id, 'error' => $e->getMessage()]);
            }
        }

        return response()->json(['ok' => true]);
    }
}
