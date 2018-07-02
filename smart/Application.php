<?php


namespace smart;

use Exception;
use Loader;


/**
 * @property array $config
 */
class Application
{
    private $_config = [
        'access' => [
            ['login' => 'admin', 'password' => '098f6bcd4621d373cade4e832627b4f6']
        ],
        'title' => 'Smart site',
        'charset' => 'UTF-8',
        'phone' => '8(888)888-88-88',
        'email' => '',
        'meta-tags' => [],
        'og-tags' => [],
        'metrics' => '',
        'head-code' => '',
        'footer-code' => ''
    ];
    private $_configurationFile;

    private $_baseUrl;
    private $_scriptUrl;
    private $_smartRoutes;
    private $_user;

    /**
     * @var self
     */
    public static $instance;

    public function __construct()
    {
        $this->_configurationFile = implode(DIRECTORY_SEPARATOR, [Loader::$rootDir, 'smart', 'config', 'config.data']);
        $smartPath = implode(DIRECTORY_SEPARATOR, [Loader::$rootDir, 'smart', 'views']);
        $this->_smartRoutes = [
            'smart' => $smartPath . DIRECTORY_SEPARATOR . 'index.php',
            'smart/index' => $smartPath . DIRECTORY_SEPARATOR . 'index.php',
            'smart/login' => $smartPath . DIRECTORY_SEPARATOR . 'login.php',
            'smart/logout' => $smartPath . DIRECTORY_SEPARATOR . 'login.php',
            'smart/handler' => $smartPath . DIRECTORY_SEPARATOR . 'handler.php'
        ];
        if (is_file($this->_configurationFile)) {
            $this->loadConfiguration();
        } else {
            $this->saveConfiguration();
        }
        $this->_user = new User();
        static::$instance = $this;
    }

    public function run()
    {
        $_file_ = $this->parseRequest();
        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
        try {
            require $_file_;
            echo ob_get_clean();
        } catch (Exception $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        }
    }

    public function getConfig()
    {
        return $this->_config;
    }

    public function getUser()
    {
        return $this->_user;
    }

    public function setUser($value)
    {
        $this->_user = $value;
    }

    public function getParam($name)
    {
        return array_key_exists($name, $this->_config) ? $this->_config[$name] : '';
    }
    public function setParam($name, $value){
        if (($name == 'meta-tags' || $name == 'og-tags') && !is_array($value)){
            $value = [];
        }
        $this->_config[$name] = $value;
    }

    private function loadConfiguration()
    {
        $this->_config = json_decode(base64_decode(file_get_contents($this->_configurationFile)), true);
    }

    public function saveConfiguration()
    {
        file_put_contents($this->_configurationFile, base64_encode(json_encode($this->_config)), LOCK_EX);
    }

    private function parseRequest()
    {
        $request = $_SERVER['REQUEST_URI'];
        $requestParts = explode('?', $request);
        $baseRoute = rtrim($requestParts[0], '/');
        $route = trim(str_replace($this->getBaseUrl(), '', $baseRoute), '/');

        if (array_key_exists($route, $this->_smartRoutes)) {
            if (!$this->_user->isLoggedIn()) {
                return $this->_smartRoutes['smart/login'];
            }
            return $this->_smartRoutes[$route];
        }
        $resultRoute = implode(DIRECTORY_SEPARATOR, [
            Loader::$rootDir,
            'views',
            (empty($route) ? 'index' : str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $route))
        ]);
        if (is_file($resultRoute)) {
            return $resultRoute;
        } elseif (is_dir($resultRoute)) {
            return $resultRoute . DIRECTORY_SEPARATOR . 'index.php';
        } elseif (strpos($resultRoute, '.') === false) {
            $resultRoute .= '.php';
            if (is_file($resultRoute)) {
                return $resultRoute;
            }
        }
        throw new Exception("View script not found: '$resultRoute'");
    }

    public function getBaseUrl()
    {
        if ($this->_baseUrl === null) {
            $this->_baseUrl = rtrim(dirname($this->getScriptUrl()), '\\/');
        }
        return $this->_baseUrl;
    }

    public function getScriptUrl()
    {
        if ($this->_scriptUrl === null) {
            $scriptFile = $this->getScriptFile();
            $scriptName = basename($scriptFile);
            if (isset($_SERVER['SCRIPT_NAME']) && basename($_SERVER['SCRIPT_NAME']) === $scriptName) {
                $this->_scriptUrl = $_SERVER['SCRIPT_NAME'];
            } elseif (isset($_SERVER['PHP_SELF']) && basename($_SERVER['PHP_SELF']) === $scriptName) {
                $this->_scriptUrl = $_SERVER['PHP_SELF'];
            } elseif (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $scriptName) {
                $this->_scriptUrl = $_SERVER['ORIG_SCRIPT_NAME'];
            } elseif (isset($_SERVER['PHP_SELF']) && ($pos = strpos($_SERVER['PHP_SELF'], '/' . $scriptName)) !== false) {
                $this->_scriptUrl = substr($_SERVER['SCRIPT_NAME'], 0, $pos) . '/' . $scriptName;
            } elseif (!empty($_SERVER['DOCUMENT_ROOT']) && strpos($scriptFile, $_SERVER['DOCUMENT_ROOT']) === 0) {
                $this->_scriptUrl = str_replace('\\', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', $scriptFile));
            } else {
                throw new Exception('Unable to determine the entry script URL.');
            }
        }
        return $this->_scriptUrl;
    }

    public function getScriptFile()
    {
        if (isset($_SERVER['SCRIPT_FILENAME'])) {
            return $_SERVER['SCRIPT_FILENAME'];
        } else {
            throw new Exception('Unable to determine the entry script file path.');
        }
    }

    //region magic
    public function __get($name)
    {
        $getter = 'get' . ucfirst($name);
        if (method_exists($this, $getter)) {
            return $this->$getter();
        } elseif (method_exists($this, 'set' . ucfirst($name))) {
            throw new Exception('Getting write-only property: ' . get_class($this) . '::' . $name);
        } else {
            throw new Exception('Getting unknown property: ' . get_class($this) . '::' . $name);
        }
    }

    public function __set($name, $value)
    {
        $setter = 'set' . ucfirst($name);
        if (method_exists($this, $setter)) {
            call_user_func([$this, $setter], $value);
        } elseif (method_exists($this, 'get' . ucfirst($name))) {
            throw new Exception('Setting read-only property: ' . get_class($this) . '::' . $name);
        } else {
            throw new Exception('Setting unknown property: ' . get_class($this) . '::' . $name);
        }
    }
    //endregion
}