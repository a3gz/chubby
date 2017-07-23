<chubby-scripts>
    <script>
    console.log('Hello', '<?php echo $this->name; ?>');
    </script>
</chubby-scripts>

<chubby-styles>
    <style>
        .hello strong {
            color: blue; 
            font-size: 16px;
        }
        .bye strong {
            color: green;
        }
    </style>
</chubby-styles>

<div class="hello">
    <strong><?php echo "Hello {$this->name}"; ?></strong>
</div>

<div class="bye">
    <strong><?php echo "Bye"; ?></strong>
</div>
