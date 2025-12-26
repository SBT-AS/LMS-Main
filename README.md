# Learning Management System (LMS)

A robust and feature-rich Learning Management System built with Laravel. This platform is designed to provide a seamless educational experience for students, instructors, and administrators.

## 🚀 Features

### for Students
- **Course Browsing**: Filter courses by categories and view detailed descriptions.
- **Enrollment System**: Easy course enrollment with support for both free and paid courses.
- **Shopping Cart**: Add multiple courses to the cart and purchase them in a single transaction.
- **Dashboard**: A personalized dashboard to track enrolled courses and progress.
- **Live Classes**: Access links to live sessions (Google Meet/Zoom) directly from the course page.
- **Certificates**: Auto-generated certificates upon successful course completion.

### for Admin & Instructors
- **Course Management**: Complete CRUD operations for courses, including uploading materials and setting prices.
- **User Management**: Manage student and instructor accounts with role-based permissions.
- **Financials**: View payment history and manage transaction records.
- **Content Security**: Features to prevent content theft (e.g., screenshot prevention).

### 💳 Payment Integration
- **Razorpay**: Fully integrated for secure and fast Indian payments.
- **Square**: (Integration in progress) for international card payments.

## 🛠 Tech Stack

- **Framework**: [Laravel](https://laravel.com)
- **Language**: PHP 8.2+
- **Frontend**: 
  - Blade Templating Engine
  - [Tailwind CSS](https://tailwindcss.com) for styling
  - JavaScript (Vanilla & Alpine.js)
- **Database**: MySQL
- **Role Management**: [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v5/introduction)
- **Data Tables**: [Yajra DataTables](https://yajrabox.com/docs/laravel-datatables/master/introduction)

## ⚙️ Installation & Setup

Follow these steps to set up the project locally:

1.  **Clone the Repository**
    ```bash
    git clone <repository-url>
    cd LMS
    ```

2.  **Install Backend Dependencies**
    ```bash
    composer install
    ```

3.  **Install Frontend Dependencies**
    ```bash
    npm install
    ```

4.  **Environment Configuration**
    Copy the example environment file and configure your settings.
    ```bash
    cp .env.example .env
    ```
    Open `.env` and set up your database and payment keys:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=root
    DB_PASSWORD=

    # Payment Gateways
    RAZORPAY_KEY=your_razorpay_key
    RAZORPAY_SECRET=your_razorpay_secret
    ```

5.  **Generate App Key**
    ```bash
    php artisan key:generate
    ```

6.  **Run Migrations & Seeders**
    Set up the database structure and default data.
    ```bash
    php artisan migrate --seed
    ```
    *(Note: Ensure you have your database server running)*

7.  **Build Assets**
    Compiles the CSS and JS files.
    ```bash
    npm run build
    ```

8.  **Run the Server**
    Start the local development server.
    ```bash
    php artisan serve
    ```
    Access the app at `http://localhost:8000`.

## 📂 Project Structure

- `app/`: Contains the core code (Controllers, Models, etc.).
- `resources/views/`: Blade templates for the frontend.
- `routes/`: Web and API route definitions.
- `database/`: Migrations and seeders.
- `public/`: The entry point for the web server and static assets.

## 🤝 Contributing

 Contributions are welcome! Please follow these steps:
 1. Fork the repository.
 2. Create a new branch (`git checkout -b feature/YourFeature`).
 3. Commit your changes (`git commit -m 'Add some feature'`).
 4. Push to the branch (`git push origin feature/YourFeature`).
 5. Open a Pull Request.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
