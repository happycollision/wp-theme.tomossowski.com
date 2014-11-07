<?php 
global $content_exists;
if($content_exists){?>

</div><!--main_content-->

<?php }?>

<footer>
	<nav>
		<ul>
			<?php global $happycol_main_nav; echo $happycol_main_nav; ?>
		</ul>
	</nav>
	
    <span id="happycol_tag">This site is the result of a <a href="http://happycollision.com" target="_blank">Happy Collision</a>.</span>
</footer><!--footer-->
<?php wp_footer();?>

</body>
</html>