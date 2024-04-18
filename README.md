# UserManagementSystem

Steps to run the system:

1. Pull the respository in LAMP/XAMPP stack: (use main branch)

https://github.com/amanpreetk11/UserManagementSystem.git

2. Import database.sql to create and populate database and required tables
3. Run the project. Entry point of the project is index.php. The page shows a login form for the admin user to login. The details of the admin user
   are pre-populated in database (during creation).

Email - admin@email.com
Password - admin

4. On login, user will be redirected to dashboard. There are tabs for Dashboard, Users and Sign Out in sidebar.
5. On Users tab, admin user can create new users, add/edit its own details like First Name, Last Name etc. Admin user cannot delete its own record.
6. When creating new user, the code automatically creates a password for the created user. It is base64 encryption of role assigned (eg. admin or user) by default. The created user can be sent an email with the information of the login details afterwards. The code has been written but needs server mail details. And in future, users can be allowed to reset their password.
7. When user role person logins the system, he/she can access the users list but cannot modify it. This has been implemented using authenticateUser function in User.php class.

Techniques used:

-Data validation and sanitization: Implemented in the respective functions in User.php class after user input is received.

-Dependency injection: Implemented in the User class constructor where the database connection is injected.

-Inheritance principle: Implemented in the Role.php class which extends the User class to handle roles of users.

-Separation of concern: Each class or function has a specific responsibility, such as the User class for user operations, and the Role class for user role operations.
