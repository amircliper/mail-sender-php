# PHP Registration and Email Confirmation API

This project is a simple PHP application that allows users to register their information, store it in an SQLite database, and send a confirmation email using PHPMailer. Before running the main application, you need to create the database by running a separate script.

## Features

- Stores user registration information in an SQLite database
- Sends a confirmation email with the registered details
- Securely uses Gmail SMTP for sending emails

## Prerequisites

- PHP 7.4 or higher
- Composer
- SQLite
- A Gmail account

## Setup Instructions

### Step 1: Clone the Repository

```sh
git clone https://github.com/yourusername/your-repo-name.git
cd your-repo-name
```

### Step 2: Install Dependencies

Use Composer to install the necessary dependencies:

```sh
composer install
```

### Step 3: Create the Database

Before running the main API script, you need to create the SQLite database by running the `create-database.php` script.

```sh
php create-database.php
```

### Step 4: Configure Gmail SMTP

You need to generate an application-specific password for your Gmail account to securely send emails. Follow these steps:

1. Go to [Google App Passwords](https://myaccount.google.com/apppasswords)
2. Generate a new app password and save it.

### Step 5: Configure Email Credentials

Open the `api.php` file and replace the `$GLOBALS['mail_address']` and `$GLOBALS['mail_password']` with your Gmail address and the generated app password.

```php
$GLOBALS['mail_address'] = 'your-email@gmail.com';
$GLOBALS['mail_password'] = 'your-app-password';
```

## Usage

Once you have set up everything, you can start using the API.

### Start the Server

You can use PHP's built-in server to run the application locally.

```sh
php -S localhost:8000
```

### Register a User

Send a POST request to the API endpoint with the user's details:

- `name`: User's name
- `phone`: User's phone number
- `email`: User's email address
- `description`: Additional information

Example using `curl`:

```sh
curl -X POST http://localhost:8000/api.php \
-H "Content-Type: application/json" \
-d '{
    "name": "John Doe",
    "phone": "123-456-7890",
    "email": "johndoe@example.com",
    "description": "New user registration"
}'
```

### Response

The API will respond with a JSON object indicating the status of the operation.

- Success with email sent:

```json
{
    "status": "success",
    "message": "User registered and email sent"
}
```

- Success without email sent:

```json
{
    "status": "success",
    "message": "User registered but email not sent",
    "error": "Detailed error message"
}
```

- Error:

```json
{
    "status": "error",
    "message": "Error message"
}
```

## Files

- `api.php`: Main API file to handle user registration and sending confirmation emails.
- `create-database.php`: Script to create the SQLite database.
- `composer.json`: Composer configuration file.

## Troubleshooting

If you encounter issues with sending emails, ensure that:

1. You have enabled "Less secure app access" in your Gmail account settings.
2. You are using the correct app password generated from the Google App Passwords page.

For any issues related to the database, ensure that the `create-database.php` script was executed successfully and that the database file `users.db` is in the correct location.

## Contributing

If you would like to contribute to this project, please fork the repository and submit a pull request.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.