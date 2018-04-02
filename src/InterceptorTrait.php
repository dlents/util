<?php
/**
 * Copyright: Swlib
 * Author: Twosee <twose@qq.com>
 * Date: 2018/3/24 上午1:02
 */

namespace Swlib\Util;

trait InterceptorTrait
{

    /**@var callable[][] */
    public $interceptors = [];

    /**
     * Add an interceptor
     *
     * @param string $name
     * @param callable[] $interceptor
     */
    public function withInterceptor(string $name, array $interceptor)
    {
        $this->interceptors[$name] = $interceptor;
    }

    /**
     * Add a function to the interceptor
     *
     * @param string $name
     * @param callable $function
     * @return self|$this
     */
    public function withAddedInterceptor(string $name, callable $function): self
    {
        if (!isset($this->interceptors[$name])) {
            $this->interceptors[$name] = [];
        }
        $this->interceptors[$name][] = $function;

        return $this;
    }

    /**
     * Remove the interceptor
     *
     * @param string $name
     * @return self|$this
     */
    public function removeInterceptor(string $name): self
    {
        if (isset($this->interceptors[$name])) {
            unset($this->interceptors[$name]);
        }

        return $this;
    }

    /**
     * Call the interceptor
     *
     * @param string $name
     * @param array ...$arguments
     * @return mixed
     */
    public function callInterceptor(string $name, &...$arguments)
    {
        if (!empty($this->interceptors[$name])) {
            foreach ($this->interceptors[$name] as &$function) {
                if (is_callable($function)) {
                    $ret = $function(...$arguments);
                    if ($ret !== null) {
                        return $ret;
                    }
                }
            }
        }

        return null;
    }

}