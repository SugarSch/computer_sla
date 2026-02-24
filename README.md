Computer Repair Request System (Web Application)

This Computer Repair Request System web application was developed for educational purposes to explore full-stack development using:

- Laravel (Backend Framework)
- React (Frontend Library)
- Inertia.js (Bridge between Backend and Frontend)

⚠️ This project is created for educational purposes only. Not intended for commercial use.

✨ Features

The system implements Role-Based Access Control (RBAC) with 5 user roles, each having different permissions, such as:

- Creating repair requests
- Assigning tasks
- Updating repair status
- Viewing histories

🛠 Tech Stack

Backend: Laravel

Frontend: React

Bridge Layer: Inertia.js

Database: PostgreSQL

🚀 Installation Guide 

1️⃣ Configure Database

- Create a new database
- Update your .env file with the correct database credentials
Run the migration command:
````
php artisan migrate
````
2️⃣ Install Dependencies
````
composer install
````
````
npm install
````
3️⃣ Run the Application

- Start the development servers:
````
php artisan serve
````
````
npm run dev
````
Then open your browser and visit:

http://127.0.0.1:3000 📌 Notes

This project is intended for learning full-stack development using Laravel + React with Inertia.js.

You are free to modify and extend it for educational purposes.

Commercial usage is not permitted.
