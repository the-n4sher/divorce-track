<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Joint application products array.
     *
     * @var array
     */
    protected $jointAppProducts = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->jointAppProducts = ["DDPJ"];

        // E.g.: $this->session = \Config\Services::session();
    }

    // This function will ensure that we have some output from the methods as required by 4.6.* codeigniter
    public function __call($method, $params)
    {
        if (method_exists($this, $method)) {
            // Start output buffering to catch any echoes
            ob_start();
            $result = call_user_func_array([$this, $method], $params);
            $output = ob_get_clean();

            // If method returned something, use it
            if ($result !== null) {
                return $result;
            }

            // If method echoed something, use that
            if ($output !== '') {
                return $output;
            }

            // Nothing returned or echoed: return empty string
            return '';
        }

        // Method doesn't exist: throw 404
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound($method);
    }
}
