<?php

namespace Drupal\jsonapi_resizable_images\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Image\ImageInterface;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Drupal\Core\File\FileSystemInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Drupal\Component\Utility\Crypt;
use Drupal\Core\Site\Settings;

class ImageController extends ControllerBase {
  public function getImage($fid, $filename) {
    $file = File::load($fid);

    if (!$file) {
      throw new NotFoundHttpException();
    }

    /** @var ImageInterface $image */
    $image = $this->getImageFactory()->get($file->getFileUri());

    $width = \Drupal::request()->query->get('w');
    $height = \Drupal::request()->query->get('h');

    // Scale and Crop if width or height are provided.
    if ($width || $height) {
      $derivative_uri = $this->getDerivativeUri($filename, $width, $height);

      // Only generate new derivative image if necessary.
      if (file_exists($derivative_uri)) {
        $image = $this->getImageFactory()->get($derivative_uri);
      }
      else {
        $operation = ($width && $height) ? 'scale_and_crop' : 'scale';
        $image->apply($operation, ['width' => $width, 'height' => $height]);
        $this->prepareDirectory($derivative_uri);
        $image->save($derivative_uri);
      }
    }

    return new BinaryFileResponse($image->getSource(), 200, [
      'Content-Type' => $image->getMimeType(),
      'Content-Length' => $image->getFileSize(),
    ]);
  }

  protected function prepareDirectory($derivative_uri) {
    $file_system = \Drupal::service('file_system');
    $directory = $file_system->dirname($derivative_uri);
    $success = $file_system->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);

    if (!$success) {
      \Drupal::logger('jsonapi_resizable_images')->error('Failed to create image directory: %directory', ['%directory' => $directory]);
      return FALSE;
    }
  }

  protected function getDerivativeUri($filename, $width = NULL, $height = NULL) {
    if ($width && $height) {
      $derivative_uri = "public://jsonapi_resizable_images/${width}_${height}/" . $filename;
    }
    elseif ($width && !$height) {
      $derivative_uri = "public://jsonapi_resizable_images/w${width}/" . $filename;
    }
    elseif($height && !$width) {
      $derivative_uri = "public://jsonapi_resizable_images/h${width}/" . $filename;
    }

    return $derivative_uri;
  }

  protected function getImageFactory() {
    return \Drupal::service('image.factory');
  }

  protected function getPathToken($filename) {
    $hash_salt = Settings::getHashSalt();
    $private_key = \Drupal::service('private_key')->get();
    return substr(Crypt::hmacBase64('jsonapi_resizable_images' . ':' . $filename, $private_key . $hash_salt), 0, 8);
  }

}