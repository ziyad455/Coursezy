# Project Overview

This project is an advanced online course platform named "Coursezy," built with Laravel 12. It features AI-powered tools like a smart chatbot and intelligent search. The platform allows for course creation, enrollment, and ratings, and aims to provide personalized learning experiences through AI-driven recommendations.

The backend is built with PHP 8.2 and Laravel 12, utilizing packages like Socialite for social logins and Pusher for real-time functionalities.

The frontend is built using Vite, with Tailwind CSS for styling and Alpine.js for JavaScript interactivity. It also uses Laravel Echo with Pusher for real-time event listening.

## Building and Running

1.  **Initial Setup:**
    *   Copy the `.env.example` file to `.env`: `cp .env.example .env`
    *   Generate an application key: `php artisan key:generate`
    *   Run database migrations: `php artisan migrate`

2.  **Install Dependencies:**
    *   Install PHP dependencies: `composer install`
    *   Install Node.js dependencies: `npm install`

3.  **Running the Development Environment:**
    *   The `composer.json` file provides a convenient `dev` script to run all necessary development servers concurrently:
        ```bash
        composer run dev
        ```
    *   This command will start the Laravel development server, the queue listener, the log pail, and the Vite development server.

4.  **Building for Production:**
    *   To build the frontend assets for production, run:
        ```bash
        npm run build
        ```

## Testing

To run the test suite, use the following command:

```bash
php artisan test
```

This command is defined in the `scripts` section of `composer.json`.

## Development Conventions

*   The project follows the standard Laravel project structure.
*   Frontend assets are managed with Vite and located in the `resources` directory.
*   The project uses Tailwind CSS for styling, with the configuration file `tailwind.config.js`.
*   Real-time events are handled with Pusher, configured in `config/broadcasting.php` and `resources/js/echo.js`.
