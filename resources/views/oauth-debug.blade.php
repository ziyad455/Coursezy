<!DOCTYPE html>
<html>
<head>
    <title>OAuth Debug Info</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1a1a1a; color: #fff; }
        .container { max-width: 1200px; margin: 0 auto; }
        .info-box { background: #2a2a2a; padding: 20px; margin: 20px 0; border-radius: 5px; }
        .label { color: #4a9eff; font-weight: bold; }
        .value { color: #4ade80; word-break: break-all; }
        .warning { color: #fb923c; }
        .error { color: #f87171; }
        .success { color: #4ade80; }
        pre { background: #000; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 OAuth Configuration Debug</h1>
        
        <div class="info-box">
            <h2>Environment Variables</h2>
            <p><span class="label">APP_URL:</span> <span class="value">{{ env('APP_URL') }}</span></p>
            <p><span class="label">GOOGLE_CLIENT_ID:</span> <span class="value">{{ env('GOOGLE_CLIENT_ID') }}</span></p>
            <p><span class="label">GOOGLE_CLIENT_SECRET:</span> <span class="value">{{ substr(env('GOOGLE_CLIENT_SECRET'), 0, 10) }}...</span></p>
            <p><span class="label">GOOGLE_REDIRECT_URI (env):</span> <span class="value">{{ env('GOOGLE_REDIRECT_URI') }}</span></p>
        </div>

        <div class="info-box">
            <h2>Config Values (what Laravel actually uses)</h2>
            <p><span class="label">Config app.url:</span> <span class="value">{{ config('app.url') }}</span></p>
            <p><span class="label">Config google.client_id:</span> <span class="value">{{ config('services.google.client_id') }}</span></p>
            <p><span class="label">Config google.redirect:</span> <span class="value">{{ config('services.google.redirect') }}</span></p>
        </div>

        <div class="info-box">
            <h2>Generated URLs</h2>
            <p><span class="label">Route URL:</span> <span class="value">{{ route('google.callback') }}</span></p>
            <p><span class="label">URL helper:</span> <span class="value">{{ url('/auth/google/callback') }}</span></p>
        </div>

        <div class="info-box">
            <h2>Socialite Redirect URL (ACTUAL URL SENT TO GOOGLE)</h2>
            @php
                try {
                    $socialite = \Laravel\Socialite\Facades\Socialite::driver('google');
                    $redirect = $socialite->stateless()->redirect();
                    $url = $redirect->getTargetUrl();
                    preg_match('/redirect_uri=([^&]+)/', $url, $matches);
                    $redirectUri = isset($matches[1]) ? urldecode($matches[1]) : 'Could not extract';
                } catch (\Exception $e) {
                    $redirectUri = 'Error: ' . $e->getMessage();
                }
            @endphp
            <p><span class="label">Redirect URI being sent:</span></p>
            <p><span class="value" style="font-size: 1.2em; background: #000; padding: 10px;">{{ $redirectUri }}</span></p>
        </div>

        <div class="info-box">
            <h2>📋 What to add in Google Console</h2>
            <p>Copy this EXACT URL to your Google OAuth Console:</p>
            <pre>{{ config('services.google.redirect') }}</pre>
            
            <h3>Steps:</h3>
            <ol>
                <li>Go to <a href="https://console.cloud.google.com/apis/credentials" target="_blank" style="color: #4a9eff;">Google Cloud Console</a></li>
                <li>Click on your OAuth 2.0 Client ID</li>
                <li>In "Authorized redirect URIs", add the URL shown above</li>
                <li>Make sure there are NO trailing slashes or spaces</li>
                <li>Click Save and wait 5 minutes for propagation</li>
            </ol>
        </div>

        <div class="info-box">
            <h2>Common Issues Check</h2>
            @php
                $issues = [];
                
                // Check for trailing slash
                if (substr(config('services.google.redirect'), -1) === '/') {
                    $issues[] = ['type' => 'error', 'msg' => 'Redirect URL has trailing slash - remove it!'];
                }
                
                // Check for https vs http
                if (strpos(config('services.google.redirect'), 'https://localhost') !== false) {
                    $issues[] = ['type' => 'warning', 'msg' => 'Using HTTPS with localhost - make sure your server supports it'];
                }
                
                // Check if APP_URL matches redirect domain
                $appDomain = parse_url(config('app.url'), PHP_URL_HOST);
                $redirectDomain = parse_url(config('services.google.redirect'), PHP_URL_HOST);
                if ($appDomain !== $redirectDomain) {
                    $issues[] = ['type' => 'error', 'msg' => "APP_URL domain ($appDomain) doesn't match redirect domain ($redirectDomain)"];
                }
                
                // Check for spaces or special characters
                if (config('services.google.redirect') !== trim(config('services.google.redirect'))) {
                    $issues[] = ['type' => 'error', 'msg' => 'Redirect URL has leading/trailing spaces'];
                }
                
                if (empty($issues)) {
                    $issues[] = ['type' => 'success', 'msg' => 'No obvious configuration issues detected'];
                }
            @endphp
            
            @foreach($issues as $issue)
                <p class="{{ $issue['type'] }}">{{ $issue['type'] === 'error' ? '❌' : ($issue['type'] === 'warning' ? '⚠️' : '✅') }} {{ $issue['msg'] }}</p>
            @endforeach
        </div>

        <div class="info-box">
            <h2>Test Links</h2>
            <a href="{{ route('google.redirect') }}" style="display: inline-block; padding: 10px 20px; background: #4a9eff; color: #fff; text-decoration: none; border-radius: 5px; margin: 10px 0;">
                🚀 Test Google Login
            </a>
        </div>
    </div>
</body>
</html>
