# BML Transaction

PHP Package for getting transactions from BML. (Experimental)

## Installation

```
composer require baraveli/bml-transaction
```

## Usage

### Login

This method will try to authenticate with the BML API. First argument is your bml username and second is bml password. When you login there will be two available properties `authenticationStatus` and `userID`.

```php
use Baraveli\BMLTransaction\BML;

$bml = new BML;

$bml->login("username", "password");

```

### Get Transactions Made Today : array

```php
use Baraveli\BMLTransaction\BML;

$bml = new BML;

$bml->login("username", "password")
    ->GetTodayTransactions();

```

### Get the Pending Transactions : array

```php
use Baraveli\BMLTransaction\BML;

$bml = new BML;

$bml->login("username", "password")
    ->GetPendingTransactions();

```

### Get transactions made between a date range.

Dates passed to the argument of the function goes through php strtotime() function. Which can parse about any English textual datetime description into a Unix timestamp. You can pass the arguments in any type of format you want. Transactions are paginated by the BML API. If you wish to get the next page of what you requested you may pass a third argument to the function which will correspond to page number.

```php
use Baraveli\BMLTransaction\BML;

$bml = new BML;

$bml->login("username", "password")
    ->GetTransactionsBetween("December 15 2019", "August 1 2020");

```

This will return all the transactions made in December 15 2019 to August 1 2020. You can only get the transactions made with in 12 months.