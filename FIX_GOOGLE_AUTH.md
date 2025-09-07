# 🔧 Fix Google OAuth Error 400: invalid_request

## The Problem
Google is rejecting the authentication because the redirect URI doesn't match what's configured in Google Cloud Console.

## ✅ Quick Fix Steps

### Step 1: Go to Google Cloud Console
Open this link in your browser:
👉 https://console.cloud.google.com/apis/credentials

### Step 2: Find Your OAuth Client
Look for the client with ID starting with:
```
740037818119-fj3jqmkj3coa6oudctvqvmn1bf5ls66k
```
Click on it to edit.

### Step 3: Add Authorized JavaScript Origins
Add these EXACT URLs (copy and paste them):
```
http://coursezy.test
http://localhost
http://127.0.0.1
```

### Step 4: Add Authorized Redirect URIs
Add these EXACT URLs (copy and paste them):
```
http://coursezy.test/auth/google/callback
http://localhost/auth/google/callback
http://127.0.0.1/auth/google/callback
```

⚠️ **IMPORTANT**: The main one that MUST be there is:
```
http://coursezy.test/auth/google/callback
```

### Step 5: Configure OAuth Consent Screen
1. Go to "OAuth consent screen" in the left sidebar
2. Make sure it's configured:
   - **User Type**: External
   - **App name**: Coursezy
   - **User support email**: Your email
   - **Developer contact**: Your email
3. Add your email as a test user if the app is in testing mode

### Step 6: Save Everything
1. Click "SAVE" at the bottom of the OAuth client page
2. Wait 5-10 minutes for changes to propagate

### Step 7: Clear Caches
Run these commands in your terminal:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### Step 8: Clear Browser Data
1. Press `Ctrl+Shift+Delete` (or `Cmd+Shift+Delete` on Mac)
2. Clear cookies and cache for the last hour
3. Restart your browser

### Step 9: Test Again
Visit: http://coursezy.test/google-diagnostic

## 🎯 Verification Checklist

Check these in Google Console:
- [ ] OAuth consent screen is configured
- [ ] App is either in "Testing" with your email added, or "In production"
- [ ] The redirect URI `http://coursezy.test/auth/google/callback` is added
- [ ] No trailing slashes or spaces in the URIs
- [ ] Client ID matches what's in your .env file

## 🚨 Common Mistakes to Avoid

1. **Wrong URL format**: Make sure it's `http://` not `https://`
2. **Trailing slashes**: Don't add `/` at the end
3. **Wrong domain**: Use `coursezy.test` not `coursezy.local` or anything else
4. **Spaces**: No spaces before or after the URLs
5. **Case sensitivity**: URLs are case-sensitive

## 📸 Visual Guide

### What the Redirect URIs section should look like:
```
Authorized redirect URIs
✓ http://coursezy.test/auth/google/callback
✓ http://localhost/auth/google/callback
```

### What the JavaScript origins section should look like:
```
Authorized JavaScript origins
✓ http://coursezy.test
✓ http://localhost
```

## 💡 Still Not Working?

If you're still getting the error, try:

1. **Use localhost instead**: 
   - Change APP_URL in .env to `http://localhost:8000`
   - Run `php artisan serve`
   - Visit `http://localhost:8000/login`

2. **Check if Herd is running**:
   - Make sure Laravel Herd is running
   - The site should be accessible at `http://coursezy.test`

3. **Try incognito/private mode**:
   - Sometimes browser extensions interfere

4. **Verify your Google account**:
   - Make sure you're not using a G Suite account with restrictions

## 📝 Your Current Configuration

Based on your setup:
- **Redirect URI being sent**: `http://coursezy.test/auth/google/callback`
- **Client ID**: `740037818119-fj3jqmkj3coa6oudctvqvmn1bf5ls66k.apps.googleusercontent.com`
- **App URL**: `http://coursezy.test`

This EXACT redirect URI must be in Google Console!

## ✅ When It's Working

You'll know it's fixed when:
1. Clicking "Sign in with Google" opens Google's login page
2. After logging in, you're redirected back to Coursezy
3. You're logged into your dashboard

---

**Need more help?** Visit http://coursezy.test/google-diagnostic for a detailed diagnostic page.
