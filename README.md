# Web Shell

Web Shell is a Laravel package that allows you to interact with a shell environment directly from your browser. This tool is designed for development purposes only and should not be used in production environments due to security risks associated with exposing shell access.

## Installation && Usage

You can install the package via composer:

```shell
composer require maso/web-shell
```

Publish the package's configuration files:

```shell
php artisan vendor:publish --provider="Maso\WebShell\WebShellServiceProvider" --tag="config"
```

## Usage

Once installed and configured, navigate to /web-shell in your browser to access the terminal interface.

## Security Considerations

This package is intended for development purposes only. Since it exposes shell access to your application, it is highly recommended to secure the route using middleware such as authentication or IP whitelisting to prevent unauthorized access. This can be done by modifying route array in the config/web-shell.php file :

```shell
'route' => [
    'prefix' => 'web-shell',
    'as' => 'web-shell.',
    'middleware' => ['auth', 'can:access-web-shell'], // Example: Add your middleware here
],
```

## Contributing

Contributions are welcome! If you'd like to improve this package, feel free to fork it, submit issues, or make pull requests.

## Security

If you discover any security vulnerabilities, please contact me directly at majd.soubh53@gmail.com. If the issue is not resolved quickly, you may open an issue on GitHub.

## Credits

- [Majd Soubh](https://www.linkedin.com/in/majd-soubh/)
