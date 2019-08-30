How to Use This Module

Enable this module and the JSON:API module. When viewing the JSON:API response
for an Image File, you should see a new attribute named "resizable_image_url".

This Image URL supports width and height parameters to allow you to scale and crop
the image to the size you need.

Examples:

Scale and crop to 200x200 thumbnail:
http://headless-drupal.docksal/resizable-images/3/nyan.gif?w=200&h=200

Scale width to 200 pixels and scale height proportionally:
http://headless-drupal.docksal/resizable-images/3/nyan.gif?w=200

Scale height to 500 pixels and scale width proporitionally:
http://headless-drupal.docksal/resizable-images/3/nyan.gif?h=500

Supported Image Formats: jpg, png, gif (animated gifs will only preserve 1st frame after cropping).

