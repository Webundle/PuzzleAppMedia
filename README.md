# Puzzle App Media Bundle
**=========================**

Puzzle app bundle

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

`composer require webundle/puzzle-app-media`

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
{
    $bundles = array(
    // ...

    new Puzzle\App\MediaBundle\PuzzleAppMediaBundle(),
                    );

 // ...
}

 // ...
}
```

### Step 3: Register the Routes

Load the bundle's routing definition in the application (usually in the `app/config/routing.yml` file):

# app/config/routing.yml
```yaml
puzzle_app:
        resource: "@PuzzleAppMediaBundle/Resources/config/routing.yml"
```

### Step 4: Configure Bundle

Then, configure bundle by adding it to the list of registered bundles in the `app/config/config.yml` file of your project under:

```yaml
# Puzzle App Media
puzzle_app_media:
    title: media.title
    description: media.description
    icon: media.icon
    templates:
        file:
            list: 'AppBundle:Media:list_files.html.twig'
            show: 'AppBundle:Media:file.html.twig'
        folder:
            list: 'AppBundle:Media:list_folders.html.twig'
            show: 'AppBundle:Media:folder.html.twig'
```