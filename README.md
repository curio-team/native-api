# Curio API Lessons

This repository contains a set of example APIs built with the **Laravel framework**, designed for use in the **Software Developer study program at Curio**.
The APIs return **JSON-only responses** (no front-end) and serve as a hands-on learning tool for understanding API development in Laravel.

## 📌 Features

The project includes the following example endpoints:

### 🌦 Weather API

* **GET** `/weer/{country}/{city}`

  * Returns weather data for a given country and any city name (letters, spaces and dashes, 2-50 characters).
  * Values drift smoothly over time (day/night and seasonal cycles) instead of re-rolling randomly on every request — polling the same city repeatedly won't jump around, but it will visibly change over the course of a day.
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

  * Converts an amount from one currency to another using predefined base rates, with a slow (±3% over a few hours) realistic drift applied on top so rates aren't static but also don't jump between requests.
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

### 👥 Crowd API

* **GET** `/crowd/{location}`

  * Returns a live-feeling occupancy reading for any venue name you make up (letters, numbers, spaces and dashes, 2-50 characters). Each unique location gets its own capacity and its own slowly-evolving crowd count.
  * The count only nudges a little between polls (it won't jump from 1 to 100 instantly) — great for demoing `setInterval` polling where students expect gradual, realistic change.
  * Example:

    ```http
    GET /crowd/city-museum
    ```

    Response:

    ```json
    {
      "location": "city-museum",
      "capacity": 184,
      "current_count": 97,
      "occupancy_percentage": 52.7,
      "status": "moderate",
      "updated_at": "2026-07-09T12:34:56+00:00"
    }
    ```

### 🏭 Lego Factory Sensor API

* **GET** `/factory/machines`

  * Lists the available factory machines (conveyor, press, painter, packer, welder) with their id and description.

* **GET** `/factory/{machine}`

  * Returns simulated sensor readings for a machine: temperature, vibration, belt speed, cumulative units produced, error count and current status (`running`, `idle` or `error`).
  * Status persists for a stretch of time instead of flickering every request, and sensor values drift gradually rather than jumping — `units_produced` only climbs while the machine is `running`.
  * ⚠️ **`conveyor-1` is intentionally unreliable.** The request itself still succeeds (`200 OK`), but `status` reports `"error"` for the first 3 seconds of every 15-second window (roughly 20% of the time), guaranteeing **at least two `"error"` readings every 30 seconds** no matter when you poll it. `error_count` increments each time it enters an error window. This is on purpose — it's there so students have to practice handling error *states* in the payload (not just failed requests) instead of assuming `status` is always healthy. Every other machine (`press-2`, `painter-3`, `packer-4`, `welder-5`) behaves normally.
  * Example:

    ```http
    GET /factory/conveyor-1
    ```

    Response:

    ```json
    {
      "machine": "conveyor-1",
      "name": "Conveyor Belt 1",
      "status": "running",
      "temperature_c": 32.4,
      "vibration_mm_s": 1.6,
      "belt_speed_units_min": 44.2,
      "units_produced": 128,
      "error_count": 0,
      "updated_at": "2026-07-09T12:34:56+00:00"
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

   Requires the PHP SQLite extension (used by the SQLite database set up in step 5). On Debian/Ubuntu, if it's not already installed:

   ```bash
   sudo apt install php8.4-sqlite3
   ```

3. **Copy the example environment file**

   ```bash
   cp .env.example .env
   ```

4. **Generate application key**

   ```bash
   php artisan key:generate
   ```

5. **Create the SQLite database and run migrations**

   The app uses a local SQLite database purely to back Laravel's cache (used to persist the slowly-drifting Crowd/Factory state between requests) — no app data is stored in it. `DB_CONNECTION=sqlite` is already the default in `.env.example`, so you just need to create the file and migrate:

   ```bash
   touch database/database.sqlite
   php artisan migrate
   ```

   On Windows (PowerShell), replace `touch` with:

   ```powershell
   New-Item -ItemType File -Path database/database.sqlite
   ```

6. **Start the development server**

   ```bash
   php artisan serve
   ```

   The application will be available at:

   ```
   http://127.0.0.1:8000
   ```

The project ships with a small Blade landing page linking to all the endpoints below.

## 🧪 Testing the API

Use a tool like **Postman**, **Insomnia**, or simply your browser (for GET routes) to test the endpoints:

* `http://127.0.0.1:8000/weer/NL/Amsterdam`
* `http://127.0.0.1:8000/currencyconverter/EUR/USD/100`
* `http://127.0.0.1:8000/quote`
* `http://127.0.0.1:8000/crowd/city-museum`
* `http://127.0.0.1:8000/factory/machines`
* `http://127.0.0.1:8000/factory/conveyor-1`

## 📚 Learning Goals

This project is intended to help students:

* Understand how to define routes in Laravel.
* Work with controllers and structured responses.
* Explore error handling (e.g., invalid inputs).
* Gain hands-on practice with REST API development.
* Practice polling endpoints with `setInterval`/`setTimeout` and handling data that changes gradually and realistically over time, rather than randomly on every request.
