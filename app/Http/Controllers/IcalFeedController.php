<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IcalFeedController extends Controller
{
    public function feed(Request $request)
    {
        $token = $request->query('token');
        if (!$token) abort(403);

        // Token = base64(user_id:hmac)
        $decoded = base64_decode($token);
        $parts = explode(':', $decoded, 2);
        if (count($parts) !== 2) abort(403);

        [$userId, $hmac] = $parts;
        $expectedHmac = hash_hmac('sha256', $userId, config('app.key'));

        if (!hash_equals($expectedHmac, $hmac)) abort(403);

        $user = User::find($userId);
        if (!$user) abort(404);

        $events = $this->getEventsForUser($user);

        $ical = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//CICA-GPRO//Calendar//FR\r\nX-WR-CALNAME:GPRO - {$user->name}\r\nMETHOD:PUBLISH\r\n";

        foreach ($events as $event) {
            $dtStart = Carbon::parse($event['start'])->format('Ymd');
            $dtEnd = Carbon::parse($event['end'] ?? $event['start'])->addDay()->format('Ymd');
            $summary = str_replace(["\r", "\n", ",", ";"], [' ', ' ', '\,', '\;'], $event['title']);

            $ical .= "BEGIN:VEVENT\r\n";
            $ical .= "UID:{$event['id']}@gpro\r\n";
            $ical .= "DTSTART;VALUE=DATE:{$dtStart}\r\n";
            $ical .= "DTEND;VALUE=DATE:{$dtEnd}\r\n";
            $ical .= "SUMMARY:{$summary}\r\n";
            $ical .= "END:VEVENT\r\n";
        }

        $ical .= "END:VCALENDAR\r\n";

        return response($ical, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'inline; filename="gpro-calendar.ics"',
        ]);
    }

    public static function generateToken(User $user): string
    {
        $hmac = hash_hmac('sha256', $user->id, config('app.key'));
        return base64_encode($user->id . ':' . $hmac);
    }

    public static function getFeedUrl(User $user): string
    {
        $token = self::generateToken($user);
        return route('ical.feed', ['token' => $token]);
    }

    protected function getEventsForUser(User $user): array
    {
        $query = Activity::query()
            ->whereNotNull('start_date')
            ->with('result.specificObjective.logicalFramework.project:id,title');

        if ($user->organization_id) {
            $query->where('organization_id', $user->organization_id);
        } else {
            $query->where('creator_user_id', $user->id);
        }

        return $query->get()->map(fn ($a) => [
            'id' => $a->id,
            'title' => strip_tags($a->description ?? ''),
            'start' => $a->start_date?->format('Y-m-d'),
            'end' => $a->end_date?->format('Y-m-d'),
        ])->toArray();
    }
}
