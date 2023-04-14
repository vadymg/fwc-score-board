# FWC Score Board

FWC Score Board Library is a dummy project for testing purpouse. It can be easily installed and used in your PHP projects :smiley:

## Installation

To install FWC Score Board Library, you can use [Composer](https://getcomposer.org/), the PHP dependency management tool. Follow these steps:

1. Install Composer, if you haven't already, by following the instructions on the [Composer website](https://getcomposer.org/download/).

2. Require FWC Score Board Library using Composer by running the following command in your project's root directory:

```bash
composer require football-world-cup/score-board
```

3. Autoload the Library in your PHP project. Add the following line to your project's autoload.php or any other appropriate autoload file:

```php
require_once __DIR__ . '/vendor/autoload.php';
```

4. Start using FWC Score Board Library in your PHP code by including the necessary classes and calling their methods. Here's an example:

```php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

// The list of teams can be fetched from a database or any other source
$teams = FWC\ScoreBoard\Team::getTeamList();

$storage = new FWC\ScoreBoard\RedisStorage();
$scoreBoard = new FWC\ScoreBoard\ScoreBoard($storage);

$scoreBoard->startGame('Mexico', 'Canada');
$scoreBoard->startGame('Germany', 'France');

$scoreBoard->updateScore('Mexico', 'Canada', 0, 5);
$scoreBoard->finishGame('Germany', 'France');

foreach ($scoreBoard->getGames() as $game) {
    echo '<pre>';
    echo $game['homeTeam'] . ' ' . $game['homeScore']  . ' - ' . $game['awayTeam'] . ' ' . $game['awayScore'];
    echo '</pre>';
}
```

## Testing

My PHP Library comes with a set of tests to ensure its functionality. To run the tests, you can use PHPUnit, the popular PHP testing framework. Follow these steps:

Install PHPUnit, if you haven't already, by running the following command using Composer:

```bash
composer require --dev phpunit/phpunit
```

Run the tests by executing the following command in your project's root directory:

```bash
vendor/bin/phpunit
```

PHPUnit will run the tests and report the results, indicating whether the tests passed or failed.

## License

FWC Score Board Library is open-source software released under the MIT License. Please see the LICENSE file for more details.

## Support

If you encounter any issues or have any questions about FWC Score Board Library, please open an issue on the GitHub repository or contact the project maintainers.

Thank you for using FWC Score Board Library! We hope it helps you in your PHP projects.
