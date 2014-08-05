<?php namespace Exolnet\Core\Exceptions;

use Exception;
use Illuminate\Support\Contracts\ArrayableInterface;
use Illuminate\Support\Contracts\JsonableInterface;
use JsonSerializable;

class ValidationException extends Exception
	implements ArrayableInterface, JsonableInterface, JsonSerializable
{
	/**
	 * The errors list
	 *
	 * @var array
	 */
	protected $errors;

	/**
	 * Constructor
	 *
	 * @param array      $errors
	 * @param string     $message
	 * @param integer    $code
	 * @param exception  $previous
	 */
	public function __construct($message = null, $code = 0, Exception $previous = null)
	{
		if (is_array($message)) {
			$this->errors = $message;
			$message      = null;
		}

		// Construct an exception
		parent::__construct($message, $code, $previous);
	}

	/**
	 * Get the errors
	 *
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * Convert the model instance to JSON.
	 *
	 * @param  int  $options
	 * @return string
	 */
	public function toJson($options = 0)
	{
		return json_encode($this->toArray(), $options);
	}

	/**
	 * Convert the object into something JSON serializable.
	 *
	 * @return array
	 */
	public function jsonSerialize()
	{
		return $this->toArray();
	}

	/**
	 * Convert the model instance to an array.
	 *
	 * @return array
	 */
	public function toArray()
	{
		return [
			'message' => $this->getMessage(),
			'code'    => $this->getCode(),
			'errors'  => $this->getErrors(),
		];
	}
}
