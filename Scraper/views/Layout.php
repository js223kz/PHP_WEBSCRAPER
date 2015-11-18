<?php
/**
 * Created by PhpStorm.
 * User: mkt
 * Date: 2015-11-13
 * Time: 15:16
 */

namespace views;


class Layout
{
    public function render($view, $list = null) {
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
        </head>
        <body>
          <div class="container">
              ' . $view. '
              ' . $list. '
          </div>

         </body>
      </html>
    ';
    }
}