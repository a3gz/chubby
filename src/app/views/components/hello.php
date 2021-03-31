<chubby-scripts>
  <script>
    console.log('Hello', '<?php echo $this->name; ?>');
  </script>
</chubby-scripts>

<chubby-styles>
  <style>
    .hello {
      max-width: 600px;
      margin: 0 auto;
      padding: 2em 0 2em 0;
    }

    .hello strong {
      color: blue;
      font-size: 16px;
      padding: 0;
      margin: 0;
    }
  </style>
</chubby-styles>

<div class="hello">
  <p><strong><?php echo "Hello {$this->name}!"; ?></strong></p>
  <p>Open this page's source code to see how in-line styles and scripts were embedded in the final HTML document.</p>
</div>