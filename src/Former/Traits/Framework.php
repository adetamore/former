<?php
namespace Former\Traits;

use \Former\Traits\Field;
use \Underscore\Arrays;
use \Underscore\String;

abstract class Framework
{

  // Public methods ------------------------------------------------ /

  /**
   * Get the name of the current framework
   *
   * @return string
   */
  public function current()
  {
    return basename(str_replace('\\', '/', get_class($this)));
  }

  /**
   * Check if the current framework matches something
   *
   * @param  string $framework
   * @return boolean
   */
  public function is($framework)
  {
    return $framework == $this->current();
  }

  /**
   * Check if the current framework doesn't match something
   *
   * @param  string $framework
   * @return boolean
   */
  public function isnt($framework)
  {
    return $framework != $this->current();
  }

  // Common methods ------------------------------------------------ /

  /**
   * Filter a field state
   *
   * @param string $state
   * @return string
   */
  public function filterState($state)
  {
    // Filter out wrong states
    if (!in_array($state, $this->states)) return null;
    return $state;
  }

  // Helpers ------------------------------------------------------- /

  /**
   * Prepend an array of classes with a string
   *
   * @param array  $classes The classes to prepend
   * @param string $with    The string to prepend them with
   *
   * @return array A prepended array
   */
  protected function prependWith($classes, $with)
  {
    return Arrays::each($classes, function($class) use ($with) {
      return $with.$class;
    });
  }

  /**
   * Alias for former.helpers.attributes
   */
  protected function addClass($attributes, $class)
  {
    return $this->app['former.helpers']->addClass($attributes, $class);
  }

  /**
   * Alias for former.helpers.attributes
   */
  protected function attributes($attributes)
  {
    return $this->app['former.helpers']->attributes($attributes);
  }

  /**
   * Create a label for a field
   *
   * @param Field  $field
   * @param string $label The field label if non provided
   *
   * @return string A label
   */
  public function createLabelOf(Field $field, $label = null)
  {
    // Get the label and its informations
    if (!$label) $label = $field->label;

    // Get label text
    $text = Arrays::get($label, 'text');
    if (!$text) return false;

    // Format attributes
    $attributes = Arrays::get($label, 'attributes', array());

    // Append required text
    if ($field->isRequired()) {
      $text .= $this->app['config']->get('former::required_text');
    }

    // Render plain label if checkable, else a classic one
    if ($field->isCheckable()) {
      $label = '<label'.$this->app['former.helpers']->attributes($attributes).'>'.$text.'</label>';
    } else {
      $label = $this->app['former.laravel.form']->label($field->name, $text, $attributes);
    }

    return $this->app['former.helpers']->decode($label);
  }
}