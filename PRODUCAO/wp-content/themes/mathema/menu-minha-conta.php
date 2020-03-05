<!-- BOTÃO -->
<button id="btn-menu-mc" class="btn-menu btn-menu-mc">
      <span id="bar1" class="bar1"></span>
      <span id="bar2" class="bar2"></span>
      <span id="bar3" class="bar3"></span>
</button>
<div id="menu-mc" class="woocommerce-MyAccount-menu">
		<ul>
            <li><a href="<?php echo bloginfo('url'); ?>/minha-conta"><span>Home</span></a></li>
                          
            <?php
            echo do_shortcode( '[groups_member group="Ciranda"]' . wp_nav_menu( array('menu' => 'Ciranda','container' => '','items_wrap' => '%3$s')) . '[/groups_member]' );           
            ?>
           

		</ul>
</div>
<script>
$(document).ready(function() {
      $( "woocommerce-MyAccount-menu .menu" ).remove();
      //abertura e fechamento do menu 
      var clicadoMc = 0;
      $("#btn-menu-mc").click(function(){
            if(clicadoMc == 0){            
                  $("#menu-mc").css("left", 0);
                  $('body').css('overflow', 'hidden');

                  $('#bar1').css({'top' : '12px', 'transform' : 'rotate(45deg)', 'width' : '20px'});
                  $('#bar2').css({'transform' : 'rotate(-45deg)', 'width' : '20px'});
                  $('#bar3').css('opacity', 0);

                  clicadoMc = 1;      	
            }else{
                  $("#menu-mc").css("left", -190);
                  $('body').css("overflow", "auto");

                  $('#bar1').css({'top' : '5px', 'transform' : 'rotate(0)', 'width' : '16px'});
                  $('#bar2').css({'transform' : 'rotate(0)', 'width' : '16px'});
                  $('#bar3').css('opacity', 1);       

                  clicadoMc = 0; 	
            }
      });                
})
</script>