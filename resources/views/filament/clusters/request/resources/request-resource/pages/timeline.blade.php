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
            /* Equivalent to mt-8 */
            padding: 0 16px;
            /* Adds some padding on the sides */
        }

        .border-left {
            border-left: 2px solid #6b7280;
            /* Tailwind's gray-500 */
            padding-left: 32px;
            /* Equivalent to pl-8 */
        }

        .event {
            display: flex;
            flex-direction: column;
            margin-bottom: 32px;
            /* Equivalent to mt-8 */
        }

        .event-row {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        .event-title {
            font-size: 1.25rem;
            /* Equivalent to text-xl */
            font-weight: bold;
            margin-bottom: 8px;
            /* Equivalent to mb-2 */
        }

        .event-date {
            color: #4b5563;
            /* Tailwind's gray-600 */
            font-size: 0.875rem;
            /* Equivalent to text-sm */
        }

        .event-description {
            color: #374151;
            /* Tailwind's gray-700 */
            font-size: 1rem;
            /* Default font size */
        }
    </style>

</x-filament::page>
