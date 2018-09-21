<?php

namespace Drupal\droopler\Plugin\Droopler\OptionalModule;

/**
 * Droopler products.
 *
 * @DrooplerOptionalModule(
 *   id = "d_products",
 *   label = @Translation("Enable Products"),
 *   description = @Translation(" Check this option if you want to make a showcase of your company's products - with tags, categories, and a simple search engine"),
 *   type = "module",
 *   standardlyEnabled = 1,
 * )
 */
class DrooplerProducts extends AbstractOptionalModule {}
