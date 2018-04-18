# PHP Toy Robot Exercise

[![Build Status](https://travis-ci.org/reithbot/toyrobot-php.svg?branch=master)](https://travis-ci.org/reithbot/toyrobot-php) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/reithbot/toyrobot-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/reithbot/toyrobot-php/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/reithbot/toyrobot-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/reithbot/toyrobot-php/?branch=master)


## Running the console

Use:

```
./toyrobot
```
to see the list of commands you can operate.

With any command you can prefix it with help to get more info, e.g.:

```
./toyrobot help PLACE
```

The commands are listed in UPPERCASE in accordance with the specs but lowercase
will work just as well.

You can use `-vv` to see logging messages provided by the `LoggerInterface`;


## Tests

Running the tests:

```
./vendor/bin/phpunit
```

You can see the test coverage here (and there is a badge at the top of this
README):

* https://scrutinizer-ci.com/g/reithbot/toyrobot-php/

Note the whole app is tested via `tests/toyrobotTest.php` but additionally all
(or almost all!) of the components are individually tested as well.

Writing tests before, or as you build the SUT, help clarify the API and responsibility.
Also the unit tests provide a very quick feedback loop so you can get started on
the next job.

## Architecture
### Linear Algebra

Much of the work could be done with complicated if-then-else or switch
statements, however using Vectors and Matrices means we can more easily increase
the scope of the ToyRobot system. For example, the tests show that we can expand
the boundaries of the Table surface. Further, without much more work we could
also add:

1. More complicated movements using cosine and sine matrix multiplications;
2. Get the Robot to move from a flat two-dimensionsal space to a three or more (multi) dimensional space.


So for example, our BoundaryCondition, that checks that we haven't fallen off
the table, supports multi-dimensional spaces.
