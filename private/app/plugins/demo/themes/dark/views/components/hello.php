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
  <p>DARK VERSION!</p>
  <p>
    Note how the content of this view is different from the light version.
    This is because we have a custom <code>hello.php</code> view for the 
    <strong>dark</strong> theme.
  <hr>
  <p><a href="<?php echo BASE_PATH; ?>/notfound">Click here to see the 404-NotFound handler...</a></p>
  <p><a href="?theme=default">Click here to apply the default theme...</a></p>
</div>