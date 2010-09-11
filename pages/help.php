<?php

// help page
function radslide_page_help() { ?>

<h2>Need help with radSLIDE?</h2>

<h3>Creating Slideshows</h3>
<p>radSLIDE supports multiple slideshows on the same site. To get started, go to the <a href="admin.php?page=radslide_slideshows">Slideshows</a> page and add a new slideshow. Click the <em>New Slideshow</em> header to make the form appear, and then fill it out.</p>
<p>Each slideshow has a name, a template, and jQuery Cycle options. The name is just for you to keep track of it. The template is how each slide will get displayed. You'll want to customize this to fit the design of your website and the placement of your slideshow. radSLIDE uses <a href="http://jquery.malsup.com/cycle/">jQuery Cycle</a> to make the actual slideshows work, and it's very powerful. When creating a slideshow, you can specify what Cycle options you want to do things like create special slideshow effects, make next and previous buttons, etc.</p>
<p>After you add the slideshow it will appear in the list of slideshows. Notice that each slideshow has an ID in that list. You need to use that ID to include the slideshow on your website.</p>

<h3>Adding images to slideshows</h3>
<p>On the <a href="admin.php?page=radslide_slideshows">Slideshows</a> page, click <em>Manage</em> next to the slideshow you want to add images to. You'll see a table of slides for that slideshow (or just a single row, if you haven't added any images yet). Fill out the form on the bottom row and click <em>Add Slide</em> to add slides to the slideshow.</p>
<p>When you click <em>Choose Image</em>, it opens the image upload box. To include the image you need to first upload it, then go to <em>Media Library</em> tab at the top, click <em>Show</em> next to the image you uploaded, and at the bottom of that image's display click <em>Insert into Post</em>.</p>
<p>You can add as many images to a slideshow as you want. Change the number in the Order column to change the order the images get displayed. After you make any changes you want to the slideshow you must click the <em>Update</em> button to save your changes.</p>

<h3>Inserting slideshows into pages or posts</h3>
<p>After you've set up a slideshow, go to the <a href="admin.php?page=radslide_slideshows">Slideshows</a> page and look for the ID number of the slideshow you want to add (let's pretend it's 3). When you edit a page or a post, to insert that slideshow put this where you want it to appear:</p>
<pre>[[radslide 3]]</pre>
<p>Change 3 to whatever the ID of the slideshow you want to add is.</p>

<h3>Inserting slideshows into a Wordpress theme</h3>
<p>Again pretending your ID is 3, add this to your theme to display the slideshow:</p>
<pre>&lt;?php radslide(3); ?&gt;</pre>

<h3>Stylizing slideshows</h3>
<p>When radSLIDE displays a slideshow, it puts it inside a div with the id of "radslide-#", where # is the ID of the slideshow. Each slide is a in a div with the class id "radslide".</p>

<p>Lets say you want the slideshow to have a gray border around it and previous and next buttons overlapping the image. You can do something like this:</p>

<pre>
&lt;!-- styles --&gt;
&lt;style type="text/css"&gt;
#slideshow {
  width: 600px;
  height: 300px;
  position: relative;
}
#slideshow #radslide-1 { border: 2px solid #333333; }
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

&lt;!-- the slideshow --&gt;
&lt;div id="slideshow"&gt;
  [[radslide 1]]
  &lt;div id="prev" class="slideshow-button"&gt;&laquo;&lt;/div&gt;
  &lt;div id="next" class="slideshow-button"&gt;&raquo;&lt;/div&gt;
&lt;/div&gt;
</pre>

<p>Of course, for those buttons to actually work, your jQuery Cycle options would need to look a little like this:</p>

<pre>{ delay:2000, speed:500, prev:'#prev', next:'#next' }</pre>

<h3>Uninstalling radSLIDE</h3>
<p>radSLIDE stores all its data in special tables in the MySQL database. If you deactivate and delete radSLIDE, you don't actually remove the data from your Wordpress site in case you want to add it again. To completely remove all of the data, you must go to the <a href="admin.php?page=radslide_uninstall">Uninstall</a> page to uninstall the data first. Then you can deactivate and delete the plugin.</p>

<?php }

?>
