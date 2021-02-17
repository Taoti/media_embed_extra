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

      // Check if height and width properties have been provided.
      $height = $node->getAttribute('data-height');
      $width = $node->getAttribute('data-width');

      // Resize proportionally if only one value was provided
      if (empty($width) && !empty($height) ||
          empty($height) && !empty($width)) {
        if (empty($width)) {
          $width = $height * $media->{$image_field}->width / $media->{$image_field}->height;
        }
        else {
          $height = $width * $media->{$image_field}->height / $media->{$image_field}->width;
        }
      }
      if (!empty($height)) {
        $media->{$image_field}->height = $height;
      }
      if (!empty($width)) {
        $media->{$image_field}->width = $width;
      }
    }
  }

}
