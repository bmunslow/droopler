<?php

namespace Drupal\droopler\Plugin\Droopler\OptionalModule;

/**
 * Droopler blog.
 *
 * @DrooplerOptionalModule(
 *   id = "d_products_init",
 *   label = @Translation("Enable Products init"),
 *   type = "module",
 *   dependencies = {
 *    "d_products",
 *   },
 *   standardlyEnabled = 1,
 * )
 */
class DrooplerProductsInit extends AbstractOptionalModule {}
