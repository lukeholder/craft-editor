# Template Editor for Craft CMS (Alpha)

Editor is a web based template editor for craft CMS.

Please use this at your own risk, is it works at file system level, I cannot
guarentee that you will not lose template files. Please backup your templates
before use.

I reserve the right to release this a paid plugin when the features and quality
improve.

# Features

* Uses the very powerful ACE editor.
* Remembers the file tree of your templates and where you last expanded the tree to.
* Binding of Command+S to save the file.
* Atomic file replacement when saving.
* Twig tag to link directly from you front end website to the editor
* Twig syntax highlighting!

# Usage

After installation you can simply place this in your twig template.

```
{% if user.admin %}
<a href="{{ craft.editor.templateLink }}">Edit this Template</a>
{% endif %}
```
Suggest placing this in your _layout.html template if you have one.

# Coming Soon

* Auto backup of your templates
* Template versioning.
* Push template to production site
* Tabbed editor

# Installation

Place editor folder found here within the plugins/ folder to your craft/plugins
folder.

# Other

Copyright 2013 Luke Holder
