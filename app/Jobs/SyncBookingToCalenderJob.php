<?php

namespace App\Jobs;

use App\Http\Services\MicrosoftGraphService;
use App\Models\Booking;
use App\Models\Settings\GeneralSetting;
use App\Models\User;
use Carbon\Carbon;
use DateTimeZone;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use JetBrains\PhpStorm\ArrayShape;
use Log;
use Microsoft\Graph\Exception\GraphException;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

class SyncBookingToCalenderJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private Booking $booking;
    private Graph $graph;
    private DateTimeZone $timeZone;
    private User $user;
    private string $companyName;

    public function __construct(Booking $booking, User $user)
    {
        $this->booking = $booking;
        $this->user = $user;
        $graphService = new MicrosoftGraphService();
        $this->graph = $graphService->getGraph($booking->user);

        $this->timeZone = app(GeneralSetting::class)->default_timezone;
        $this->companyName = app(GeneralSetting::class)->company_name;
    }

    public function handle()
    {
        try {
            $events = $this->getEvents();
            $bookingEvents = $this->formatBookingEvents();
            $eventExist = $this->checkEventExist($events);
            if (! $eventExist) {
                $this->createEvent($bookingEvents);
            }
        } catch (GuzzleException|GraphException|Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * @throws GuzzleException
     * @throws GraphException
     */
    private function getEvents()
    {
        $queryParams = [
            'startDateTime' => $this->booking->package->depart_time->subDays()->toIso8601String(),
            'endDateTime' => $this->booking->package->depart_time->addDays()->toIso8601String(),
            '$select' => 'subject,organizer,start,end',
            '$orderby' => 'start/dateTime',
            '$top' => 50,
        ];

        $getEventsUrl = '/me/calendarView?' . http_build_query($queryParams);

        return $this->graph->createRequest('GET', $getEventsUrl)
            ->addHeaders([
                'Prefer' => 'outlook.timezone="' . $this->timeZone->getName() . '"',
            ])
            ->setReturnType(Model\Event::class)
            ->execute();
    }

    #[ArrayShape(['subject' => "", 'attendees' => "", 'start' => "array", 'end' => "array", 'body' => "string[]"])]
    private function formatBookingEvents(): array
    {
        $booking = $this->booking;
        return [
            'subject' => $booking->package->tour->name,
            'attendees' => [[
                'emailAddress' => [
                    'address' => $this->user->email,
                ],
                'type' => 'required',
            ]],
            'start' => [
                'dateTime' => $booking->package->depart_time->toIso8601String(),
                'timeZone' => 'Asia/Kuala_Lumpur',
            ],
            'end' => [
                'dateTime' => $booking->package->depart_time->addDays($this->booking->package->tour->days)->toIso8601String(),
                'timeZone' => 'Asia/Kuala_Lumpur',
            ],
            'body' => [
                'content' =>
                    "Hi," . $this->user->name . "," .
                    "You have a booking for " . $booking->package->tour->name . " on " . $booking->package->depart_time->format('d/m/Y') . "." .
                    "Please contact us if you have any questions." .
                    "Regards,\n" .
                    $this->companyName,
                'contentType' => 'text',
            ],
        ];
    }

    private function checkEventExist(mixed $events): bool
    {
        $isEventCreatedBefore = false;
        foreach ($events as $event) {
            $eventStart = Carbon::parse($event->getStart()->getDateTime());
            $eventEnd = Carbon::parse($event->getEnd()->getDateTime());

            $scheduleStart = $this->booking->package->depart_time;
            $scheduleEnd = $this->booking->package->depart_time->addDays($this->booking->package->tour->days);

            if ($eventStart->isSameDay($scheduleStart) && $eventEnd->isSameDay($scheduleEnd)) {
                $isEventCreatedBefore = true;

                break;
            }
        }

        return $isEventCreatedBefore;
    }

    /**
     * @throws GuzzleException
     * @throws GraphException
     */
    private function createEvent(array $bookingEvents)
    {
        return $this->graph->createRequest('POST', '/me/events')
            ->attachBody($bookingEvents)
            ->setReturnType(Model\Event::class)
            ->execute();
    }
}
