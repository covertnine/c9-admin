# C9 Admin
Plugin does the basic WordPress things, with options.

## Developer Guide (and a crash course on using PHP classes)

From `c9-admin.php` the admin class in `admin/class-c9-admin.php` is instantiated:

```
function run_c9_admin()
{
	$plugin = new C9_Admin('C9_Admin', C9_ADMIN_VERSION);
}
```

Whenever a PHP class is instantiated, the `_construct` method is automatically called. So, basically, everything is handled from there (all the Wordpress actions and filters)

```
/* -- admin/class-c9-admin.php -- */

public function __construct($plugin_name, $version)
{

    $this->plugin_name = $plugin_name;
    $this->version = $version;

    add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
    add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    
    ....

}
```

`$this` refers to the instantiated C9_Admin object itself, so `enqueue_styles` is the class method referenced later within the class.

```
public function enqueue_styles()
{
    ...
}
```

So, that's how you add your hooks to this plugin.

## Use
Find all current options in Settings > C9 Admin
