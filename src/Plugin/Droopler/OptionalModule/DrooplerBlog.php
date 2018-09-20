<?php

namespace Drupal\droopler\Plugin\Droopler\OptionalModule;

use Drupal\Core\Form\FormStateInterface;

/**
 * Droopler blog.
 *
 * @DrooplerOptionalModule(
 *   id = "d_blog",
 *   label = @Translation("Enable Blog"),
 *   description = @Translation("Droopler brings out-of-the-box blog functionality. Check this option to enable blog listing and content type"),
 *   type = "module",
 *   standardlyEnabled = 1,
 * )
 */
class DrooplerBlog extends AbstractOptionalModule {}
