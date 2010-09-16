<?php

// help page
function radslide_page_help() {
	?>
	<h2>Need help with radSLIDE?</h2>

	<h3>Creating Slideshows</h3>
	<p>radSLIDE supports multiple slideshows on the same site. To get started, go to the <a href="admin.php?page=radslide_slideshows">Slideshows</a> page and add a new slideshow. Click the <em>New Slideshow</em> header to make the form appear, and then fill it out. The defaults should be good to get you started.</p>
	<p>Each slideshow has a name, a template, and jQuery Cycle options. The name is just for you to keep track of it. The template is how each slide will get displayed. You may want to customize this to fit the design of your website and the placement of your slideshow. radSLIDE uses <a href="http://jquery.malsup.com/cycle/">jQuery Cycle</a> to make the actual slideshows work, and it's very powerful. When creating a slideshow, you can specify what <a href="http://jquery.malsup.com/cycle/options.html">Cycle options</a> you want to do things like create special slideshow transition effects, adjust timing, make next and previous buttons, etc.</p>
	<p>After you add the slideshow it will appear in the list of slideshows. Notice that each slideshow has an ID in that list. You need to use that ID to include the slideshow on your website.</p>

	<h3>Adding images to slideshows</h3>
	<p>On the <a href="admin.php?page=radslide_slideshows">Slideshows</a> page, click <em>Manage</em> next to the slideshow you want to add images to. You'll see a table of slides for that slideshow (or just a single row, if you haven't added any images yet). Fill out the form on the bottom row and click <em>Add Slide</em> to add slides to the slideshow.</p>
	<p>When you click <em>Choose Image</em>, it opens the Wordpress media upload dialog. If the images for your slideshow are not already uploaded use the <em>From Computer</em> tab to upload them. To include images into your slideshow, go to the <em>Media Library</em> tab at the top, click <em>Show</em> next to the image you want, select the size you want and click <em>Insert into Post</em>.</p>
	<p>You can add as many images to a slideshow as you want. Change the number in the Order column to change the order in which the images get displayed. After you make any changes you want to the slideshow you must click the <em>Update</em> button to save your changes.</p>

	<h3>Inserting slideshows into pages or posts</h3>
	<p>After you've set up a slideshow, go to the <a href="admin.php?page=radslide_slideshows">Slideshows</a> page and look for the ID number of the slideshow you want to add (let's pretend it's 3). To insert that slideshow put this where you want it to appear in your page or post:</p>
	<pre>[[radslide 3]]</pre>
	<p>Change 3 to the ID of the slideshow you want to use.</p>

	<h3>Inserting slideshows into a Wordpress theme</h3>
	<p>Again pretending your ID is 3, add this to your theme to display the slideshow:</p>
	<pre>&lt;?php radslide(3); ?&gt;</pre>

	<h3>Stylizing slideshows</h3>
	<p>When radSLIDE displays a slideshow, it puts it inside a div with the id of "radslide-[id]", where [id] is the ID of the slideshow. Each slide is in a div with the class "radslide".</p>

	<p>If you want to put a border around your slideshow, you can do this:</p>

<pre class="code">
&lt;!-- html --&gt;
[[radslide 1]]

&lt;!-- css --&gt;
&lt;style type="text/css"&gt;
#radslide-1 {
	border: 2px solid #333333;
}
&lt;/style&gt;
</pre>

	<p>If you want to have previous and next buttons, you can do something like this:</p>

<pre class="code">
&lt;!-- html --&gt;
[[radslide 1]]
&lt;div id="prev" class="slideshow-button"&gt;&laquo;&lt;/div&gt;
&lt;div id="next" class="slideshow-button"&gt;&raquo;&lt;/div&gt;
</pre>
	
	<p>For those buttons to work your jQuery Cycle options would need to look a little like this:</p>

<pre class="code">{ timeout:2000, speed:500, prev:'#prev', next:'#next' }</pre>

	<p>Lets say you want the slideshow previous and next buttons to overlap the slideshow images. You can do something like this:</p>

<pre class="code">
&lt;!-- html --&gt;
&lt;div id="slideshow"&gt;
	[[radslide 1]]
	&lt;div id="prev" class="slideshow-button"&gt;&laquo;&lt;/div&gt;
	&lt;div id="next" class="slideshow-button"&gt;&raquo;&lt;/div&gt;
&lt;/div&gt;

&lt;!-- css --&gt;
&lt;style type="text/css"&gt;
#slideshow {
	width: 600px;
	height: 300px;
	position: relative;
}
#slideshow .radslide { position: relative; }
#slideshow .slideshow-button {
	position: absolute;
	bottom: 6px;
	color: white;
	background-color: black;
	width: 20px;
	height: 20px;
	text-align: center;
}
#slideshow #prev { right: 43px; }
#slideshow #next { right: right: 16px; }
&lt;/style&gt;
</pre>

	<h3>Uninstalling radSLIDE</h3>
	<p>radSLIDE stores all its data in special tables in the MySQL database. If you deactivate or delete radSLIDE, you don't actually remove the data from your Wordpress site in case you want to add it again. To completely remove all of the data, you must go to the <a href="admin.php?page=radslide_uninstall">Uninstall</a> page to uninstall the data first. Otherwise, it is safe to deactive and reactivate the plugin without losing your slideshow data.</p>
	<?php	
	radslide_rd_credit();
}
?>
