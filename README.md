<p align="center"><img src="http://res.cloudinary.com/guorenjun/image/upload/v1531809978/MN_LOGO_3.png" width="300"></p>

创建这个包的初衷是希望input text组件也可以支持Select组件的search功能。同时扩展suggestion功能，能支持获取API提供的内容。

```php
<?php
use MobileNowGroup\SearchableTextField\SearchableText;

SearchableText::make(__('Title'), 'title')
    ->rules('required', 'max:255')
    ->creationRules('unique:projects,title')
    ->options([
        'project1' => 'Project 1',
        'project2' => 'Project 2',
    ])->remoteOptionsUrl('/api/nova-vendor/projects'),

```

### Todo list

- [x] suggestion 可以支持value + label 模式
- [x] suggestion内容可以从远程获取