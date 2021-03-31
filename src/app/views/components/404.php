<chubby-styles>
  <style>
    .not-found {
      max-width: 600px;
      margin: 0 auto;
      padding: 2em 0 2em 0;
    }
  </style>
</chubby-styles>

<div class="not-found">
  <h2>Error 404: Page not found.</h2>
  <h3>Customize your 404 response:</h3>
  <h4>Option 1</h4>
  <p>Edit the component: <code>src/app/views/components/404.php</code></p>

  <h4>Option 2</h4>
  <ul>
    <li>
      Create a copy of <code>vendor/a3gz/chubby/src/fat/Handlers/NotFoundExceptionHandler.php</code>
      and paste it somewhere in your application's tree.
    </li>
    <li>
      Edit your application's <code>src/app/config/config.php</code> 
      and assign your custom callback under <code>notFoundHandler</code>.
    </li>
  </ul>
</div>