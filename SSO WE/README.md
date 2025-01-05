# SSO Webtool

A secure Single Sign-On (SSO) solution for web applications.

## Features

- User authentication (login/register)
- Email and phone verification
- Secure data storage
- API integration
- Customizable application experience

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer
- Node.js (for frontend assets)

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/sso-webtool.git
   cd sso-webtool
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install frontend dependencies:
   ```bash
   npm install
   ```

4. Create a `.env` file from the example:
   ```bash
   cp .env.example .env
   ```

5. Generate application key:
   ```bash
   php artisan key:generate
   ```

6. Configure your database settings in the `.env` file.

7. Run migrations:
   ```bash
   php artisan migrate
   ```

8. Start the development server:
   ```bash
   php artisan serve
   ```

## Configuration

Edit the `.env` file to configure:

- Database connection
- Email settings
- Application URL
- Security settings

## Usage

Access the application in your browser at `http://localhost:8000`

## Contributing

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

Distributed under the MIT License. See `LICENSE` for more information.

## Contact

Your Name - your.email@example.com

Project Link: [https://github.com/yourusername/sso-webtool](https://github.com/yourusername/sso-webtool)
