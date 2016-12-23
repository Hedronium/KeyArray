<?php
namespace Hedronium\KeyArray;

class TrieNode
{
	protected $sub_tree = [];
	protected $is_set = false;
	protected $data = null;

	protected $parent;
	protected $string_key;

	public function __construct($parent = null, $string_key = null)
	{
		$this->parent = $parent;
		$this->string_key = $string_key;
	}

	public function isset()
	{
		return $this->is_set;
	}

	public function data()
	{
		return $this->data;
	}

	protected function keyNotFoundException()
	{
		throw new KeyNotFoundException('The key doesn\'t exist.');
	}

	protected function validateKey($cur_key)
	{
		if (!is_scalar($cur_key)) {
			throw new InvalidKeyException('Key must be an array.');
		}

		return $cur_key;
	}

	public function get(array $key)
	{
		if (empty($key)) {
			if ($this->isset()) {
				return $this;
			} else {
				$this->keyNotFoundException();
			}
		} else {
			if (isset($this->sub_tree[$key[0]])) {
				return $this->sub_tree[$key[0]]->get(array_slice($key, 1));
			} else {
				$this->keyNotFoundException();
			}
		}
	}

	public function set($key, $data)
	{
		if (empty($key)) {
			$this->data = $data;
			$this->is_set = true;

			return $this;
		} else {
			$cur_key = $this->validateKey($key[0]);

			if (!isset($this->sub_tree[$cur_key])) {
				$this->sub_tree[$cur_key] = new static($this, $cur_key);
			}

			return $this->sub_tree[$cur_key]->set(array_slice($key, 1), $data);
		}
	}

	public function unset($key)
	{
		if (empty($key)) {
			$this->is_set = false;
			$this->data = null;

			if (empty($this->sub_tree)) {
				$this->parent->deleaf($this->string_key);
			}
		} elseif (isset($this->sub_tree[$key[0]])) {
			$this->sub_tree[$key[0]]->unset(array_slice($key, 1));
		}
	}

	public function deleaf($string_key)
	{
		if (empty($this->sub_tree[$string_key]->sub_tree) && !$this->sub_tree[$string_key]->isset()) {
			unset($this->sub_tree[$string_key]);
		}
	}

	public function traverse($key_suffix = [])
	{
		if ($this->string_key) {
			$key_suffix[] = $this->string_key;
		}

		if ($this->isset()) {
			yield $key_suffix => $this->data();
		}

		foreach ($this->sub_tree as $path) {
			yield $path->traverse($key_suffix);
		}
	}
}
