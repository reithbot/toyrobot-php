# PHP Toy Robot Exercise

[![Build Status](https://travis-ci.org/reithbot/toyrobot-php.svg?branch=master)](https://travis-ci.org/reithbot/toyrobot-php) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/reithbot/toyrobot-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/reithbot/toyrobot-php/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/reithbot/toyrobot-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/reithbot/toyrobot-php/?branch=master)


## Running the console

You can use `-vv` to see logging messages provided by the `LoggerInterface`;


## Tests

Running the tests:

```
./vendor/bin/phpunit
```

You can see the test coverage here (and there is a badge at the top of this
README):

* https://scrutinizer-ci.com/g/reithbot/toyrobot-php/

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
