# Template Editor for Craft CMS (Alpha)

Editor is a web based template editor for craft CMS.

Download [editor.zip here](https://github.com/lukeholder/craft-editor/releases/tag/1.0) on the releases page.
Only download the repo if you are a developer and know how to use composer.

Please use this at your own risk, is it works at file system level, I cannot
guarentee that you will not lose template files. Please backup your templates
before use.

I reserve the right to release this a paid plugin when the features and quality
improve.

![Screenshot](http://d.pr/i/26TW+ "Editor Screenshot")

# Features

* Uses the very powerful ACE editor.
* Remembers the file tree of your templates and where you last expanded the tree to.
* Binding of Command+S to save the file.
* Atomic file replacement when saving.
* Twig tag to link directly from you front end website to the editor
* Twig syntax highlighting!

# Usage

After installation you can simply place this in your twig templates:

```
{% if user and user.admin %}
<a href="{{ craft.editor.templateLink(_self) }}">Edit this Template</a>
{% endif %}
```
You need to place the above in the actual files you wish to have a link to.
If you only place the above link in the layout you will only get a link to
your layout.

# Coming Soon

* Template versioning.
* Push template to production site
* Tabbed editor

# Installation

Place editor folder found here within the plugins/ folder to your craft/plugins
folder.

# Other

Copyright 2013 Luke Holder
