#Installation

## For Symfony 2.1
### Add iJankiMailMimeDecodeBundle in your composer.json

```js
{
    "require": {
        "ijanki/mailmimedecode-bundle": "dev-master"
    }
}
```

## For Symfony 2.0


## Enable the bundle

Finally, enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Ijanki\Bundle\MailMimeDecode\MailMimeDecodeBundle(),
    );
}
```

WORK IN PROGRESS
