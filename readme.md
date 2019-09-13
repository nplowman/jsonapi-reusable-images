# Installation
This module does not require any configuration. Simply enable this module
and the JSON:API module.

# Usage
1. After enabling the module, you should see an additional property in the JSON
response for File Entities named 'resizable_image_url':  
 
```
attributes: {
  drupal_internal__fid: 3,
  langcode: "en",
  filename: "nyan.gif",
  uri: {
    value: "public://2019-08/nyan.gif",
    url: "/sites/default/files/2019-08/nyan.gif"
  },
  filemime: "image/gif",
  filesize: 761850,
  status: true,
  created: "2019-08-30T22:53:41+00:00",
  changed: "2019-08-30T22:53:48+00:00",
  resizable_image_url: "http://example.com/resizable-images/3/nyan.gif"
}
```

2. You can use 'w' and 'h' query parameters with this URL to scale and crop the 
image to the size you need.

## Examples:

Scale and crop to 200x200 thumbnail:

```http://headless-drupal.docksal/resizable-images/3/nyan.gif?w=200&h=200```

Scale width to 200 pixels and scale height proportionally:
```http://headless-drupal.docksal/resizable-images/3/nyan.gif?w=200```

Scale height to 500 pixels and scale width proporitionally:
```http://headless-drupal.docksal/resizable-images/3/nyan.gif?h=500```

# Notes
- Supported Image Formats: jpg, png, gif 
- Note that animated gifs can be cropped, but only the first frame will be 
available in derivative image.
- While the module was built with the use case of JSON:API in mind, it is not a 
hard requirement. The resizable_image_url is attached to file entities as a computed
property, and can be also used with the core REST API and custom solutions.

# Similar Modules
[Consumer Image Styles](https://www.drupal.org/project/consumer_image_styles)
Consumer Image Styles takes a different approach to solving the same problem as this module.
The advantage of JSON Resizable Images is that it allows you to request a resized
image with any width and height on-the-fly, while Consumer Image Styles requires
you configure preset Image Styles on the Drupal side. 




