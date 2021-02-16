<?php

namespace Drupal\media_embed_extra\Plugin\Filter;

use Drupal\media\MediaInterface;
use Drupal\media\Plugin\Filter\MediaEmbed as CoreMediaEmbed;

/**
 * Provides a filter to embed media items using a custom tag.
 *
 * @Filter(
 *   id = "media_embed",
 *   title = @Translation("Embed media"),
 *   description = @Translation("Embeds media items using a custom tag, <code>&lt;drupal-media&gt;</code>. If used in conjunction with the 'Align/Caption' filters, make sure this filter is configured to run after them."),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_TRANSFORM_REVERSIBLE,
 *   settings = {
 *     "default_view_mode" = "default",
 *     "allowed_view_modes" = {},
 *     "allowed_media_types" = {},
 *   },
 *   weight = 100,
 * )
 *
 * @internal
 */
class MediaEmbed extends CoreMediaEmbed {

  /**
   * {@inheritdoc}
   */
  protected function applyPerEmbedMediaOverrides(\DOMElement $node, MediaInterface $media) {
    parent::applyPerEmbedMediaOverrides($node, $media);
    if ($image_field = $this->getMediaImageSourceField($media)) {
      $settings = $media->{$image_field}->getItemDefinition()->getSettings();
      if (!empty($node->getAttribute('data-height'))) {
        $media->{$image_field}->height = $node->getAttribute('data-height');
      }
      if (!empty($node->getAttribute('data-width'))) {
        $media->{$image_field}->width = $node->getAttribute('data-width');
      }
    }
  }

}
