<?php include('header.php') ?>

<section id="content" class="container clearfix">
<header class="page-header">

		<h1 class="page-title"><?php echo SCTEXT('About Us') ?></h1>

		<hr />

		<h2 class="page-description"><?php echo $data['content']->page_header ?></h2>
		
	</header>

	<p>
    <?php echo $data['content']->page_content ?>
    </p>
</section><!-- end #content -->

<?php include('footer.php') ?>