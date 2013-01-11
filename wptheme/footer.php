
      <footer id='blog-footer'>
        <?php $options = get_option("brendan_options");
          if (array_key_exists('footer', $options)) {
            echo $options['footer'];
          }
        ?>
      </footer> 
    </div>
    <?php wp_footer(); if (array_key_exists('before_footer', $options)) {
      echo $options['before_footer'];
    }?>
  </body>
</html>
