<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google OAuth Fixed - Coursezy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-50 to-blue-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">✅ Google OAuth is Fixed!</h1>
                <p class="text-gray-600">The redirect URL has been corrected</p>
            </div>

            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h2 class="font-semibold text-gray-900 mb-3">Current Configuration:</h2>
                <dl class="space-y-2">
                    <div class="flex justify-between">
                        <dt class="text-gray-600">APP_URL:</dt>
                        <dd class="font-mono text-sm bg-white px-3 py-1 rounded">{{ config('app.url') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Redirect URL:</dt>
                        <dd class="font-mono text-sm bg-white px-3 py-1 rounded text-green-600">{{ config('services.google.redirect') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Client ID:</dt>
                        <dd class="font-mono text-sm bg-white px-3 py-1 rounded">{{ substr(config('services.google.client_id'), 0, 20) }}...</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                <p class="text-sm text-blue-800">
                    <strong>Important:</strong> Make sure this exact URL is added in your Google Console:
                </p>
                <div class="mt-2 p-2 bg-white rounded">
                    <code class="text-blue-600 font-mono">http://coursezy.test/auth/google/callback</code>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="font-semibold text-gray-900">Test the Login:</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="{{ route('login') }}" 
                       class="block text-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        Go to Login Page
                    </a>
                    
                    <a href="{{ route('google.redirect') }}" 
                       class="block text-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Test Google Login Directly
                    </a>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="font-semibold text-gray-900 mb-3">Quick Checklist:</h3>
                <ul class="space-y-2 text-sm">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>APP_URL is set to <code class="bg-gray-100 px-1 rounded">http://coursezy.test</code></span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Redirect URL is hardcoded to prevent future issues</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Config cache has been cleared</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Clear your browser cache before testing</span>
                    </li>
                </ul>
            </div>

            <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
                <p class="text-sm text-yellow-800">
                    <strong>Still having issues?</strong> Try using an incognito/private browser window to test the login.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
