<!DOCTYPE html>
<html>
<head>
    <title>Pusher Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
    <style>
        body { font-family: Arial; padding: 20px; }
        .status { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .connected { background: #d4edda; color: #155724; }
        .disconnected { background: #f8d7da; color: #721c24; }
        .message { background: #d1ecf1; color: #0c5460; padding: 10px; margin: 5px 0; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Pusher Real-Time Test</h1>
    
    <div id="status" class="status disconnected">Connecting to Pusher...</div>
    
    <h2>Connection Details:</h2>
    <pre id="connection-info"></pre>
    
    <h2>Test Messages:</h2>
    <button onclick="sendTestMessage()">Send Test Message</button>
    <div id="messages"></div>

    <script>
        // Initialize Echo with Pusher
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '14d65f689c4c081b8c19',
            cluster: 'eu',
            forceTLS: true,
            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }
        });

        // Monitor connection status
        Echo.connector.pusher.connection.bind('connected', function() {
            document.getElementById('status').className = 'status connected';
            document.getElementById('status').textContent = '✅ Connected to Pusher!';
            
            document.getElementById('connection-info').textContent = JSON.stringify({
                state: Echo.connector.pusher.connection.state,
                socketId: Echo.connector.pusher.connection.socket_id,
                cluster: 'eu',
                key: '14d65f689c4c081b8c19'
            }, null, 2);
        });

        Echo.connector.pusher.connection.bind('disconnected', function() {
            document.getElementById('status').className = 'status disconnected';
            document.getElementById('status').textContent = '❌ Disconnected from Pusher';
        });

        Echo.connector.pusher.connection.bind('error', function(err) {
            console.error('Pusher error:', err);
            document.getElementById('status').className = 'status disconnected';
            document.getElementById('status').textContent = '❌ Error: ' + err.error.data.message;
        });

        // Subscribe to private channel
        @auth
        Echo.private('chat.{{ Auth::id() }}')
            .listen('MessageSent', (e) => {
                const messageDiv = document.createElement('div');
                messageDiv.className = 'message';
                messageDiv.textContent = `New message from ${e.from_user.name}: ${e.message}`;
                document.getElementById('messages').appendChild(messageDiv);
            })
            .error((error) => {
                console.error('Channel subscription error:', error);
            });
        @endauth

        function sendTestMessage() {
            fetch('/messages/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    to_user_id: {{ Auth::id() ?? 1 }}, // Send to self for testing
                    message: 'Test message at ' + new Date().toLocaleTimeString()
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Message sent:', data);
                alert('Test message sent! Check console for details.');
            })
            .catch(error => {
                console.error('Error sending message:', error);
                alert('Error sending message. Check console.');
            });
        }
    </script>
</body>
</html>