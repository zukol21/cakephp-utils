;(function () {
    /**
     * Web browser storage wrapper plugin.
     *
     * By default the plugin uses browser's SessionStorage, but you can use LocalStorage
     * by defining the 'engine' option, as 'local', during instantiation.
     *
     * To use the plugin simply instantiate it and use the public read / write methods:
     *
     * var storage = new QoboStorage(
     *     {engine: 'local'}
     * );
     *
     * storage.write('foo', 'bar');
     * var value = storage.read('foo');
     *
     * alert(value); // 'bar'
     *
     * @link https://scotch.io/tutorials/building-your-own-javascript-modal-plugin
     */
    this.QoboStorage = function (options = {}) {

        // create global element references
        this.enabled = false;
        this.engine = null;

        // define option defaults
        var defaults = {
            engine: 'session'
        }

        // create options by extending defaults with the passed in arugments
        this.options = extendDefaults(defaults, options);

        try {
            // initialize engine
            switch (this.options.engine) {
                case 'local':
                    this.engine = window.localStorage;
                    break;
                default:
                    this.engine = window.sessionStorage;
            }
        } catch (e) {
            console.log(e);
        }
    }

    QoboStorage.prototype = {
        // cache write
        write: function (key, value) {
            try {
                this.engine.setItem(key, value);
            } catch (e) {
                console.log(e);
            }
        },

        // cache read
        read: function (key) {
            var value = null;

            try {
                value = this.engine.getItem(key);
            } catch (e) {
                console.log(e);
            }

            return value;
        }
    };

    function extendDefaults(source, properties)
    {
        var property;
        for (property in properties) {
            if (properties.hasOwnProperty(property)) {
                source[property] = properties[property];
            }
        }

        return source;
    }
})();
