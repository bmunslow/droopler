<?php

namespace Drupal\droopler\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines an entity browser widget annotation object.
 *
 * @see hook_entity_browser_widget_info_alter()
 *
 * @Annotation
 */
class DrooplerOptionalModule extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The human-readable name of the widget.
   *
   * @var \Drupal\Core\Annotation\Translation
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * The human-readable name of the widget.
   *
   * @var \Drupal\Core\Annotation\Translation
   * @ingroup plugin_translatable
   */
  public $type;

  /**
   * The weight of the plugin in relation to other plugins.
   *
   * @var int
   */
  public $weight;

  /**
   * The array of plugin IDs to exclude.
   *
   * @var array
   */
  public $exclusions;

  /**
   * The array of dependent plugin IDs.
   *
   * @var array
   */
  public $dependencies;

}
