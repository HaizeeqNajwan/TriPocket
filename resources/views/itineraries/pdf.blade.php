<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $itinerary->title }} - PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            padding: 30px;
        }
        h1 {
            color: #e91e63;
        }
        .section {
            margin-bottom: 20px;
        }
        .activities {
            margin-top: 10px;
            padding-left: 20px;
        }
        ul {
            margin-top: 0;
            padding-left: 20px;
        }
        li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <h1>{{ $itinerary->title }}</h1>

    <div class="section">
        <p><strong>Location:</strong> {{ $itinerary->location }}</p>
        <p><strong>Theme:</strong> {{ ucfirst($itinerary->theme) }}</p>
        <p><strong>Created at:</strong> {{ $itinerary->created_at->format('d M Y') }}</p>
    </div>

    <hr>

    <h3>Activities / Itinerary Plan</h3>

    @if (is_array($itinerary->days))
        @foreach($itinerary->days as $dayEntry)
            <div class="day">
                <p><strong>{{ $dayEntry['day'] ?? 'Day' }}:</strong></p>
                <ul>
                    @foreach($dayEntry['activities'] ?? [] as $activity)
                        <li>{{ $activity['time'] ?? '' }} – {{ $activity['name'] ?? '' }}</li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    @else
        <p>No activities listed.</p>
    @endif
</body>
</html>