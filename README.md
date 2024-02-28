# zbateson/php-dummy-sendmail

PHP based 'sendmail' dummy for writing emails to a directory for testing.

* Predictable and configurable file-naming
* Configurable output directory and file extension

## Installation

Dependencies must be downloaded and installed with composer - to do so:

```bash
composer install
```

> This depends on how composer is installed, if that fails please ensure composer is installed and review composer's
> documentation at: https://getcomposer.org/doc/00-intro.md

To create an executable PHAR archive, run `composer run build` first. This creates the file `php-dummy-sendmail.phar`.
Once created, move it to `/usr/local/bin` (or somewhere in your path) and configure the `sendmail` setting in PHP to use
the file.

```bash
mv php-dummy-sendmail.phar /usr/local/bin/php-sendmail
```

To run directly without creating a phar archive, use the `sendmail` file.

```bash
php sendmail
```

In Windows php-dummy-sendmail can be run with:

```
php sendmail
```

### Configure php.ini

Change the sendmail configuration in php.ini:

```
sendmail = /usr/local/bin/php-sendmail --directory /path/to/output-dir
```

## Usage

php-sendmail
    [--directory[="..."]]
    [--timestamp[="..."]]
    [--increment-file[="..."]]
    [--input-file[="..."]]
    [--file-extension[="..."]]
    [--print]
    [to]

Example:
php-sendmail user@example.com --directory /path/to/output/dir --timestamp "Y-m-d H:i:s:u" --file-extension txt

> php-sendmail reads from STDIN by default, so the above example run on the command line would block.

### Command-line Options

--directory - specifies the default directory to read/write to (useful if not the current dir)

--timestamp - PHP date() timestamp format (with 'u' support), used to format the output file name (default 'Y-m-d H:i:s:u')

--increment-file - Specifies a file to save an index number for auto-increment functionality. File names are numbered
  with this option set.

--file-extension - Sets the file extension to use for saved files.

--input-file - Specify an input file (useful for debugging)

--print - Simply prints the output
