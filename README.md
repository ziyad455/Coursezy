# Coursezy 🎓

[![Laravel 12](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP 8.2](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](https://opensource.org/licenses/MIT)

**Coursezy** is a state-of-the-art, AI-powered online course platform built with Laravel 12. It aims to revolutionize the learning experience by integrating intelligent tools that assist both students and instructors, providing personalized and interactive education at scale.

## 🚀 The Vision

In a world of static learning, Coursezy brings interactivity through AI. The project solves the problem of passive online education by offering a smart chatbot for instant help, intelligent search for quick resource discovery, and personalized recommendations to keep learners engaged.

## ✨ Features

### 🤖 AI-Powered Experience

-   **Smart Chatbot:** Instant assistance for students within the course context.
-   **Intelligent Search:** Advanced search capabilities to find specific topics and lessons.
-   **AI Recommendations:** Personalized course suggestions based on user behavior and interests.

### 👨‍🏫 For Instructors

-   **Course Creation & Management:** Intuitive tools to build and organize course content.
-   **Real-time Interactions:** Integrated Pusher functionality for live updates.
-   **Cloud Media Management:** Seamless image and video uploads via Cloudinary.

### 🎓 For Students

-   **Interactive Learning:** Enroll in courses, track progress, and rate content.
-   **Social Login:** Quick access via social platforms using Laravel Socialite.
-   **Responsive Design:** A premium, modern UI built with Tailwind CSS and Alpine.js.

## 🛠 Tech Stack

-   **Backend:** [Laravel 12](https://laravel.com), PHP 8.2
-   **Frontend:** [Vite](https://vitejs.dev), [Tailwind CSS](https://tailwindcss.com), [Alpine.js](https://alpinejs.dev)
-   **Database:** SQLite (Default), MySQL support
-   **Real-time:** [Pusher](https://pusher.com) & [Laravel Echo](https://laravel.com/docs/12.x/broadcasting)
-   **Media:** [Cloudinary](https://cloudinary.com)
-   **AI Services:** Hugging Face API integration
-   **Testing & Quality:** [Pest](https://pestphp.com), [SonarQube](https://www.sonarqube.org)

## 📁 Project Structure (Brief)

-   `app/Http/Controllers`: Core application logic and request handling.
-   `app/Services`: External integrations (Cloudinary, AI, etc.).
-   `resources/views`: Blade templates powered by Tailwind CSS.
-   `resources/js`: Alpine.js components and Real-time Echo configuration.
-   `database/migrations`: Efficient schema definitions for Courses, Enrollments, etc.
-   `routes/`: Cleanly separated Web and API routes.

## ⚙️ Installation & Setup

### Prerequisites

-   PHP >= 8.2
-   Composer
-   Node.js & NPM
-   SQLite (or your preferred database)

### Step-by-Step Instructions

1. **Clone the repository:**

    ```bash
    git clone https://github.com/yourusername/coursezy.git
    cd coursezy
    ```

2. **Install Dependencies:**

    ```bash
    composer install
    npm install
    ```

3. **Environment Setup:**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Database Configuration:**
   Ensure your `.env` is configured correctly, then run:
    ```bash
    php artisan migrate
    ```

## 🏃 How to Run

Coursezy comes with a convenient developer script that starts all necessary services simultaneously.

### Development Mode

```bash
composer run dev
```

This command concurrently runs:

-   Laravel Development Server (`php artisan serve`)
-   Vite Dev Server (`npm run dev`)
-   Queue Listener (`php artisan queue:listen`)
-   Log Pail (`php artisan pail`)

### Production Build

```bash
npm run build
```

## 📸 Screenshots

Here are some previews of the Coursezy platform:

| Guest Page                                                     | Welcome Message                                                        |
| -------------------------------------------------------------- | ---------------------------------------------------------------------- |
| ![Guest Page](storage/app/public/page_screens/guesst_page.png) | ![Welcome Message](storage/app/public/page_screens/welcom_message.png) |

| Choosing Role                                                           | Student Dashboard                                                           |
| ----------------------------------------------------------------------- | --------------------------------------------------------------------------- |
| ![Choosing Role](storage/app/public/page_screens/chousing_the_role.png) | ![Student Dashboard](storage/app/public/page_screens/dashboard_student.png) |

| Coach Dashboard                                                          | AI Assistant                                                      |
| ------------------------------------------------------------------------ | ----------------------------------------------------------------- |
| ![Coach Dashboard](storage/app/public/page_screens/dachboarde_coach.png) | ![AI Assistant](storage/app/public/page_screens/ai_assistent.png) |

| Course Details                                                            | Watching Courses                                                          |
| ------------------------------------------------------------------------- | ------------------------------------------------------------------------- |
| ![Course Details](storage/app/public/page_screens/cours_deltail_page.png) | ![Watching Courses](storage/app/public/page_screens/watching_courses.png) |

| Creating Course                                                         | Add Sections                                                      |
| ----------------------------------------------------------------------- | ----------------------------------------------------------------- |
| ![Creating Course](storage/app/public/page_screens/creating_course.png) | ![Add Sections](storage/app/public/page_screens/add_sections.png) |

| Payment                                                | Profile                                                 |
| ------------------------------------------------------ | ------------------------------------------------------- |
| ![Payment](storage/app/public/page_screens/pyment.png) | ![Profile](storage/app/public/page_screens/profile.png) |

## 🔮 Future Improvements

-   [ ] Integration with more AI models (OpenAI/Claude).
-   [ ] Mobile application using Flutter or React Native.
-   [ ] Gamification system (Badges, Points, Leaderboards).
-   [ ] Multi-instructor support for diverse organizations.

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

---

 by Ziyad Tber.
