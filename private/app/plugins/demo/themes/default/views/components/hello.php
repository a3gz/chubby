<chubby-scripts>
  <script>
    console.log('Hello', '<?php echo $this->name; ?>');
  </script>
</chubby-scripts>
<?php
$this->requireCss([
  "/public/assets/themes/{$this->getThemeName()}/css/styles.css"
]);
?>
<div class="hello">
  <p><strong><?php echo "Hello {$this->name}!"; ?></strong></p>
  <p>Open this page's source code to see how in-line styles and scripts were embedded in the final HTML document.</p>
  <hr>
  <p><a href="<?php echo BASE_PATH; ?>/notfound">Click here to see the 404-NotFound handler...</a></p>
  <p><a href="?theme=dark">Click here to apply the dark theme...</a></p>
</div>