<?php include $header;?>
	<header class="top-container">
		<nav class="top-container-nav">
			<?php foreach($links as $link):?>
				<a href="<?php echo htmlspecialchars($link[0]);?>"
				   class="top-container-nav-a">
					<?php echo htmlspecialchars($link[1]);?>
				</a>
			<?php endforeach;?>
		</nav>
	</header>
	<section class="container-title">
		<h1><?php echo htmlentities($short_desc);?></h1>
	</section>
	<section class="list-container-row">
		<?php foreach($plans as $plan):?>
			<li class="list-container-item">
				<button class="item-btn">
					<?php echo htmlentities($plan, ENT_QUOTES, 'UTF-8');?>
				</button>
			</li>
		<?php endforeach;?>
	</section>
<?php include $footer;?>
