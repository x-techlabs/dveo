Release Files
-------------

    Included in the package is gzip compressed versions of the css and javascript files to be served from a website or CDN. These are stored in the dist directory.
    The regular release files and css minified files are contained within the demo directory with the example configuration files.

    For javascript files these would either be ending in `min.js` or `.js.gz`. For css files these would be ending in `.tiny.css` or `.css.gz`.

Amazon CDN
-----------

    For Amazon S3 to handle gzip content simply set the header `Content-Encoding:gzip` to each file and the CDN will handle the rest for browsers to uncompress on the fly.
    CDN's can also compress files on the fly if the browser sends the correct headers.
    Cloudfront has an option `Compress Objects Automatically` that can be used to compress the regular release files on the fly and send deflate headers. see: http://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/ServingCompressedFiles.html

Apache/Nginx
------------

    Serving Pre-Compressed Release Files
    ------------------------------------

    For Apache and Nginx it is required to force the correct encoding header for a particular mimetype.


    Apache
    ------

    ```
    <FilesMatch "\.(min.js|js.gz)$">
            ForceType application/x-javascript
            Header set Content-Encoding gzip
            AddEncoding gzip gz
            Options +Multiviews
            SetEnv force-no-vary
    </FilesMatch>

    <FilesMatch "\.(tiny.css|css.gz)$">
            ForceType application/x-javascript
            Header set Content-Encoding gzip
            AddEncoding gzip gz
            Options +Multiviews
            SetEnv force-no-vary
    </FilesMatch>
    ```

    Nginx
    -----
    ```
    location ~* \.(min.js|js.gz|tiny.css|css.gz)$ {
                    gzip  off;
                    gzip_static on;
                    add_header  Content-Encoding  gzip;
                    types {
                            text/css gz;
                            application/javascript gz;
                    }
            }
    ```

    Serving Uncompressed Release Files And Compress On The Fly
    ----------------------------------------------------------

    Apache and Ngix can be configured to serve gzipped compressed content on the fly for browsers to decompress using deflate headers.
    This will not use the pre-compressed distribution releases but the normal release files found in the demo directory.

    The purpose of pre-compressed files is to help reduce server load.

    Apache
    ------

    ```
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
    AddOutputFilterByType DEFLATE text/javascript
    ```

    Nginx
    -----

    ```
    gzip_http_version   1.1;
        gzip  on;
        gzip_buffers      16 8k;
        gzip_comp_level   8;
        gzip_min_length  1400;
        gzip_proxied    any;
        gzip_types  text/plain text/css application/x-javascript text/xml application/xml application/xml+rss text/javascript;
        gzip_disable "MSIE [1-6]\.(?!.*SV1)";
        gzip_vary           on;
    ```

