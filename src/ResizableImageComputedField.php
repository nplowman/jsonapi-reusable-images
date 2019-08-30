<?php
namespace Drupal\jsonapi_resizable_images;

use Drupal\Core\Entity\Plugin\DataType\EntityAdapter;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Url;
use Drupal\Core\Field\FieldItemList;
use Drupal\Core\TypedData\ComputedItemListTrait;

class ResizableImageComputedField extends FielditemList implements FieldItemListInterface {
  use ComputedItemListTrait;

  protected function computeValue() {
    /** @var EntityAdapter $parent */
    $parent = $this->getParent();
    $entity = $parent->getEntity();
    $mime = $entity->getMimeType();
    if (in_array($mime, $this->getSupportedTypes())) {
      $parameters = ['fid' => $entity->id(), 'filename' => $entity->getFilename()];
      $options = ['absolute' => TRUE];
      $this->list[0] = $this->createItem(0, Url::fromRoute('jsonapi_resizable_images.get_image', $parameters, $options)->toString());
    }
  }

  private function getSupportedTypes() {
    return [
      'image/jpeg',
      'image/png',
      'image/gif'
    ];
  }

}