# 🧾 PHP CRUD Application

A simple PHP application demonstrating basic **CRUD (Create, Read, Update, Delete)** operations using MySQL.
This project is ideal for beginners learning how to interact with databases in PHP.

---

## 🚀 Features

* ✅ Create new records
* ✅ Read/display records from database
* ✅ Update existing records
* ✅ Delete records
* ✅ Simple and clean UI
* ✅ MySQL database integration

---

## 🛠️ Tech Stack

* **Backend:** PHP
* **Database:** MySQL
* **Frontend:** HTML, CSS
* **Server:** Apache (XAMPP/WAMP)

---

## 📂 Project Structure

```
/php-crud-app
│-- config.php
|-- login.php
|-- logout.php
│-- client_form.php
│-- p2.php
│-- delete.php
|-- profile.php
│-- signup.php
```

---

## ⚙️ Installation Guide

### 1. Clone Repository

```
git clone https://github.com/your-username/php-crud-app.git
```

### 2. Move to Server Directory

* XAMPP → `htdocs/`
* WAMP → `www/`

---

### 3. Create Database

Create a database in phpMyAdmin:

```
db_crud
```

---

### 4. Create Table

Run this SQL:

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

### 5. Configure Database

Edit `config.php`:

```php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'crud';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
```

---

### 6. Run the Project

Open in browser:

```
http://localhost/crud/
```

---
## 7. pagiantion added 
 🔹 Pagination

## 🧠 How It Works

* **Create:** Add new user via sign up
* **Read:** Display all users in table
* **Update:** Edit user details if admin
* **Delete:** Remove user from database if admin

---

## 🔐 Security Notes

* Basic version (for learning)
* Recommended improvements:

  * Use prepared statements
  * Add input validation
  * Implement authentication
  * checks unique email address

---

## 📈 Future Improvements

* 🔹 Search & filter functionality

* 🔹 AJAX (no page reload)
* 🔹 Better UI (Bootstrap/Tailwind)
* 🔹 User authentication system

---

## 👨‍💻 Author

**Muhammad Sarmad Qadir**

---

## 📄 License

This project is licensed under the MIT License.

---

## ⭐ Support

If you found this helpful:

* ⭐ Star the repository
* 🍴 Fork it
* 🤝 Contribute

---
