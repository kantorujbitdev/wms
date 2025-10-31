# Warehouse Management System (WMS)

A Warehouse Management System built with CodeIgniter 3 and Bootstrap 5, powered by external RESTful APIs.

## Features

- **User Authentication**: Login/logout with Bearer Token authentication
- **Dashboard**: Summary & charts display
- **Item Management**: CRUD operations for items
- **Warehouse Management**: Manage warehouses and stock
- **Transaction Management**: Track stock in/out and transfers
- **Reporting**: Generate and export reports
- **User Management**: Manage users and roles
- **Settings**: Configure system settings

## Requirements

- PHP 7.4 or higher
- CodeIgniter 3
- External RESTful API with Bearer Token authentication

## Installation

1. Clone this repository to your server
2. Configure your base URL in `application/config/config.php`
3. Set your API base URL in `application/config/config.php`
4. Make sure the `application/cache` and `application/logs` directories are writable
5. Access the application through your web browser

## Configuration

### API Configuration

Set your API base URL in `application/config/config.php`:

```php
 $config['api_base_url'] = 'https://api.example.com/v1/';
```
