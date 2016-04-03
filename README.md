# Async Task Runner for the WordPress
Create one time asynchronous tasks for WordPress

Forked from The WP Asynchronous Tasks plugin for TechCrunch.com - https://github.com/techcrunch/wp-async-task



## How To Use
Make sure to install as a composer dependnecy in plugin/site/app/whatever and include your autoloader.

### Conceptual Understanding
* Read original version readme :
    * https://github.com/techcrunch/wp-async-task/blob/master/README.md
* Read my article about using original version  
    * http://torquemag.io/2016/01/use-asynchronous-php-wordpress/
    
* Basically:
    * Create a class that extends `shelob9\async\task`
        * set action name in protected property `$action`
        * Prepare data to send to aync task in protected method `prepare_data()`
        * Prepare data and fire action for processing in protected method `prepare_data`
        * Optionally add public method `callback` to call with async task
    * Create new instance of `shelob9\async\system`
        * pass it an object -- insantiate you must -- of your `helob9\async\task` class as first parameter
        * Optionally pass, as second parameter, the callback function for the async task. Pass as string or array.
        * Optionally pass, as third parameter, an array of arguments for `do_action`. Should have priority in first index and number of callback params in second argument. Default is `[20,1]`.

* Consider:
    * Not calling a hook, or using system object, just calling a cb function right in `shelob9\async\task::run_action()`
    * 



### Examples


* With callback in class that defines task

```
    use shelob9\async\system;
    
    $task = new example();
    new system( $task, false, [ 20, 2 ] );
    
    
    class example extends shelob9\async\task {
    
        /**
         * Name of action
         *
         * @var string
         */
        protected $action = 'save_post';
    
        /**
         * Prepare async task -- runs when the original hook is fired
         *
         * @param array $data Dta passed to save_post hook
         *
         * @return array
         */
        protected function prepare_data( $data ) {
            $post_id = $data[0];
            return array( 'post_id' => $post_id );
        }
    
        /**
         * Runs the async hook in next session
         */
        protected function run_action() {
            $post_id = $_POST['post_id'];
            $post = get_post( $post_id );
            do_action( "wp_async_$this->action", $post->ID, $post );
        }
    
        /**
         * Process async task
         *
         * @param $id
         * @param $post
         */
        public function callback( $id, $post ){
            //do something here
        }
    
    }
    
```

* With callback as a function

```
    use shelob9\async\system;
    
    $task = new example();
    new system( $task, 'hi_roy', [ 20, 2 ] );
    
    
    class example extends shelob9\async\task {
    
        /**
         * Name of action
         *
         * @var string
         */
        protected $action = 'save_post';
    
        /**
         * Prepare async task -- runs when the original hook is fired
         *
         * @param array $data Dta passed to save_post hook
         *
         * @return array
         */
        protected function prepare_data( $data ) {
            $post_id = $data[0];
            return array( 'post_id' => $post_id );
        }
    
        /**
         * Runs the async hook in next session
         */
        protected function run_action() {
            $post_id = $_POST['post_id'];
            $post = get_post( $post_id );
            do_action( "wp_async_$this->action", $post->ID, $post );
        }
    
       
    }
    
    function hi_roy( $id, $post ){
        //do something here
    }
    
```



## Copyright

Copyright Josh Pollock 2016 -- Based heavily on TechCrunch WP Asynchronous Tasks plugin for TechCrunch.com - https://github.com/techcrunch/wp-async-task - copyright 2014 TechCrunch. Much thanks, very open source

## License

This library is licensed under the [MIT](http://opensource.org/licenses/MIT) license. See LICENSE.md for more details.
