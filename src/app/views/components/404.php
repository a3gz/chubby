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
  <p>This will use the provided handler which can be found at <code>src/fat/Handlers/NotFoundExceptionHandler.php</code></p>

  <h4>Option 2</h4>
  <ul>
    <li>
      Edit or create a copy of <code>src/fat/Handlers/NotFoundExceptionHandler.php</code>.
    </li>
    <li>
      Edit your application's <code>src/app/config/config.php</code> 
      and retur your custom callback under <code>notFoundHandler</code>.
    </li>
    <li>Edit or create a copy of the component: <code>src/app/views/components/404.php</code></li>
  </ul>
</div>