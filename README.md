# PHP Toy Robot Exercise

[![Build Status](https://travis-ci.org/reithbot/toyrobot-php.svg?branch=master)](https://travis-ci.org/reithbot/toyrobot-php) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/reithbot/toyrobot-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/reithbot/toyrobot-php/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/reithbot/toyrobot-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/reithbot/toyrobot-php/?branch=master)


## Installation/Setup
### Docker
To use the [docker image](https://hub.docker.com/r/redthor/toyrobot-php/):

```
docker run --rm -ti redthor/toyrobot-php
```

When you want the robot to move about the table, you must mount `/tmp` (your
local folder could be somewhere else) with the `/tmp` in the container.
Also, add an additional `/toyrobot`:

```
docker run --rm -ti -v /tmp:/tmp redthor/toyrobot-php ./toyrobot left
#                   ^^ mount ^^                       ^^ extra ^ ^command
```

You can run the tests as well (mounting isn't required):

```
docker run --rm -ti redthor/toyrobot-php vendor/bin/phpunit
```


### Your Own PHP Env
The toy robot requires php 7.1 and [composer](https://getcomposer.org/).
It is only a command line client, so the php cli binaries should be sufficient.

To get going:
```
composer install -v
```

Then simply:
```
./toyrobot
```


## ToyRobot Instructions
Running `toyrobot` wihout an instruction will list the options available.

With any command you can prefix it with `help` to get more info, e.g.:

```
./toyrobot help PLACE
```

The commands are listed in UPPERCASE in accordance with the specs but lowercase
will work just as well.

You can use `-vv` to see logging messages provided by the `LoggerInterface`, e.g

```
./toyrobot -vv place 4,2,S
```


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
The architecture is inspired by DDD/CQRS and by linear algebra.

### Command/Query Bus System
The app has been implemented with a light Command/Bus architecture. The point is to keep the
Robot domain separate from the console instructions. Conceptually Bus Commands could come
from anywhere in the future, such as a web front controller or a Message Queue Broker.

### Linear Algebra
Much of the work could be done with complicated if-then-else or switch
statements, however using Vectors and Matrices means we can more easily increase
the scope of the ToyRobot system.

For example, the tests show that we can expand the boundaries of the Table surface. Further,
without only a little effort we could also add:

1. More complicated movements using cosine and sine matrix multiplications;
2. Get the Robot to move from a flat two-dimensionsal space to a three or more (multi) dimensional space.

So for example, our BoundaryCondition, that checks that we haven't fallen off
the table, supports multi-dimensional spaces so that we could easily convert the
toy robot app to a robot wandering in three dimensions.

### Domain
The main two areas are `Robot` and `Space`. The `Space` context provides the abstract `SymmetricSpace` (symmetric
because we rely on evenly distributed boundaries) and the concrete specialisation `Table`. A Table is just a
2-dimensional space. In the app we bootstrap the table to be 5x5 but it could be other sizes.

The `Space` context contains the `BoundaryCondition`. A value, `x`, in a coordinate is said to be within the boundaries if it
conforms to the following equation:

![Equation](https://imgur.com/sr63tai.png)

Where `δ` and `x` are whole numbers and `δ` is the number of dimensions in the space, so 5 in a 5x5 table. Obviously a
division by zero is not allowed, that falls outside of the boundary.

In the `Robot` space we have our main character, the `Robot`. It is given a `Space`, a position and `Direction` to start.
The `Direction` class calculates where the robot is facing and produces left and right rotations by using vector and
matrix multiplication. See [here](https://en.wikipedia.org/wiki/Rotation_matrix) for some background.

The `Robot` only knows about the `Table` via a `SpaceInterface`, so it can be a 3D space if we want. The `Space`
contains the responsibility for working out whether a move is valid (by proxying the responsibility to
`BoundaryCondition`).

### Infrastructure
Infrastructure contains the concrete persistence classes. The robot is simply stored on disk between runs. We simplify
the data to serialize through sleep (serialize) and wakeup methods (unserialize) on the `Robot`.


## Builds
This repo is build by [Travis](https://travis-ci.org/reithbot/toyrobot-php) in three ways:

1. Against php7.1
2. Against php7.2
3. Against php7.2 but with the minimum versions of libraries

Travis produces the code coverage report which is then sent over to [Scrutinizer
CI](https://scrutinizer-ci.com/g/reithbot/toyrobot-php/). The Scrutinizer job will have
already started but it will wait to receive the code coverage report. Scrutinizer then conducts the analysis and
produces the reports and suggested fixes.
