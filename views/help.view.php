
<h2><?php echo $title; ?></h2>
<p>
This application is designed to store notes, misc. data, lists, and to do lists.
It's like a wiki, except that it has an index along the side for all your
pages, and categories.
</p>
<h2>Important Concepts</h2>
<p>
There are a number of important comcepts worth knowing in order to make the
most of this software.
</p>
<h3>The Content Directory</h3>
<p>
There is a directory (normally called "content") which contains all your files.
They can be stored in directories, which would represent categories. For example,
if you have To Do lists, you could store them in a directory called to-do.
</p>
<h3>The Table of Contents File</h3>
<p>
There is also a "table of contents" file (normally called "toc.md"), which
provides a map of sorts to all your files. This file is in "vimwiki" format.
It has links which are navigable in Vim, when it has the vimwiki plugin
installed. Using Vim, you can create new files and add directories. This package
was originally designed as a thin shell over the top of Vim/vimwiki.
</p>
<h3>The Database</h3>
<p>
There is a SQLite database behind this application, which stores all the filenames
and directory names from your content directory. The point of this database is
to speed things up. On the left side of the pages is a HTML equivalent of the
table of contents file spoken of earlier. There are two ways to derive this
date: scan all the directories at every page load, or store the information in
some store of backing store, in our case, a database.
</p>
<h2>Managing with Vim</h2>
<p>
This content files were originally designed to be managed by Vim. Using the vimwiki
plugin, one would use the table of contents file to navigate the different
pages. From that file you can create new links and files. Vimwiki allows you to
jump directly into the pages you want to edit.
</p>
<h2>Managing with PKBase2</h2>
<p>
Originally, PKBase (version 1) was designed to allow me to share my notes and
to do lists with my wife. I would edit everything on the back end in Vim, and
I could share the results in a web interface with my wife. PKBase has been
significantly updated in version 2. 
As an alternative to Vim/vimwiki, you can now use PKBase2 to manage your pages. There
are buttons to add, edit and delete pages.
</p>
<h2>Synchronization</h2>
<p>
One problem built in to this software is that, if you add or delete files in Vim,
your database (which controls the on-screen index) will go out of sync with 
the content directory. You can compensate for this by using the "Rebuild Index"
button in the software. This will re-scan your directories and files, and update
the database and you on screen index. It will also recreate your table of contents
file. If you're simply editing files in Vim, no additional action is needed. The
synchronization process can take up to 30 seconds.
</p>
<h2>Directories</h2>
<p>
File directories serve as the "categories" or "topics" for the various types
of information you store. You can add and delete directories/topics by
pressing the <strong>Topics</strong> button. Be aware that deleting a
directory which contains "children" (pages) also deletes those pages.
</p>
<h2>File and Directory Names</h2>
<p>
When you create file and directory names, you may use multiple words, like
"My To-Do Lists". However, there are two important things to consider. First,
do not include spaces in your file or directory names, or any other odd
non-alphabetic characters. Spaces in filenames will be automatically converted
to dashes. Non-alphabetic characters will be removed. The titles in the index
and the table of contents page are derived from the file and directory names.
When these are shown, they will substituted spaces for dashes (on screen only),
and capitalize the words in the page names.
</p>
<h2>Markdown</h2>
<p>
Markdown is a form of markup (like HTML) designed to ease the task of adding
styling to text. PKBase2 is designed to handle markdown files. It converts your
markdown files to HTML on the fly. If you're not using markdown, do this:
Change the <code>suffix</code> value in your configuration file to 
whatever file extension you plan to use. PKBase2 will automatically convert
markdown files, and will leave whatever other files you use alone. So, for
example, if you prefer to create file using HTML format (really?), these
will show up on screen exactly as you wrote them. Anything else will simply
show up as text.
</p>
<h2>Configuration</h2>
<p>
Editing the configuration file at <code>config/config.ini</code> will allow you
to change the title and "slogan" of your site. You can also change your 
content directory, and the extension/suffix of your pages.
</p>

