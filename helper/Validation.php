<?php
/**
 * Validation Helper
 * 
 * Helper untuk validasi input
 */

class Validation
{
    private $errors = [];

    /**
     * Validate required field
     * 
     * @param string $field
     * @param mixed $value
     * @param string $label
     * @return $this
     */
    public function required($field, $value, $label = '')
    {
        if (empty($value)) {
            $this->errors[$field] = ($label ?: ucfirst($field)) . ' tidak boleh kosong.';
        }
        return $this;
    }

    /**
     * Validate email
     * 
     * @param string $field
     * @param string $value
     * @param string $label
     * @return $this
     */
    public function email($field, $value, $label = '')
    {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = ($label ?: ucfirst($field)) . ' tidak valid.';
        }
        return $this;
    }

    /**
     * Validate min length
     * 
     * @param string $field
     * @param mixed $value
     * @param int $min
     * @param string $label
     * @return $this
     */
    public function min($field, $value, $min, $label = '')
    {
        if (!empty($value) && strlen($value) < $min) {
            $this->errors[$field] = ($label ?: ucfirst($field)) . ' minimal ' . $min . ' karakter.';
        }
        return $this;
    }

    /**
     * Validate max length
     * 
     * @param string $field
     * @param mixed $value
     * @param int $max
     * @param string $label
     * @return $this
     */
    public function max($field, $value, $max, $label = '')
    {
        if (!empty($value) && strlen($value) > $max) {
            $this->errors[$field] = ($label ?: ucfirst($field)) . ' maksimal ' . $max . ' karakter.';
        }
        return $this;
    }

    /**
     * Validate numeric
     * 
     * @param string $field
     * @param mixed $value
     * @param string $label
     * @return $this
     */
    public function numeric($field, $value, $label = '')
    {
        if (!empty($value) && !is_numeric($value)) {
            $this->errors[$field] = ($label ?: ucfirst($field)) . ' harus berupa angka.';
        }
        return $this;
    }

    /**
     * Check if validation has error
     * 
     * @return boolean
     */
    public function hasError()
    {
        return !empty($this->errors);
    }

    /**
     * Get all errors
     * 
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Get first error message
     * 
     * @return string|null
     */
    public function getFirstError()
    {
        return reset($this->errors) ?: null;
    }

    /**
     * Get error for specific field
     * 
     * @param string $field
     * @return string|null
     */
    public function getError($field)
    {
        return $this->errors[$field] ?? null;
    }
}
