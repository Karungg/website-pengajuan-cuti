<x-filament::page>
    <div class="container">
        <div class="border-left">
            @foreach ($logs as $item)
                <div class="event">
                    <div class="event-row">
                        <div>
                            <h3 class="event-title">{{ $item->status }}</h3>
                            <p class="event-date">Tanggal : {{ $item->created_at }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <style>
        .container {
            max-width: 1200px;
            margin: 32px auto;
            padding: 0 16px;
        }

        .border-left {
            border-left: 2px solid #6b7280;
            padding-left: 32px;
        }

        .event {
            display: flex;
            flex-direction: column;
            margin-bottom: 32px;
        }

        .event-row {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        .event-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .event-date {
            color: #4b5563;
            font-size: 0.875rem;
        }

        .event-description {
            color: #374151;
            font-size: 1rem;
        }
    </style>

</x-filament::page>
