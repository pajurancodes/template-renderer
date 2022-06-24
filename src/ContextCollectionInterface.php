<?php

namespace PajuranCodes\Template\Renderer;

/**
 * An interface to a collection of values 
 * passed to template files as context parameters.
 *
 * @author pajurancodes
 */
interface ContextCollectionInterface extends \Countable, \IteratorAggregate {

    /**
     * Get a value from the collection.
     * 
     * @param int|string $key A key.
     * @param mixed $default (optional) A default value to return if the given key doesn't exist.
     * @return mixed The found value or the given default value.
     */
    public function get(int|string $key, mixed $default = null): mixed;

    /**
     * Set a value in the collection.
     * 
     * @param int|string $key A key.
     * @param string|int|float|bool|null|object|array $value A value.
     * @return static
     */
    public function set(int|string $key, string|int|float|bool|null|object|array $value): static;

    /**
     * Push a value onto the end of the collection.
     * 
     * @param string|int|float|bool|null|object|array $value A value.
     * @return static
     */
    public function push(string|int|float|bool|null|object|array $value): static;

    /**
     * Pop and return the last value in the collection.
     * 
     * The collection will be shortened by one element.
     * 
     * @return string|int|float|bool|null|object|array The last value, or null if the collection is empty.
     */
    public function pop(): mixed;

    /**
     * Shift a value off the beginning of the collection.
     * 
     * @return string|int|float|bool|null|object|array The shifted value, or null if the collection is empty.
     */
    public function shift(): string|int|float|bool|null|object|array;

    /**
     * Prepend a value to the beginning of the collection.
     * 
     * @param string|int|float|bool|null|object|array $value A value.
     * @return static
     */
    public function unshift(string|int|float|bool|null|object|array $value): static;

    /**
     * Remove a value from the collection.
     * 
     * @param int|string $key A key.
     * @return static
     */
    public function remove(int|string $key): static;

    /**
     * Check if a value exists in the collection.
     * 
     * @param int|string $key A key.
     * @return bool True if the specified key exists, or false otherwise.
     */
    public function exists(int|string $key): bool;

    /**
     * Get all values from the collection.
     * 
     * @return (string|int|float|bool|null|object|array)[] All values in the collection.
     */
    public function all(): array;

    /**
     * Remove all values from the collection.
     * 
     * @return static
     */
    public function clear(): static;

    /**
     * Check if the collection is empty.
     * 
     * @return bool True if the collection is empty, or false otherwise.
     */
    public function isEmpty(): bool;

    /**
     * Count the values in the collection.
     *
     * @return int Number of values in the collection.
     */
    public function count(): int;

    /**
     * Get an iterator to iterate through the collection.
     *
     * @return \Traversable The values iterator.
     */
    public function getIterator(): \Traversable;
}
