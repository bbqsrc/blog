
      <footer id='blog-footer'><span id='smiley'>:)</span></footer>
      
    </div>
    <?php wp_footer(); $options = get_option("brendan_options");
        if (array_key_exists('footer', $options)) {
                echo $options['footer'];
        }?>
  </body>
</html>
