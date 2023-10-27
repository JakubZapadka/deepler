# Deepler - Automatic Politics Article Generator

Deepler is a web application that automatically generates coherent and unbiased articles about politics using the OpenAI GPT-3 API. It aims to provide a summary of recent political events without unnecessary exaggeration or speculation. admin users can also manage articles, view logs, and access an admin panel for testing purposes.

![image](https://github.com/JakubZapadka/deepler/assets/102255945/2606ade7-e3f6-49d7-83eb-70f4c112fac4)

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
- Database system(included in XAMPP)

## Getting Started

To get Deepler up and running on your local machine, follow these steps:

1. Clone the repository:

   ```shell
   git clone https://github.com/JakubZapadka/deepler
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

5. Export database file(db.sql) to your database

6. Configure your environment by copying the `.env.example` file to `.env`:

   ```shell
   cp .env.example .env
   ```

7. Set your env keys in the `.env` file:

   ```shell
   DB_HOST=YOUR_DB_HOST
   DB_USER=YOUR_DB_USER
   DB_PASS=YOUR_DB_PASS
   DB_NAME=YOUR_DB_NAME
   CHATGPT_API_KEY=YOUR_CHATGPT_API_KEY
   DAILY_PASS=YOUR_PASSWORD_TO_FILE_DAILY-ARTICLE.PHP
   ```
   
9. Start the development server:

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
