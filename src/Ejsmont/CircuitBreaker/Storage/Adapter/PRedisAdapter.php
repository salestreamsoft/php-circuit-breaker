<?php
/**
 * Created by PhpStorm.
 * User: hooman
 * Date: 10/20/17
 * Time: 9:43 AM
 */

namespace Ejsmont\CircuitBreaker\Storage\Adapter;

use Ejsmont\CircuitBreaker\Storage\StorageException;

class PRedisAdapter extends BaseAdapter
{

    /**
     * @var \Redis
     */
    private $predis;

    /**
     * Prepare instance
     *
     * @param \Redis $redis
     * @param int $ttl
     * @param bool|false $cachePrefix
     */
    public function __construct($predis, $ttl = 3600, $cachePrefix = false) {
        parent::__construct($ttl, $cachePrefix);
        $this->predis = $predis;
    }

    /**
     * Helper method to make sure that extension is loaded (implementation dependent)
     *
     * @throws Ejsmont\CircuitBreaker\Storage\StorageException if extension is not loaded
     * @return void
     */
    protected function checkExtension()
    {
    }

    /**
     * Loads item by cache key.
     *
     * @param string $key
     * @return mixed
     *
     * @throws Ejsmont\CircuitBreaker\Storage\StorageException if storage error occurs, handler can not be used
     */
    protected function load($key)
    {
        try {
            return $this->predis->hget($key);
        } catch (\Exception $e) {
            throw new StorageException("Failed to load redis key: $key", 1, $e);
        }
    }

    /**
     * Save item in the cache.
     *
     * @param string $key
     * @param string $value
     * @param int $ttl
     * @return void
     *
     * @throws Ejsmont\CircuitBreaker\Storage\StorageException if storage error occurs, handler can not be used
     */
    protected function save($key, $value, $ttl)
    {
        try {
            $this->predis->hset($key, $value);
        } catch (\Exception $e) {
            throw new StorageException("Failed to save redis key: $key", 1, $e);
        }
    }
}