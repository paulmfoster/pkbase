
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

There is a directory which contains all your files.
They can be stored in directories, which would represent categories. For example,
if you have To Do lists, you could store them in a directory called <code>to-do</code>.
You can set this directory in the <code>config/config.ini</code> file.

</p>

<h2>Managing with Vim</h2>

<p>

These content files were originally designed to be managed by Vim. Using the vimwiki
plugin, one would use the table of contents file to navigate the different
pages. From that file you can create new links and files. Vimwiki allows you to
jump directly into the pages you want to edit. Of course, you could use any editor
you like. With version 3, you may create and edit files in Markdown, Vim outline, plain
text, or HTML format.

</p>

<h2>Managing with PKBase3</h2>
<p>
Originally, PKBase (version 1) was designed to allow me to share my notes and
to do lists with my wife. I would edit everything on the back end in Vim, and
I could share the results in a web interface with my wife. PKBase was
significantly updated in version 2. 
As an alternative to Vim/vimwiki, you could now use PKBase2 to manage your pages. There
are buttons to add, edit and delete pages. Version 3 makes no major changes,
except to allow you to use a wider variety of files.
</p>
<h2>Directories</h2>
<p>
File directories serve as the "categories" or "topics" for the various types
of information you store. You can add and delete directories/topics by
pressing the <strong>Topics</strong> button. Be aware that deleting a
directory which contains "children" (pages) also deletes those pages. In adding
a topic, you must select a "parent" topic for it to be in.
</p>
<h2>File and Directory Names</h2>
<p>

When you create file and directory names, you may use multiple
words, like "My To-Do Lists". However, there are two important
things to consider. First, if you create these files with your
editor, do not include spaces in your file or directory names, or
any other odd non-alphabetic characters. In PKBase, spaces in
filenames will be automatically converted to dashes.
Non-alphabetic characters will be removed. The titles in the
index and the table of contents page are derived from the file
and directory names.  When these are shown, spaces will be
substituted for dashes (on screen only), and words will be
capitalized in the page and directory names. If you manage your
files in PKBase instead of your editor, you may make file and
directory names anything you like. Spaces will be turned into dashes
or hyphens, and capitals will be lower cased.

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

