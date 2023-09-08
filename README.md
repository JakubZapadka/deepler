# Deepler - Automatic Politics Article Generator

Deepler is a web application that automatically generates coherent and unbiased articles about politics using the OpenAI GPT-3 API. It aims to provide a summary of recent political events without unnecessary exaggeration or speculation. Users can also manage articles, view logs, and access an admin panel for testing purposes.

You can see the project in action at [Deepler Website](https://deepler.pl). To access the admin panel for testing, use the following credentials:
- **Username**: test
- **Password**: test
- **Admin Panel Login**: [Admin Panel](https://deepler.pl/login.php)

## Prerequisites

Before running the project, ensure you have the following dependencies installed on your system:

- PHP 8.1.0+
- Composer
- Node.js
- A local server (e.g., XAMPP) if you want to run it locally

## Getting Started

To get Deepler up and running on your local machine, follow these steps:

1. Clone the repository:

   ```shell
   git clone https://github.com/your-username/deepler.git
   ```

2. Change to the project directory:

   ```shell
   cd deepler
   ```

3. Install PHP dependencies using Composer:

   ```shell
   composer install
   ```

4. Install JavaScript dependencies:

   ```shell
   npm install
   ```

5. Configure your environment by copying the `.env.example` file to `.env`:

   ```shell
   cp .env.example .env
   ```

6. Set your OpenAI API key in the `.env` file:

   ```shell
   OPENAI_API_KEY=your_api_key_here
   ```

7. Generate application key:

   ```shell
   php artisan key:generate
   ```

8. Migrate the database and seed it with sample data:

   ```shell
   php artisan migrate --seed
   ```

9. Start the development server:

   ```shell
   php artisan serve
   ```

10. Visit `http://localhost:8000` in your browser to access Deepler locally.

## Features

- Automatic generation of political articles using OpenAI GPT-3 API.
- Article management (add and remove articles).
- User authentication for admin panel access.
- Log viewing for tracking system activity.

## Technologies Used

Deepler is a project that encompasses various technologies and skills, including:

- Image processing for article content.
- Communication with APIs and databases.
- Developing a web application using vanilla PHP.
- Data management and manipulation.

## Contributing

We welcome contributions to make Deepler even better! Feel free to submit issues or pull requests.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

- [OpenAI](https://openai.com) for providing the GPT-3 API.
- The Deepler community for their support and feedback.

---

**Note**: Deepler is a project for educational and testing purposes and should not be used for commercial or political purposes without appropriate data verification and editorial oversight. The generated content may not always be entirely accurate or unbiased. Use it responsibly.
