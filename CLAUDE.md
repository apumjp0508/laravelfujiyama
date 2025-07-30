# Project Overview
- create ec site.
# Technology Stack

## Frontend
- HTML
- CSS
- Bootstrap
- JavaScript
- jQuery

## Backend
- PHP 8.2.12
- Laravel 9.52.20

## Environment
- Git
- GitHub
- Composer 2.8.8
- Node.js 16

# Directory Structure
- /components: Reusable UI components
- /hooks: Common logic utilities
- /pages/api: Backend processing and API routes
- /lib: External integration libraries (e.g., Slack notifications)

# Code Architecture

- Keep controllers as slim as possible.
- Database operations, external API integrations, and similar logic should be written in the `service` layer.
- Validation logic should be written in `app/Http/Requests` for clear responsibility separation.
- Services should be designed with testability in mind, following existing patterns, and including proper error handling.

# Naming Convention
- Use camelCase for all naming.

# Notes
- API keys should be stored in `.env` files.

# Preferred Claude Model
Use `claude-3-sonnet` by default. Use `opus` for tasks that require higher accuracy.

# Claude Expectations
The `products`, `orders`, and `order_items` tables have been updated to include a `shippingFee` column. Please update all related logic accordingly.

