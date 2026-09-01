<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting…</title>
    <style>
        body {
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            font-family: sans-serif;
            color: #333333;
        }
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(0, 0, 0, 0.08);
            border-top-color: #ff6b00;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div class="spinner"></div>

    <script>
        const trackBase = "{{ url('/qr/' . $type) }}";

        function skip() {
            window.location.replace(trackBase + '?skip=1');
        }

        if (navigator.permissions && navigator.geolocation) {
            navigator.permissions.query({ name: 'geolocation' }).then(function(permissionStatus) {
                if (permissionStatus.state === 'granted') {
                    navigator.geolocation.getCurrentPosition(
                        function(pos) {
                            const lat = pos.coords.latitude.toFixed(6);
                            const lng = pos.coords.longitude.toFixed(6);
                            window.location.replace(trackBase + '?lat=' + lat + '&lng=' + lng);
                        },
                        function() {
                            skip();
                        },
                        { timeout: 3000, enableHighAccuracy: false }
                    );
                } else {
                    skip();
                }
            }).catch(function() {
                skip();
            });
        } else {
            skip();
        }
    </script>
</body>
</html>
