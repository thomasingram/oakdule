<!DOCTYPE html>
<html lang="en-GB">
<head>
	<meta charset="utf-8" />
	
	<?php
	if ($title)
	{
	    $title = $title . ' &#8226; ';
	}
	
	$title .= 'Oakdule';
	?>
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" href="<?php echo base_url('css/default.css'); ?>" />
	<link rel="icon" href="<?php echo base_url('favicon.ico'); ?>" />
</head>
<body>
	<header>
		<div id="header-inner">
			<a class="logo" href="/">Oakdule</a>
			
			<?php if (isset($page) && $page == 'list' && isset($user)): ?>
			
			<h1><?php echo $user['name']; ?>&#8217;s habits</h1>
			<a class="nav-auxiliary" href="<?php echo site_url('user/logout'); ?>">Sign out <?php echo $user['name']; ?></a>
			<form action="<?php echo site_url('list/add'); ?>" method="post">
				<fieldset>
					<img src="<?php echo $user['profile_image_url']; ?>" alt="<?php echo $user['name']; ?>" height="48" width="48" /><label class="hidden" for="name">Name:</label><input id="name" name="name" type="text" required="required" placeholder="Create a new habit…" />
				</fieldset>
				<input class="hidden" name="submit" type="submit" value="Create habit" />
			</form>
			
			<?php elseif (!isset($user)): ?>
			
			<a class="nav-auxiliary" href="<?php echo site_url('user/sign_in_twitter'); ?>">Sign in with Twitter</a>
			<hgroup>
				<h1>A little effort <span>each day</span> goes a long way.</h1>
				<h2>Oakdule helps you chop those long-term goals down to size by recording and tracking your daily efforts.</h2>
			</hgroup>
			<a class="twitter" href="<?php echo site_url('user/sign_in_twitter'); ?>">Sign in with Twitter</a>
			<?php endif; ?>
		</div>
	</header>
	<div id="content">