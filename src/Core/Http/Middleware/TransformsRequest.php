<?php

namespace Themosis\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

class TransformsRequest
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->clean($request);

        return $next($request);
    }

    /**
     * Clean the request's data.
     *
     * @param Request $request
     */
    protected function clean($request)
    {
        $this->cleanParameterBag($request->query);

        if ($request->isJson()) {
            $this->cleanParameterBag($request->json());
        } elseif ($request->request !== $request->query) {
            $this->cleanParameterBag($request->request);
        }
    }

    /**
     * Clean the data in the parameter bag.
     *
     * @param ParameterBag $bag
     */
    protected function cleanParameterBag(ParameterBag $bag)
    {
        $bag->replace($this->cleanArray($bag->all()));
    }

    /**
     * Clean the data in the given array.
     *
     * @param array  $data
     * @param string $keyPrefix
     *
     * @return array
     */
    protected function cleanArray(array $data, $keyPrefix = '')
    {
        foreach ($data as $key => $value) {
            $data[$key] = $this->cleanValue($keyPrefix.$key, $value);
        }

        return collect($data)->all();
    }

    /**
     * Clean the given value.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    protected function cleanValue($key, $value)
    {
        if (is_array($value)) {
            return $this->cleanArray($value, $key.'.');
        }

        return $this->transform($key, $value);
    }

    /**
     * Transform the given value.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    protected function transform($key, $value)
    {
        return $value;
    }
}