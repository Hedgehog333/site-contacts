# site-contacts
WordPress Plugin (OOP)

The plugin is designed to store contact information of the site. Such as phone, e-mail, Skype and address.The data is stored in the database.
Once activated in the __Settings__ item appears __Site Contacts__.

### Tech
* [jQuery] - duh

### Shortcode
If you want to get data from html:
```html
[sitecontact code="code_name"]
```

If you want to get data from php:
```php
echo do_shortcode('[sitecontact code="code_name"]');
```

[jQuery]: <http://jquery.com>