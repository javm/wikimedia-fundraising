Currency Conversion Tool
==========================
# Installation
It uses Mysql and php 7.2.8

a) Create a db with name 'exchange'

b) Grant write and read permisions to user 'wikimedia'

c) Create the table:

```mysql -u wikimedia -p < exchange.sql```

# Run for each requirement

1. Retrieving the data from the API (you can assume this will be triggered by a cron job)

```./convert.php 1```

2. Parsing the data

```./convert.php 2```

3. Storing the data in your MySQL table

```./convert.php 3```

4. Given an amount of a foreign currency, convert it into the
equivalent in US dollars. For example:
input: 'JPY 5000'
output: 'USD 65.63'

```./convert.php 'JPY 5000'```

5. Given an array of amounts in foreign currencies, return an array of US equivalent amounts in the same order. For example:
input: array( 'JPY 5000', 'CZK 62.5' )
output: array( 'USD 65.63', 'USD 3.27' )

```./convert.php "array( 'JPY 5000', 'CZK 62.5' )"```

(This can be a separate function from #4.)


