<?php

use App\Models\Agenda;
use App\Models\Cita;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Livewire\Volt\Component;

new class extends Component {
    public $events = [];

    public function mount(): void
    {
        $this->loadEvents();
    }

    public function loadEvents(): void
    {

        $this->events = Cita::with(['paciente'])
            ->get()
            ->map(function ($cita) {
                return [
                    'id' => $cita->id,
                    'title' => $cita->nombre_examen . ' - ' . $cita->paciente->nombreCompleto,
                    'start' => $cita->fecha_cita . ' ' . $cita->hora_cita,
                    'end' => Carbon::parse($cita->hora_cita)->addMinutes(30)->toDateTimeString(),
                ];
            })
            ->toArray();

//        dd($this->events);
    }

}; ?>
<div>
    <div id="calendar" class="w-full " wire:ignore></div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <script>
        document.addEventListener('livewire:initialized', () => {

            const calendarEl = document.getElementById('calendar');
            const events = @json($events);
            const calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'es',
                hiddenDays: [0],

                titleFormat: {
                    month: 'long',
                    day: 'numeric',
                    weekday: 'long'
                },
                dayHeaderFormat: {
                    weekday: 'long',
                    day: 'numeric',
                },
                // Convertir la primera letra a mayúscula
                dayHeaderContent: function (arg) {
                    const text = arg.text.charAt(0).toUpperCase() + arg.text.slice(1);
                    return {
                        html: text
                    };
                },
                allDayText: 'Todo el día',
                slotMinTime: '07:00',
                slotMaxTime: '17:00',
                initialView: 'timeGridWeek',
                slotDuration: '00:30:00',
                slotLabelInterval: '00:30:00',
                slotLabelFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    omitZeroMinute: false
                },
                events: events,
                displayEventTime: false,
            });

            calendar.render();
        });
    </script>
</div>
