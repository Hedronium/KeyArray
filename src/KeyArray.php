<?php
namespace Hedronium\KeyArray;

use ArrayAccess;
use Iterator;
use Hedronium\GeneratorNest\GeneratorNest;

class KeyArray implements ArrayAccess, Iterator
{
	protected $trie;
	protected $bootstrap_iterator;
	protected $iterator;

	public static function array()
	{
		return new static;
	}

	protected function bootstrapIterator()
	{
		$this->iterator = GeneratorNest::nested($this->trie->traverse());

		$this->iterator_current = function () {
			return $this->iterator->current();
		};

		$this->iterator_key = function () {
			return $this->iterator->key();
		};

		$this->iterator_next = function () {
			return $this->iterator->next();
		};

		$this->iterator_rewind = function () {
			unset($this->iterator);
			$this->iterator = GeneratorNest::nested($this->trie->traverse());
			return $this->iterator->rewind();
		};

		$this->iterator_valid = function () {
			return $this->iterator->valid();
		};

		$this->iterator_current->bindTo($this);
		$this->iterator_key->bindTo($this);
		$this->iterator_next->bindTo($this);
		$this->iterator_rewind->bindTo($this);
		$this->iterator_valid->bindTo($this);
	}

	protected function bootstrapLazyIterator()
	{
		$this->iterator_current = function () {
			$this->bootstrapIterator();
			return ($this->iterator_current)();
		};

		$this->iterator_key = function () {
			$this->bootstrapIterator();
			return ($this->iterator_key)();
		};

		$this->iterator_next = function () {
			$this->bootstrapIterator();
			return ($this->iterator_next)();
		};

		$this->iterator_rewind = function () {
			$this->bootstrapIterator();
			return ($this->iterator_rewind)();
		};

		$this->iterator_valid = function () {
			$this->bootstrapIterator();
			return ($this->iterator_valid)();
		};

		$this->iterator_current->bindTo($this);
		$this->iterator_key->bindTo($this);
		$this->iterator_next->bindTo($this);
		$this->iterator_rewind->bindTo($this);
		$this->iterator_valid->bindTo($this);
	}

	public function __construct()
	{
		$this->trie = new TrieNode;
		$this->bootstrapLazyIterator();
	}

	public function offsetExists($offset)
	{
		return $this->trie->get($offset)->isset();
	}

	public function offsetGet($offset)
	{
		return $this->trie->get($offset)->data();
	}

	public function offsetSet($offset, $value)
	{
		if (!is_array($offset)) {
			throw new InvalidKeyException('Key must be an array.');
		}

		$this->trie->set($offset, $value);
	}

	public function offsetUnset($offset)
	{
		$this->trie->unset($offset);
	}

	public function current()
	{
		return ($this->iterator_current)();
	}

	public function key()
	{
		return ($this->iterator_key)();
	}

	public function next()
	{
		return ($this->iterator_next)();
	}

	public function rewind()
	{
		return ($this->iterator_rewind)();
	}

	public function valid()
	{
		return ($this->iterator_valid)();
	}
}
