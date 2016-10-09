<?php include $header;?>
	<header class="top-container">
		<nav class="top-container-nav">
			<?php foreach($links as $link):?>
				<a href="<?php echo htmlspecialchars($link[0]);?>" class="top-container-nav-a"><?php echo htmlspecialchars($link[1]);?></a>
			<?php endforeach;?>
		</nav>
	</header>
	<section class="container-title">
		<div class="doc-body">
			<div class="title-underline">
				<h2><?php echo htmlspecialchars($title_one);?></h2>
			</div>
			<p class="doc-text">
				<?php echo htmlspecialchars($bloc_one);?>
			</p>
			<div class="title-underline">
				<h2><?php echo htmlspecialchars($title_two);?></h2>
			</div>
			<p class="doc-text">
				<?php echo htmlspecialchars($bloc_two);?>
			</p>
			<div class="title-underline">
				<h2><?php echo htmlspecialchars($title_three);?></h2>
			</div>
			<p class="doc-text">
				<?php echo htmlspecialchars($bloc_three);?>
			</p>
		</div>
	</section>
<?php include $footer;?>