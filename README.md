# Web Shell

The Web Shell package allows you to easily interact with shell directly from your browser.

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

Web Shell allows powerful access to your system, so it is highly recommended to secure the route using middleware such as authentication or IP whitelisting to prevent unauthorized access.

## Contributing

Contributions are welcome! If you'd like to improve this package, feel free to fork it, submit issues, or make pull requests.

## Security

If you discover any security vulnerabilities, please contact me directly at majd.soubh53@gmail.com. If the issue is not resolved quickly, you may open an issue on GitHub.

## Credits

- [Majd Soubh](https://www.linkedin.com/in/majd-soubh/)
