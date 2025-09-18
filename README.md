# Curio API Lessons

This repository contains a set of example APIs built with the **Laravel framework**, designed for use in the **Software Developer study program at Curio**.
The APIs return **JSON-only responses** (no front-end) and serve as a hands-on learning tool for understanding API development in Laravel.

## 📌 Features

The project includes the following example endpoints:

### 🌦 Weather API

* **GET** `/weer/{country}/{city}`

  * Returns simulated weather data for a given country and city.
  * Example:

    ```http
    GET /weer/NL/Amsterdam
    ```

    Response:

    ```json
    {
      "country": "NL",
      "city": "Amsterdam",
      "Temp": 12,
      "RainChance": 73
    }
    ```

### 💱 Currency Converter API

* **GET** `/currencyconverter/{from}/{to}/{amount}`

  * Converts an amount from one currency to another using predefined rates.
  * Example:

    ```http
    GET /currencyconverter/EUR/USD/100
    ```

    Response:

    ```json
    {
      "from": "EUR",
      "to": "USD",
      "amount": 100,
      "rate": 1.25,
      "calculated": 125
    }
    ```

* **GET** `/currencyconverter`

  * Returns a list of available currencies and possible conversion pairs.

### 💬 Quote API

* **GET** `/quote`

  * Returns a random inspirational quote.
  * Example Response:

    ```json
    {
      "quote": "It always seems impossible until it’s done.",
      "author": "Nelson Mandela"
    }
    ```

## ⚙️ Installation

Follow these steps to get the project running locally:

1. **Clone the repository**

   ```bash
   git clone https://https://github.com/curio-team/native-api
   cd native-api
   ```

2. **Install PHP dependencies**

   ```bash
   composer install
   ```

3. **Copy the example environment file**

   ```bash
   cp .env.example .env
   ```

4. **Generate application key**

   ```bash
   php artisan key:generate
   ```

5. **Start the development server**

   ```bash
   php artisan serve
   ```

   The application will be available at:

   ```
   http://127.0.0.1:8000
   ```

🚫 **Note:** This project does not include any frontend. So **no need to** run `npm install`. Additionally it has no database setup.

## 🧪 Testing the API

Use a tool like **Postman**, **Insomnia**, or simply your browser (for GET routes) to test the endpoints:

* `http://127.0.0.1:8000/weer/NL/Amsterdam`
* `http://127.0.0.1:8000/currencyconverter/EUR/USD/100`
* `http://127.0.0.1:8000/quote`

## 📚 Learning Goals

This project is intended to help students:

* Understand how to define routes in Laravel.
* Work with controllers and structured responses.
* Explore error handling (e.g., invalid inputs).
* Gain hands-on practice with REST API development.
