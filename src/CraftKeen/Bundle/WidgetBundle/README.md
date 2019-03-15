# Widgets

## Creating new Widget

### Implementing Model
First af all, should created a class model for new widget.
new class must implement [`\CraftKeen\Bundle\WidgetBundle\Model\WidgetInterface`](./Model/WidgetInterface.php)
Also can be used [`\CraftKeen\Bundle\WidgetBundle\Model\AbstractWidget`](./Model/AbstractWidget.php)
Optionally can be used following interfaces:
- DoctrineAwareInterface
- LanguageProviderAwareInterface
- SecurityContextAwareInterface

 For example:
```
<?php

namespace Acme\SomeBundle\Widgets;

use CraftKeen\Bundle\WidgetBundle\Model\AbstractWidget;

class AcmeWidget extends AbstractWidget 
{
}
```
OR
```php
<?php

namespace CraftKeen\Bundle\WidgetBundle\Model;

use CraftKeen\CMS\PageBundle\Entity\PageWidget;

class AcmeWidget implements WidgetInterface
{
    /**
     * {@inheritdoc}
     */
    public function setSource(PageWidget $source)
    {
        // TODO: Implement setSource() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getUid()
    {
        // TODO: Implement getUid() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        // TODO: Implement getTemplate() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplateData()
    {
        // TODO: Implement getTemplateData() method.
    }

    /**
     * {@inheritdoc}
     */
    public function supportsCache()
    {
        // TODO: Implement supportsCache() method.
    }

    /**
     * {@inheritdoc}
     */
    public function isApplicable(PageWidget $source)
    {
        // TODO: Implement isApplicable() method.
    }
}
```
 
### Registering Model
```yml
    craft_keen_widget.widget.text:
        parent: craft_keen_widget.widget.abstract
        class: Acme\SomeBundle\Widgets\AcmeWidget
        calls:
            - [setTemplate, ['CraftKeenWidgetBundle:%s:PageWidgets/AcmeWidget.html.twig']]
        tags:
            - { name: craft_keen_widget.widget, alias: AcmeWidget }
```

### Creating template

```twig
{% set fields = ['wrapClasses', 'widgetClasses', 'text', 'href'] %}

{% set widget = widget|merge({'data' : checkWidgetFields(widget.data, fields) }) %}

{{
    react_component(
        widget.dataType, {
            'props':{
                "isLoggedIn": logged,
                "id": widget.id,
                "wrapClasses": widget.data.wrapClasses,
                "widgetClasses": widget.data.widgetClasses,
                "text": widget.data.text,
                "href": widget.data.href,
            }
        }
    )
}}
```
