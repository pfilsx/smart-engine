<?php


namespace smart;


use Exception;
use Loader;

class ErrorHandler
{
    /**
     * Memory size to reserve for fatal errors handling
     * @var int
     */
    public $memoryReserveSize = 262144;
    /**
     * @var null|Exception
     */
    public $exception = null;
    private $_memoryReserve;
    /**
     * @var Application
     */
    private $_app;

    public function register($app){
        $this->_app = $app;
        ini_set('display_errors', false);
        set_exception_handler([$this, 'handleException']);
        set_error_handler([$this, 'handleError']);
        if ($this->memoryReserveSize > 0) {
            $this->_memoryReserve = str_repeat('x', $this->memoryReserveSize);
        }
        register_shutdown_function([$this, 'handleFatalError']);
    }

    /**
     * Unregister error and exception core handlers
     */
    public function unregister()
    {
        restore_error_handler();
        restore_exception_handler();
    }

    /**
     * Handle errors and translate them to exception for handling
     * @param $code - error code
     * @param $message - error message
     * @param $file - error file
     * @param $line - error line
     * @return false - if error_reporting is disabled
     * @throws Exception
     */
    public function handleError($code, $message, $file, $line)
    {
        if (error_reporting() & $code) {
            $exception = new Exception($message, $code);
            // in case error appeared in __toString method we can't throw any exception
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            array_shift($trace);
            foreach ($trace as $frame) {
                if ($frame['function'] === '__toString') {
                    $this->handleException($exception);
                    if (defined('HHVM_VERSION')) {
                        flush();
                    }
                    exit(1);
                }
            }
            throw $exception;
        }
        return false;
    }
    /**
     * Handle exceptions and print them to output
     * @param Exception $exception
     */
    public function handleException($exception)
    {
        $this->unregister();
        $this->exception = $exception;
        if (PHP_SAPI !== 'cli') {
            http_response_code(500);
        }
        try {
            $this->clearOutput();
            $this->renderException($exception);
            exit(1);
        } catch (Exception $e) {
            // an other exception could be thrown while displaying the exception
            $msg = "An Error occurred while handling another error:\n";
            $msg .= (string)$e;
            $msg .= "\nPrevious exception:\n";
            $msg .= (string)$exception;
            if (SMART_DEBUG) {
                if (PHP_SAPI === 'cli') {
                    echo $msg . "\n";
                } else {
                    echo '<pre>' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '</pre>';
                }
            } else {
                echo 'An internal server error occurred.';
            }
            error_log($msg);
            if (defined('HHVM_VERSION')) {
                flush();
            }
            exit(1);
        }
    }
    /**
     * Handle fatal errors and translating them into exception for displaying
     */
    public function handleFatalError()
    {
        unset($this->_memoryReserve);
        $error = error_get_last();
        if ($this->isFatalError($error)) {
            $this->exception = new Exception($error['message'], $error['type']);
            $this->clearOutput();
            $this->renderException($this->exception);
            exit(1);
        }
    }

    /**
     * Render exception to output if CRL_DEBUG enabled
     * @param Exception $exception
     * @throws Exception
     */
    public function renderException($exception)
    {
        if (is_string($this->_app->requestedRoute) && strpos($this->_app->requestedRoute, 'smart') === false) {
            $_file_ = implode(DIRECTORY_SEPARATOR, [Loader::$rootDir, 'views', 'error.php']);
            if (!is_file($_file_)){
                $_file_ = null;
            }
        }
        if (empty($_file_)) {
            $_file_ = implode(DIRECTORY_SEPARATOR, [Loader::$rootDir, 'smart', 'views', 'error.php']);
        }
        echo $this->_app->renderFile($_file_, ['exception' => $exception]);
    }

    /**
     * Indicates whether error is fatal
     * @param array $error
     * @return bool
     */
    private function isFatalError($error)
    {
        return isset($error['type']) && in_array($error['type'], [
                E_ERROR,
                E_PARSE,
                E_CORE_ERROR,
                E_CORE_WARNING,
                E_COMPILE_ERROR,
                E_COMPILE_WARNING
            ]);
    }
    /**
     * Clear current output buffers
     */
    private function clearOutput()
    {
        // the following manual level counting is to deal with zlib.output_compression set to On
        for ($level = ob_get_level(); $level > 0; --$level) {
            if (!@ob_end_clean()) {
                ob_clean();
            }
        }
    }
}