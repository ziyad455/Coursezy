# PowerShell script to add AI chat component to all standalone Blade files

$viewsPath = "C:\Users\osaka\Herd\Coursezy\resources\views"
$aiChatComponent = @"

    <!-- AI Chat Component -->
    <x-ai_chat />
"@

# List of files to update (only standalone HTML pages that don't use layouts)
$filesToUpdate = @(
    "$viewsPath\student\dashboard.blade.php",
    "$viewsPath\student\accont.blade.php",
    "$viewsPath\student\pyment.blade.php",
    "$viewsPath\student\inbox.blade.php",
    "$viewsPath\student\my-courses.blade.php",
    "$viewsPath\coach\dashboard.blade.php",
    "$viewsPath\coach\accont.blade.php",
    "$viewsPath\coach\inbox.blade.php",
    "$viewsPath\coach\Courses\index.blade.php",
    "$viewsPath\coach\Courses\add.blade.php",
    "$viewsPath\coach\Courses\edit.blade.php",
    "$viewsPath\auth\login.blade.php",
    "$viewsPath\auth\register.blade.php",
    "$viewsPath\auth\forgot-password.blade.php",
    "$viewsPath\coursDetails.blade.php",
    "$viewsPath\chating.blade.php",
    "$viewsPath\rull.blade.php",
    "$viewsPath\AiPage.blade.php",
    "$viewsPath\verify-google.blade.php",
    "$viewsPath\google-diagnostic.blade.php",
    "$viewsPath\google-test.blade.php",
    "$viewsPath\oauth-debug.blade.php"
)

foreach ($file in $filesToUpdate) {
    if (Test-Path $file) {
        $content = Get-Content $file -Raw
        
        # Check if the file already has the AI chat component
        if ($content -notmatch "x-ai_chat") {
            # Check if this is a standalone HTML file (has </body> tag)
            if ($content -match "</body>") {
                # Add the component before </body>
                $newContent = $content -replace "</body>", "$aiChatComponent`n</body>"
                Set-Content -Path $file -Value $newContent
                Write-Host "✅ Updated: $file" -ForegroundColor Green
            } else {
                Write-Host "⚠️ Skipped (no body tag): $file" -ForegroundColor Yellow
            }
        } else {
            Write-Host "ℹ️ Already has AI chat: $file" -ForegroundColor Cyan
        }
    } else {
        Write-Host "❌ File not found: $file" -ForegroundColor Red
    }
}

Write-Host "Finished adding AI chat component to all pages!" -ForegroundColor Magenta
