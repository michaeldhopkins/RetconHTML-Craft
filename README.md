# Retcon v. 1.3.3 for Craft CMS ![Craft 2.5](https://img.shields.io/badge/craft-2.5-red.svg?style=flat-square)

## Craft 3
Retcon for Craft 3 is in development, and will be available in the Plugin Store at some point (probably soon-ish).  

## Got WYSIWYG?

Ever have a client put 4 MB PNG files in their Redactor fields, failing to apply any of your meticulously crafted image transforms? Writers consistently using the `<strong>` tag for headers? Have you ever needed to implement lazy loading of images embedded in WYSIWYG content, responsive images or wanted to remove pesky inline styles without breaking a sweat? This is for you.

**Retcon** is a small [Craft CMS](http://buildwithcraft.com) plugin offering a series of easy-to-use Twig filters for manipulating HTML content.

## Installation and setup

* Download & unzip
* Move the /retconhtml folder to craft/plugins
* Install from Control Panel

***

## Basic usage

_Please see the [Wiki page](https://github.com/mmikkel/RetconHTML-Craft/wiki) for documentation, featureset overview and code examples._

Retcon uses [DOMDocument](http://php.net/manual/en/class.domdocument.php) to rewrite HTML. It includes a series of different methods, exposed as Twig filters:

```twig
{{entry.body | retconTransform('someImageTransform')}}
```

All methods, however, can also be called as template methods:

```twig
{{craft.retconHtml.transform(entry.body, 'someImageTransform')}}
```

...or through the Retcon service, if you ever want to use it in your PHP:

```php
<?php echo craft()->retconHtml->transform($entry->body, 'someImageTransform'); ?>
```

If you prefer, Retcon also includes a "catch-all" filter, taking the filter name as its first argument:

```twig
{{entry.body | retcon('transform', 'someImageTransform')}}
```
```twig
{{craft.retconHtml.retcon(entry.body, 'transform', 'someImageTransform')}}
```
```php
<?php echo craft()->retconHtml->retcon($entry->body, 'transform', 'someImageTransform'); ?>
```

And finally, you'll also be able to apply several operations in one go (for a _theoretical_ performance gain). Each index in the operation array will be either a String value (filter name) if there are no arguments, or an array of arguments (where the filter name should be the first index).

```twig
{{entry.body | retcon([
    ['transform', 'someImageTransform'],
    'lazy',
    ['attr', '.foo', {'class' : 'bar'}]
])}}
```
```twig
{{craft.retconHtml.retcon(entry.body, [
    ['transform', 'someImageTransform'],
    'lazy',
    ['attr', '.foo', {'class' : 'bar'}]
])}}
```
```php
<?php
echo craft()->retconHtml->retcon($entry->body, array(
    array('transform', 'someImageTransform'),
    'lazy',
    array('attr', '.foo', array('class' => 'bar')),
));
?>
````

### Methods

**[transform](https://github.com/mmikkel/RetconHTML-Craft/wiki/Transform)**
Apply a named or inline image transform to all images. If installed, Retcon uses [Imager](https://github.com/aelvan/Imager-Craft) to apply the transform.  

**[srcset](https://github.com/mmikkel/RetconHTML-Craft/wiki/srcset)**
Apply an array of named or inline image transform to all images, for simple srcset support. If installed, Retcon uses [Imager](https://github.com/aelvan/Imager-Craft) to apply the transforms.  

**[lazy](https://github.com/mmikkel/RetconHTML-Craft/wiki/Lazy)**
Replaces the _src_ attribute of image tags with a transparent, 1x1 px base64 encoded SVG, placing the original image's src in a data-attribute and retaining the original image's aspect ratio  

**[autoAlt](https://github.com/mmikkel/RetconHTML-Craft/wiki/AutoAlt)**
Adds filename as alternative text for images missing alt tags

**[attr](https://github.com/mmikkel/RetconHTML-Craft/wiki/Attr)**
Adds, replaces or removes a set of attributes and attribute values – e.g. `class`. Can be used to remove inline styles.  

**[renameAttr](https://github.com/mmikkel/RetconHTML-Craft/wiki/renameAttr)**
Renames existing attributes for matching selectors, retaining the attribute values.  

**[wrap](https://github.com/mmikkel/RetconHTML-Craft/wiki/Wrap)**
Wraps stuff in other stuff  

**[unwrap](https://github.com/mmikkel/RetconHTML-Craft/wiki/Unwrap)**
Removes parent node for matching elements  

**[remove](https://github.com/mmikkel/RetconHTML-Craft/wiki/Remove)**
Removes all elements matching the given selector(s)  

**[only](https://github.com/mmikkel/RetconHTML-Craft/wiki/Only)**
Removes everything but the elements matching the given selector(s)  

**[change](https://github.com/mmikkel/RetconHTML-Craft/wiki/Change)**
Changes tag type for all elements matching the given selector(s). Can also remove the tag(s) completely, retaining inner content  

**[inject](https://github.com/mmikkel/RetconHTML-Craft/wiki/Inject)**
Inject strings or HTML

**[replace](https://github.com/mmikkel/RetconHTML-Craft/wiki/Replace)**
Replace stuff with ```preg_replace``

### Settings

#### Base transform path
By default, Retcon will store any transformed images to a subfolder below the original image (identical to Craft's behaviour when applying transforms to an AssetFileModel). If you want to store transforms somewhere else, you can set a different base path here. Make sure that the folder is writable!

#### Base transform URL
Self explanatory.

#### Encoding
HTML output will be encoded to **UTF-8** by default, but you can set the encoding to be anything you want.  

### Use Imager
If installed, Retcon will use the Imager plugin by André Elvan for all image transforms. You can turn it off, though.  

### Disclaimer & support
Retcon is provided free of charge. The author is not responsible for data loss or any other problems resulting from the use of this plugin.
Please see [the Wiki page](https://github.com/mmikkel/RetconHTML-Craft/wiki) for documentation and examples. and report any bugs, feature requests or other issues [here](https://github.com/mmikkel/RetconHTML-Craft).
As Retcon is a hobby project, no promises are made regarding response time, feature implementations or bug amendments.

#### Changelog

##### 1.3.3

* [Fixed] The retconOnly filter no longer returns matching elements in reversed order

##### 1.3.2

* [Fixed] Fixes an issue where absolute transform URLs were messed up.

##### 1.3.1

* [Improved] The retconChange filter is now able to remove tag(s) completely for matching selectors, retaining content

##### 1.3

* [Added] Adds retconSrcset filter for simple responsive images
* [Added] Adds retconRenameAttr filter for renaming attribute names
* [Added] Adds setting to turn off Imager support
* [Improved] The rectonLazy filter base64 string now retains the aspect ratio for the original image

##### 1.2.2

* [Fixed] Fixed an issue where a missing $options parameter to RetconHtmlDocument would throw a warning in PHP7

##### 1.2.1

* [Added] Its now possible to add attributes without values
* [Improved] Reasons now longer throws PHP exceptions for empty strings

##### 1.2

* [Added] Adds release feed, documentation URL and other Craft 2.5 features
* [Improved] Now uses André Elvan's [Imager](https://github.com/aelvan/Imager-Craft) plugin for image transforms (where available)

##### 1.1.1

* Fixed an issue where certain filters would only apply to the first matched selector

##### 1.1.0

* Added ```replace``` filter

##### 1.0.3

* Fixed typo in settings template

##### 1.0.2

* Fixed issue with undefined constant CRAFT_SITE_URL

##### 1.0.1

* Fixed issue with hostname parsing for image URLs (could cause images to not be recognized as local assets)
* Fixed issue with uppercase file extensions not being allowed (could cause image transforms to be rejected)

##### 1.0

* _Initial public release_
