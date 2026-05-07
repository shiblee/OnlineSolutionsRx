#Codemirror JS library

##Usage
Add the following code to your controller index method
```php
	$this->document->addScript('view/javascript/d_codemirror/lib/codemirror.js');\
	$this->document->addScript('view/javascript/d_codemirror/lib/xml.js');
	$this->document->addScript('view/javascript/d_codemirror/lib/formatting.js');
	$this->document->addStyle('view/javascript/d_codemirror/lib/codemirror.css');
	$this->document->addStyle('view/javascript/d_codemirror/theme/monokai.css');
```

then inisialize the codemirror in your template file with the following code:

```js
// Initialize codemirrror
	var editor = CodeMirror.fromTextArea(document.querySelector('.file-content .active textarea'), {
		mode: 'text/html',
		height: '500px',
		lineNumbers: true,
		autofocus: true,
		theme: 'monokai'
	});

	editor.setValue(json['code']);

```

To add content fit fix add this styles:

```css
.CodeMirror {
  border: 1px solid #eee;
  height: auto;
}
.CodeMirror-scroll {
  overflow-y: hidden;
  overflow-x: auto;
}
```