# deadlock-symfony

A PHP app on Symfony and Doctrine that shows how a database deadlock happens.

## Setup

```
sh bin/build.sh
```

## Running the app

```
docker exec -it varfrog-deadlock-app sh bin/run.sh
```

## Explanation

We have two records of an entity `\App\Entity\User` in the database with usernames "alice" and "bob" - let's call them
users Alice and Bob, respectively.

bin/run.sh launches two commands in parallel:
1. app:lock-alice-and-bob - it first locks Alice, sleeps for some seconds, then tries to lock Bob,
2. app:lock-bob-and-alice - it locks Bob and Alice, without sleeping.

This results in a deadlock in the 1st command.

Output of `bin/run.sh`:
```
[error] Got a deadlock in app:lock-alice-and-bob

Command log in a chronological order, lines numbered in sequence:
     1  19:56:39.622337;app:lock-alice-and-bob;Locking Alice and sleeping.
     2  19:56:39.622344;app:lock-bob-and-alice;Locking Bob.
     3  19:56:39.634649;app:lock-bob-and-alice;Locking Alice.
     4  19:56:44.634883;app:lock-alice-and-bob;Locking Bob.
     5  19:56:44.640286;app:lock-alice-and-bob;Got a deadlock.
```

Explanation of the log:
* Lines 1, 2: the order of these lines does not matter - either of the commands can be started first. Here,
`app:lock-alice-and-bob` locks Alice and sleeps for some seconds, `app:lock-bob-and-alice` locks Bob.
* Line 3: `app:lock-bob-and-alice` tries to get a lock on Alice but has to wait since it's locked in line 1 by
`app:lock-alice-and-bob`
* Line 4: `app:lock-alice-and-bob` tries to get a lock on Bob but it has to wait for `app:lock-bob-and-alice`
to end its database transaction, i.e. for the lock on Bob to be released. But now `app:lock-bob-and-alice` will never
 release the lock  because it itself is waiting for the same thing - it waits for a lock of Alice to be released by
 `app:lock-alice-and-bob`. This situation cannot be resolved (both commands are infinitely waiting for each other) and
  thus we get a deadlock (Line 5).
