<?php
/* ------ -
 * Return WordPress htaccess custom rules selected
 * --- -
 *
 */
add_filter( 'mod_rewrite_rules', 'wpho_add_htaccess_rules' );
function wpho_add_htaccess_rules( $wordpress_rules ) {

    $custom_rules = '
    ### START Custom WP-htaccess-Optimise rules ###
    ';

    if ( 'true' === get_option( 'Timezoneselection' ) ) {

        $custom_rules .= wpho_get_time_zone_selection();
    }
    if ( 'true' === get_option( 'Preventsindexingoffolderswithoutindexphp' ) ) {

        $custom_rules .= wpho_get_prevents_index_in_folders_without_index_php();
    }
    if ( 'true' === get_option( 'Disabletheserversignature' ) ) {

        $custom_rules .= wpho_get_disable_the_server_signature();
    }
    if ( 'true' === get_option( 'BlockSensitiveFiles' ) ) {

        $custom_rules .= wpho_get_block_sensitive_files();
    }
    if ( 'true' === get_option( 'ProtectWP-includes' ) ) {

        $custom_rules .= wpho_get_protect_wp_includes();
    }
    if ( 'true' === get_option( 'protectauthorlink' ) ) {

        $custom_rules .= wpho_get_protect_author_link();
    }
    if ( 'true' === get_option( 'blockauthorscans' ) ) {

        $custom_rules .= wpho_get_block_author_scans();
    }
    if ( 'true' === get_option( 'Enablingthetrackingofsymboliclinks' ) ) {

        $custom_rules .= wpho_get_enabling_the_tracking_of_symbolic_links();
    }
    if ( 'true' === get_option( 'commentspam' ) ) {

        $custom_rules .= wpho_get_comment_spam();
    }
    if ( 'true' === get_option( 'Protectionagainstfileinjections' ) ) {

        $custom_rules .= wpho_get_protection_against_file_injections();
    }
    if ( 'true' === get_option( 'VariousprotectionsXSSclickjackingandMIME-Typesniffing' ) ) {

        $custom_rules .= wpho_get_various_protections_xss();
    }
    if ( 'true' === get_option( 'Disablethehotlinkingofyourpictures' ) ) {

        $custom_rules .= wpho_get_disable_the_hotlinking_of_your_pictures();
    }
    if ( 'true' === get_option( 'RedirectwithoutWWW' ) ) {

        $custom_rules .= wpho_get_redirect_without_www();
    }
    if ( 'true' === get_option( 'RedirecttoHTTPS' ) ) {

        $custom_rules .= wpho_get_redirect_to_https();
    }
    if ( 'true' === get_option( 'Cachingfilesinthebrowser' ) ) {

        $custom_rules .= wpho_get_caching_files_in_the_browser();
    }
    if ( 'true' === get_option( 'DisabledheadersETags' ) ) {

        $custom_rules .= wpho_get_disabled_headers_etags();
    }
    if ( 'true' === get_option( 'Compressstaticfiles' ) ) {

        $custom_rules .= wpho_get_compress_static_files();
    }

    $custom_rules .='
    ### END Custom WP-htaccess-Optimise rules ###
    ';

    return $wordpress_rules . $custom_rules;
}

/* ------ -
 * All functions to build htaccess
 * --- -
 *
 */
function wpho_get_time_zone_selection() {

    $custom_function_rules = vsprintf(
    '
        ## START Time zone selection
            SetEnv TZ %1$s
   ',
        array(
            get_option( 'wpho_country_selected_htaccess' ),
        )
    );

    return $custom_function_rules;
}


function wpho_get_prevents_index_in_folders_without_index_php() {

    $custom_function_rules =
    '
        ## START Prevents indexing of folders without index.php ##
            Options All -Indexes
            IndexIgnore *
    ';

    return $custom_function_rules;
}

function wpho_get_disable_the_server_signature() {

    $custom_function_rules = vsprintf(
    '
        ## START Disable the server signature ##
            ServerSignature Off
    ',
        array()
    );

    return $custom_function_rules;
}

function wpho_get_block_sensitive_files() {
    $custom_function_rules =
    '
        ## START Block Sensitive Files ##
            <files .htaccess>
                Order deny,allow
                Deny from all
            </files>
            <files readme.html>
                Order deny,allow
                Deny from all
            </files>
            <files license.txt>
                Order deny,allow
                Deny from all
            </files>
            <files install.php>
                Order deny,allow
                Deny from all
            </files>
            <files wp-config.php>
                Order deny,allow
                Deny from all
            </files>
            <files error_log>
                Order deny,allow
                Deny from all
            </files>
            <files fantastico_fileslist.txt>
                Order deny,allow
                Deny from all
            </files>
            <files fantversion.php>
                Order deny,allow
                Deny from all
            </files>
            <Files README.md>
                order deny,allow
                deny from all
            </Files>
            <Files .gitignore>
                order deny,allow
                deny from all
            </Files>
            <Files ~ "^.*\.([Hh][Tt][AaPp])">
                order deny,allow
                deny from all
                satisfy all
            </Files>
    ';

    return $custom_function_rules;
}

function wpho_get_protect_wp_includes() {
    $custom_function_rules =
    '
        ## START Protect WP-includes ##
            <IfModule mod_rewrite.c>
                RewriteEngine On
                RewriteBase /
                RewriteRule ^wp-admin/includes/ - [F,L]
                RewriteRule !^wp-includes/ - [S=3]
                RewriteRule ^wp-includes/[^/]+\.php$ - [F,L] # Ligne à retirer pour les multi-sites
                RewriteRule ^wp-includes/js/tinymce/langs/.+\.php - [F,L]
                RewriteRule ^wp-includes/theme-compat/ - [F,L]
            </IfModule>
    ';

    return $custom_function_rules;
}

function wpho_get_protect_author_link() {
    $custom_function_rules =
    '
        ## START protect author link ##
            <IfModule mod_rewrite.c>
                RewriteCond %%{QUERY_STRING} ^author=([0-9]*)
                RewriteRule .* - [F]
            </IfModule>
    ';

    return $custom_function_rules;
}

function wpho_get_block_author_scans() {
    $custom_function_rules =
    '
        ## START block author scans ##
            <IfModule mod_rewrite.c>
                RewriteEngine On
                RewriteBase /
                RewriteCond %%{QUERY_STRING} (author=\d+) [NC]
                RewriteRule .* - [F]
                #RewriteRule ^(.*)$ / [R=301,L]
            </IfModule>
    ';

    return $custom_function_rules;
}

function wpho_get_enabling_the_tracking_of_symbolic_links() {
    $custom_function_rules =
    '
        ## START Enabling the tracking of symbolic links ##
            <IfModule mod_rewrite.c>
                Options +FollowSymLinks
            </IfModule>
    ';

    return $custom_function_rules;
}

function wpho_get_comment_spam() {
    $custom_function_rules = vsprintf(
    '
        ## Avoid comment spam ##
            <IfModule mod_rewrite.c>
                RewriteCond %%{REQUEST_METHOD} POST
                RewriteCond %%{REQUEST_URI} .wp-comments-post\.php*
                RewriteCond %%{HTTP_REFERER} !.%1$s.* [OR]
                RewriteCond %%{HTTP_USER_AGENT} ^$
                RewriteRule (.*) ^http://%%{REMOTE_ADDR}/$ [R=301,L]
            </IfModule>
    ',
        array(
            wpho_get_site_domain()[0],
        )
    );

    return $custom_function_rules;
}

function wpho_get_protection_against_file_injections() {
    $custom_function_rules =
    '
        ## START Protection against file injections ##
            <IfModule mod_rewrite.c>
                RewriteCond %%{REQUEST_METHOD} GET
                RewriteCond %%{QUERY_STRING} [a-zA-Z0-9_]=http:// [OR]
                RewriteCond %%{QUERY_STRING} [a-zA-Z0-9_]=(\.\.//?)+ [OR]
                RewriteCond %%{QUERY_STRING} [a-zA-Z0-9_]=/([a-z0-9_.]//?)+ [NC]
                RewriteRule .* - [F]
            </IfModule>
    ';

    return $custom_function_rules;
}

function wpho_get_various_protections_xss() {
    $custom_function_rules =
    '
        ## START Various protections ( XSS, clickjacking and MIME-Type sniffing )
            <ifModule mod_headers.c>
                Header set X-XSS-Protection "1; mode=block"
                Header always append X-Frame-Options SAMEORIGIN
                Header set X-Content-Type-Options: "nosniff"
            </ifModule>
    ';

    return $custom_function_rules;
}

function wpho_get_disable_the_hotlinking_of_your_pictures() {
    $custom_function_rules = vsprintf(
    '
        ## START Disable the hotlinking of your pictures ##
            <IfModule mod_rewrite.c>
                RewriteEngine On
                RewriteCond %%{HTTP_REFERER} !^$
                RewriteCond %%{HTTP_REFERER} !^http(s)?://(www\.)?%1$s [NC]
                RewriteRule \.(jpg|jpeg|png|gif)$ http://fakeimg.pl/400x200/?text=Pas_touche_aux_images [NC,R,L]
            </IfModule>
    ',
        array(
            wpho_get_site_domain()[0],
        )
    );

    return $custom_function_rules;
}

function wpho_get_redirect_without_www() {
    $custom_function_rules =
    '
        ## START Redirect without WWW ##
            <IfModule mod_rewrite.c>
                RewriteEngine On
                RewriteBase /
                RewriteCond %%{HTTP_HOST} ^www\.(.*)$ [NC]
                RewriteRule ^(.*)$ http://%%1/$1 [R=301,L]
            </IfModule>
    ';

    return $custom_function_rules;
}

function wpho_get_redirect_to_https() {
    $custom_function_rules =
    '
        ## START Redirect to HTTPS ##
            <IfModule mod_rewrite.c>
                RewriteEngine On
                RewriteCond     %%{SERVER_PORT} ^80$
                RewriteRule     ^(.*)$ https://%%{SERVER_NAME}%%{REQUEST_URI} [L,R]
            </IfModule>
    ';

    return $custom_function_rules;
}

function wpho_get_caching_files_in_the_browser() {
    $custom_function_rules =
    '
        ## START Caching files in the browser ##
            <IfModule mod_expires.c>
                ExpiresActive On
            
                # Perhaps better to whitelist expires rules? Perhaps.
                ExpiresDefault                              "access plus 1 month"
            
                # cache.appcache needs re-requests in FF 3.6 (thanks Remy ~Introducing HTML5)
                ExpiresByType text/cache-manifest           "access plus 0 seconds"
            
                # Your document html
                ExpiresByType text/html                     "access plus 0 seconds"
            
                # Data
                ExpiresByType text/xml                      "access plus 0 seconds"
                ExpiresByType application/xml               "access plus 0 seconds"
                ExpiresByType application/json              "access plus 0 seconds"
                ExpiresByType application/pdf               "access plus 0 seconds"
            
                # Feed
                ExpiresByType application/rss+xml           "access plus 1 hour"
                ExpiresByType application/atom+xml          "access plus 1 hour"
            
                # Webfonts
                ExpiresByType application/x-font-ttf        "access plus 1 month"
                ExpiresByType font/opentype                 "access plus 1 month"
                ExpiresByType application/x-font-woff       "access plus 1 month"
                ExpiresByType application/x-font-woff2      "access plus 1 month"
                ExpiresByType image/svg+xml                 "access plus 1 month"
                ExpiresByType application/vnd.ms-fontobject "access plus 1 week"
            
                # Media: images, video, audio
                ExpiresByType image/gif                     "access plus 1 month"
                ExpiresByType image/png                     "access plus 1 month"
                ExpiresByType image/PNG                     "access plus 1 month"
                ExpiresByType image/jpeg                    "access plus 1 month"
                ExpiresByType image/jpg                     "access plus 1 month"
                ExpiresByType video/ogg                     "access plus 1 month"
                ExpiresByType audio/ogg                     "access plus 1 month"
                ExpiresByType video/mp4                     "access plus 1 month"
                ExpiresByType video/webm                    "access plus 1 month"
            
                # HTC files  (css3pie)
                ExpiresByType text/x-component              "access plus 1 month"
            
                # CSS and JavaScript
                ExpiresByType text/css                      "access plus 3 week"
                ExpiresByType application/javascript        "access plus 3 week"
            
                # Favicon (cannot be renamed)
                ExpiresByType image/x-icon                  "access plus 1 week"
                ExpiresByType application/x-shockwave-flash "access plus 1 week"
            </IfModule>
            <ifModule mod_headers.c>
                <filesMatch "\.(ico|pdf|flv|jpg|jpeg|jpe?g|png|PNG|gif|swf|mp3|mp4)$">
                    Header set Cache-Control "public"
                </filesMatch>
                <filesMatch "\.(css)$">
                    Header set Cache-Control "public"
                </filesMatch>
                <filesMatch "\.(js)$">
                    Header set Cache-Control "private"
                </filesMatch>
                <filesMatch "\.(x?html?|php)$">
                    Header set Cache-Control "private, must-revalidate"
                </filesMatch>
            </ifModule>
            # ----------------------------------------------------------------------
            # Proper MIME type for all files
            # ----------------------------------------------------------------------
            
            AddType application/javascript                   js jsonp
            AddType application/json                         json
            
            # Audio
            AddType audio/ogg                                oga ogg
            AddType audio/mp4                                m4a f4a f4b
            
            # Video
            AddType video/ogg                                ogv
            AddType video/mp4                                mp4 m4v f4v f4p
            AddType video/webm                               webm
            AddType video/x-flv                              flv
            
            # SVG
            #   Required for svg webfonts on iPad
            #   twitter.com/FontSquirrel/status/14855840545
            AddType     image/svg+xml                        svg svgz
            AddEncoding gzip                                 svgz
            
            # Webfonts
            AddType application/vnd.ms-fontobject            eot
            AddType application/x-font-ttf                   ttf ttc
            AddType font/opentype                            otf
            AddType application/x-font-woff                  woff
            
            # Assorted types
            AddType image/x-icon                             ico
            AddType image/webp                               webp
            AddType text/cache-manifest                      appcache manifest
            AddType text/x-component                         htc
            AddType application/xml                          rss atom xml rdf
            AddType application/x-chrome-extension           crx
            AddType application/x-opera-extension            oex
            AddType application/x-xpinstall                  xpi
            AddType application/octet-stream                 safariextz
            AddType application/x-web-app-manifest+json      webapp
            AddType text/x-vcard                             vcf
            AddType application/x-shockwave-flash            swf
            AddType text/vtt                                 vtt
    ';

    return $custom_function_rules;
}

function wpho_get_disabled_headers_etags() {
    $custom_function_rules =
    '
        ## START Disabled headers ETags ##
            Header unset ETag
            FileETag None
    ';

    return $custom_function_rules;
}

function wpho_get_compress_static_files() {
    $custom_function_rules =
    '
        ## START Compress static files ##
        # Compress all output labeled with one of the following MIME-types
            <IfModule mod_deflate.c>
                <IfModule mod_filter.c>
                    AddOutputFilterByType DEFLATE            application/atom+xml
                    AddOutputFilterByType DEFLATE            application/javascript
                    AddOutputFilterByType DEFLATE            application/x-javascript
                    AddOutputFilterByType DEFLATE            application/json
                    AddOutputFilterByType DEFLATE            application/rss+xml
                    AddOutputFilterByType DEFLATE            application/vnd.ms-fontobject
                    AddOutputFilterByType DEFLATE            application/x-font
                    AddOutputFilterByType DEFLATE            application/x-font-opentype
                    AddOutputFilterByType DEFLATE            application/x-font-otf
                    AddOutputFilterByType DEFLATE            application/x-font-truetype
                    AddOutputFilterByType DEFLATE            application/x-font-ttf
                    AddOutputFilterByType DEFLATE            application/xhtml+xml
                    AddOutputFilterByType DEFLATE            application/xml
                    AddOutputFilterByType DEFLATE            font/otf
                    AddOutputFilterByType DEFLATE            font/ttf
                    AddOutputFilterByType DEFLATE            font/opentype
                    AddOutputFilterByType DEFLATE            image/svg+xml
                    AddOutputFilterByType DEFLATE            image/x-icon
                    AddOutputFilterByType DEFLATE            text/css
                    AddOutputFilterByType DEFLATE            text/html
                    AddOutputFilterByType DEFLATE            text/javascript
                    AddOutputFilterByType DEFLATE            text/plain
                    AddOutputFilterByType DEFLATE            text/x-component
                    AddOutputFilterByType DEFLATE            text/xhtml
                    AddOutputFilterByType DEFLATE            text/xml
                </IfModule>
                <IfModule mod_setenvif.c>
                    <IfModule mod_headers.c>
                        SetEnvIfNoCase ^(Accept-EncodXng|X-cept-Encoding|X{15}|~{15}|-{15})$ ^((gzip|deflate)\s*,?\s*)+|[X~-]{4,13}$ HAVE_Accept-Encoding
                        RequestHeader append Accept-Encoding "gzip,deflate" env=HAVE_Accept-Encoding
                    </IfModule>
                </IfModule>
                BrowserMatch ^Mozilla/4 gzip-only-text/html
                BrowserMatch ^Mozilla/4\.0[678] no-gzip
                #BrowserMatch \bMSIE !no-gzip !gzip-only-text/html
                SetEnvIfNoCase Request_URI \.(?:gif|jpe?g|png)$ no-gzip dont-vary
                Header append Vary User-Agent env=!dont-vary
            </IfModule>
    ';

    return $custom_function_rules;
}

function wpho_get_site_domain() {

    $site_home_url = get_site_url();
    preg_match( '/\/\/(.*)$/', $site_home_url, $site_domain );
    $site_domains[] = $site_domain[1];

    return $site_domains;
}
