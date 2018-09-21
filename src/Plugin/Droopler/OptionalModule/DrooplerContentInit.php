<?php

namespace Drupal\droopler\Plugin\Droopler\OptionalModule;

/**
 * Droopler content init.
 *
 * @DrooplerOptionalModule(
 *   id = "d_content_init",
 *   label = @Translation("Fill the website with Demo content"),
 *   description = @Translation("Check this to add some example content to your website. Feel free to modify this content and adapt it to your needs."),
 *   type = "module",
 *   standardlyEnabled = 1,
 *   forceToInstall = 1,
 * )
 */
class DrooplerContentInit extends AbstractOptionalModule {

  /**
   * {@inheritdoc}
   */
  public function submitForm(array $formValues) {
    \Drupal::moduleHandler()->invoke('d_content_init', 'd_content_init_create_all');
    \Drupal::moduleHandler()->invoke('d_content_init', 'd_content_init_add_footer');
  }

}
