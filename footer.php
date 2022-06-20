			   </div>
            </div>


            <!-- footer -->
            <footer class="footer" role="contentinfo">

                <?php
                $footer = get_page_by_path('footer'); 
                $content = apply_filters( 'the_content', $footer->post_content ); 
                echo $content;

                ?>
                

                <!-- copyright -->

                <!-- /copyright -->

            </footer>
            <!-- /footer -->

        </div>
        <!-- /wrapper -->

        <?php wp_footer(); ?>


    </body>
    </html>
