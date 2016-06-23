<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slick\Http\PhpEnvironment;
use Slick\Http\PhpEnvironment\MiddlewareRunnerInterface;
use Slick\Http\Server\MiddlewareCollection;
use Slick\Http\Server\MiddlewareCollectionInterface;
use Slick\Http\Server\MiddlewareInterface;

/**
 * HTTP Server handler and middleware runner
 *
 * @package Slick\Http
 */
class Server implements MiddlewareRunnerInterface
{

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var ResponseInterface|PhpEnvironment\Response
     */
    private $response;

    /**
     * @var MiddlewareCollectionInterface
     */
    private $middlewareCollection;

    /**
     * Server request handler and middleware runner.
     *
     * @param ServerRequestInterface|null $req
     * @param ResponseInterface|null $res
     */
    public function __construct(
        ServerRequestInterface $req = null, ResponseInterface $res = null
    ) {
        $this->request = $req;
        $this->response = $res;
        $this->middlewareCollection = new MiddlewareCollection();
    }

    /**
     * Set HTTP request message
     *
     * @param ServerRequestInterface $request
     *
     * @return Server
     */
    public function setRequest(ServerRequestInterface $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Adds a middleware object to the stack
     *
     * @param MiddlewareInterface $middleware
     *
     * @return self|$this|\Slick\Http\Server\MiddlewareRunnerInterface
     */
    public function add(MiddlewareInterface $middleware)
    {
        $this->middlewareCollection->append($middleware);
        return $this;
    }

    /**
     * Sets a full list of middleware objects tho this runner
     *
     * @param MiddlewareCollectionInterface $objects
     *
     * @return self|$this|\Slick\Http\Server\MiddlewareRunnerInterface
     */
    public function setMiddlewareCollection(
        MiddlewareCollectionInterface $objects
    )
    {
        $this->middlewareCollection = $objects;
        return $this;
    }

    /**
     * Gets the middleware collection object
     *
     * @return MiddlewareCollection|MiddlewareCollectionInterface
     */
    public function getMiddlewareCollection()
    {
        return $this->middlewareCollection;
    }

    /**
     * Runs all the stack and return the response
     *
     * @return ResponseInterface|Response
     */
    public function run()
    {
        $middleware = $this->setObjectDependency();
        return $middleware->handle($this->request, $this->getResponse());
    }

    /**
     * Initializes and reuses the server response
     *
     * @return ResponseInterface|PhpEnvironment\Response
     */
    protected function getResponse()
    {
        if (null === $this->response) {
            $this->response = new PhpEnvironment\Response();
        }
        return $this->response;
    }

    /**
     * Sets the middleware objects dependency
     *
     * This method returns the middleware that starts the chain
     *
     * @return null|MiddlewareInterface
     */
    protected function setObjectDependency()
    {
        $reverse = array_reverse($this->middlewareCollection->asArray());
        $last = null;
        /** @var MiddlewareInterface $object */
        foreach ($reverse as $object) {
            $object->setNext($last);
            $last = $object;
        }
        return $last;
    }
}
