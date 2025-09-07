<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google OAuth Test - Coursezy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-2xl mx-auto p-8 bg-white rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Google OAuth Configuration Test</h1>
        
        <div class="space-y-4">
            <div class="p-4 bg-gray-50 rounded-lg">
                <h2 class="font-semibold text-gray-700 mb-2">Configuration Status:</h2>
                <ul class="space-y-2 text-sm">
                    <li class="flex items-center">
                        <span class="text-green-500 mr-2">✓</span>
                        <span>Laravel Socialite: Installed</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-green-500 mr-2">✓</span>
                        <span>Google Client ID: {{ substr(env('CLIENT_ID'), 0, 20) }}...</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-green-500 mr-2">✓</span>
                        <span>Google Client Secret: {{ substr(env('GOOGLE_CLIENT_SECRET'), 0, 10) }}...</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-green-500 mr-2">✓</span>
                        <span>Callback URL: {{ env('APP_URL') }}{{ env('GOOGLE_REDIRECT') }}</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-green-500 mr-2">✓</span>
                        <span>App URL: {{ env('APP_URL') }}</span>
                    </li>
                </ul>
            </div>
            
            <div class="p-4 bg-blue-50 rounded-lg">
                <h2 class="font-semibold text-gray-700 mb-2">Google Console Setup:</h2>
                <p class="text-sm text-gray-600 mb-2">Make sure you have added the following to your Google Console:</p>
                <ul class="space-y-1 text-sm">
                    <li>• Authorized JavaScript origins: <code class="bg-white px-2 py-1 rounded">{{ env('APP_URL') }}</code></li>
                    <li>• Authorized redirect URIs: <code class="bg-white px-2 py-1 rounded">{{ env('APP_URL') }}/auth/google/callback</code></li>
                </ul>
            </div>
            
            <div class="p-4 bg-yellow-50 rounded-lg">
                <h2 class="font-semibold text-gray-700 mb-2">Test Authentication:</h2>
                <div class="space-y-3">
                    <a href="{{ route('google.redirect') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="white" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="white" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="white" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="white" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Test Google Login
                    </a>
                    <p class="text-xs text-gray-500">Click to test the Google OAuth flow</p>
                </div>
            </div>
            
            @if(Auth::check())
            <div class="p-4 bg-green-50 rounded-lg">
                <h2 class="font-semibold text-gray-700 mb-2">Current User:</h2>
                <ul class="space-y-1 text-sm">
                    <li>Name: {{ Auth::user()->name }}</li>
                    <li>Email: {{ Auth::user()->email }}</li>
                    <li>Google ID: {{ Auth::user()->google_id ?? 'Not linked' }}</li>
                    <li>Role: {{ Auth::user()->role }}</li>
                </ul>
            </div>
            @endif
            
            <div class="p-4 bg-gray-50 rounded-lg">
                <h2 class="font-semibold text-gray-700 mb-2">Routes Available:</h2>
                <ul class="space-y-1 text-xs font-mono">
                    <li>GET /auth/google/redirect → GoogleAuthController@redirectToGoogle</li>
                    <li>GET /auth/google/callback → GoogleAuthController@handleGoogleCallback</li>
                    <li>GET /auth/google/link → GoogleAuthController@linkGoogleAccount</li>
                    <li>GET /auth/google/link/callback → GoogleAuthController@handleGoogleLink</li>
                    <li>POST /auth/google/unlink → GoogleAuthController@unlinkGoogleAccount</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
