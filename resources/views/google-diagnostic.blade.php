<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google OAuth Diagnostic - Coursezy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Google OAuth Configuration Diagnostic</h1>
        
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4 text-red-600">⚠️ Error 400: invalid_request - Common Causes & Solutions</h2>
            
            <div class="space-y-4">
                <div class="border-l-4 border-blue-500 pl-4">
                    <h3 class="font-semibold">Current Configuration:</h3>
                    <ul class="mt-2 space-y-1 text-sm">
                        <li><strong>APP_URL:</strong> <code class="bg-gray-100 px-2 py-1 rounded">{{ config('app.url') }}</code></li>
                        <li><strong>Client ID:</strong> <code class="bg-gray-100 px-2 py-1 rounded">{{ config('services.google.client_id') }}</code></li>
                        <li><strong>Redirect URI Generated:</strong> <code class="bg-gray-100 px-2 py-1 rounded text-red-600">{{ config('services.google.redirect') }}</code></li>
                    </ul>
                </div>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <p class="font-semibold">📍 EXACT Redirect URI to add in Google Console:</p>
                    <div class="mt-2 p-3 bg-white border rounded">
                        <code class="text-red-600 font-mono text-lg">{{ config('services.google.redirect') }}</code>
                        <button onclick="copyToClipboard('{{ config('services.google.redirect') }}')" class="ml-2 px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                            Copy
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">📋 Step-by-Step Fix Instructions:</h2>
            
            <ol class="space-y-4">
                <li class="flex">
                    <span class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 flex-shrink-0">1</span>
                    <div>
                        <p class="font-semibold">Go to Google Cloud Console</p>
                        <a href="https://console.cloud.google.com/apis/credentials" target="_blank" class="text-blue-600 hover:underline">
                            https://console.cloud.google.com/apis/credentials
                        </a>
                    </div>
                </li>
                
                <li class="flex">
                    <span class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 flex-shrink-0">2</span>
                    <div>
                        <p class="font-semibold">Find your OAuth 2.0 Client ID</p>
                        <p class="text-sm text-gray-600">Look for: {{ substr(config('services.google.client_id'), 0, 30) }}...</p>
                    </div>
                </li>
                
                <li class="flex">
                    <span class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 flex-shrink-0">3</span>
                    <div>
                        <p class="font-semibold">Add these EXACT Authorized JavaScript origins:</p>
                        <div class="mt-2 space-y-1">
                            <div class="bg-gray-100 p-2 rounded">
                                <code>http://coursezy.test</code>
                                <button onclick="copyToClipboard('http://coursezy.test')" class="ml-2 text-sm text-blue-600">Copy</button>
                            </div>
                            <div class="bg-gray-100 p-2 rounded">
                                <code>http://localhost</code>
                                <button onclick="copyToClipboard('http://localhost')" class="ml-2 text-sm text-blue-600">Copy</button>
                            </div>
                        </div>
                    </div>
                </li>
                
                <li class="flex">
                    <span class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 flex-shrink-0">4</span>
                    <div>
                        <p class="font-semibold">Add these EXACT Authorized redirect URIs:</p>
                        <div class="mt-2 space-y-1">
                            <div class="bg-red-100 p-2 rounded border-2 border-red-300">
                                <code class="text-red-600 font-bold">{{ config('services.google.redirect') }}</code>
                                <button onclick="copyToClipboard('{{ config('services.google.redirect') }}')" class="ml-2 text-sm text-blue-600">Copy</button>
                            </div>
                            <div class="bg-gray-100 p-2 rounded">
                                <code>http://localhost/auth/google/callback</code>
                                <button onclick="copyToClipboard('http://localhost/auth/google/callback')" class="ml-2 text-sm text-blue-600">Copy</button>
                            </div>
                        </div>
                    </div>
                </li>
                
                <li class="flex">
                    <span class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 flex-shrink-0">5</span>
                    <div>
                        <p class="font-semibold">Save changes in Google Console</p>
                        <p class="text-sm text-gray-600">Click "Save" at the bottom of the page</p>
                    </div>
                </li>
                
                <li class="flex">
                    <span class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 flex-shrink-0">6</span>
                    <div>
                        <p class="font-semibold">Wait 5 minutes for changes to propagate</p>
                        <p class="text-sm text-gray-600">Google needs time to update the configuration</p>
                    </div>
                </li>
                
                <li class="flex">
                    <span class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 flex-shrink-0">7</span>
                    <div>
                        <p class="font-semibold">Clear your browser cache</p>
                        <p class="text-sm text-gray-600">Press Ctrl+Shift+Delete (or Cmd+Shift+Delete on Mac)</p>
                    </div>
                </li>
            </ol>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">🔍 Additional Checks:</h2>
            
            <div class="space-y-3">
                <div class="flex items-start">
                    <span class="text-2xl mr-3">{{ config('services.google.client_id') && config('services.google.client_secret') ? '✅' : '❌' }}</span>
                    <div>
                        <p class="font-semibold">OAuth Credentials Present</p>
                        <p class="text-sm text-gray-600">Client ID and Secret are configured</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <span class="text-2xl mr-3">{{ str_starts_with(config('services.google.redirect'), 'http') ? '✅' : '❌' }}</span>
                    <div>
                        <p class="font-semibold">Redirect URI Format</p>
                        <p class="text-sm text-gray-600">URI starts with http/https protocol</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <span class="text-2xl mr-3">{{ !str_contains(config('services.google.redirect'), 'localhost') || str_contains(config('services.google.redirect'), 'coursezy.test') ? '✅' : '⚠️' }}</span>
                    <div>
                        <p class="font-semibold">Domain Consistency</p>
                        <p class="text-sm text-gray-600">Using consistent domain throughout</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">🧪 Test Again:</h2>
            
            <div class="space-y-4">
                <p class="text-gray-600">After completing the steps above, test the authentication:</p>
                
                <div class="flex gap-4">
                    <a href="{{ route('google.redirect') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="white" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="white" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="white" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="white" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Test Google Login
                    </a>
                    
                    <button onclick="clearCaches()" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        Clear Laravel Cache
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 rounded-lg p-6">
            <h3 class="font-semibold mb-2">💡 Pro Tip:</h3>
            <p class="text-sm">If you're still getting errors after following these steps, the issue might be:</p>
            <ul class="mt-2 space-y-1 text-sm list-disc list-inside">
                <li>The OAuth consent screen is not configured (set it to "External" for testing)</li>
                <li>The app is in "Testing" mode and your email is not added as a test user</li>
                <li>Browser cookies/cache need to be cleared</li>
                <li>The redirect URI has trailing slashes or extra spaces</li>
            </ul>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Copied to clipboard: ' + text);
            }, function(err) {
                console.error('Could not copy text: ', err);
            });
        }
        
        function clearCaches() {
            fetch('/clear-caches', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                .then(response => response.json())
                .then(data => {
                    alert('Caches cleared! Try logging in again.');
                })
                .catch(error => {
                    alert('Please run: php artisan config:clear && php artisan cache:clear');
                });
        }
    </script>
</body>
</html>
