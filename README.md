# Paul's Knowledge Base (PKBase)

PKBase is like a wiki in web format, except that you also have
an index on screen for your files. Most wikis store your data/documents
in one directory. PKBase stores files in various subdirectories
of your choosing. You may search your documents, or go to a
specific directory/topic to see a given file.

## Use Case

Here's my use case for this software. I dump a lot of random information
into my personal wiki, like details on appliances I've bought, cheat
sheets for software I use, construction project details, etc. My wife
can't access this information normally (it's on my personal machine),
and doesn't have the expertise to handle Vim or Vimwiki. Still, she
might need access to some of this information. So I run PKBase on a web
server on my machine, and she can surf to my webserver from her machine.

## Prerequisites

- A running web server you have access to.
- PHP installed on your web server.
- A place to house and edit your knowledge base files.

## Installation

Create a directory under your webserver which will hold this
software, and copy it there. Edit your `config/config.ini`.
Change the following lines to suit your needs:

```
content_dir = "/home/paulf/vimwiki"
site_title = "Paul's Knowledge Base"
slogan = "All you need to know..."
```

Any values after the equals sign which contain spaces must be
enclosed in double quotes, as above.

You may now add content (files and subdirectories) to the
`content_dir` you selected above. I keep content in my home
directory, rather than under the webserver directory hierarchy.
You're free to choose differently.

## Operation

The software displays a listing of the directories and files in
your top level content directory along the left side of the page.
You may click on any of these links to either view the file, or
explore the contents of the directory.

Typically, it's expected that you would manage your files in the
editor of your choice. Prior versions of this software allowed for the user
to add, edit and delete pages from the website. However, this
functionality has been abandoned. This is not a "groupware" project. It is
designed so that one person maintains the knowledge base, and others may
use it.

The software can parse markdown, HTML, text and vim outline files.
Extensions must be `.md`, `.html`, `.txt` or `.otl`, respectively. It will
also display images.

## File And Directory Names

Prior versions of this software offered a fair latitude in file and
directory naming. This also has been abandoned. It's expected that
filenames will consist of one or more words, initially capitalized, with
underscores in between words. In creating titles for display (as in the
sidebar), underscores will be replaced by spaces. "Titles" as they appear
in the actual documents, should be written out in the actual documents at
the beginning, using whatever method the particular file's markup
specifies. For markdown files, this would be as follows:

```
# My Title
```

For HTML files, it would be:

```
<h1>My Title</h1>
```

## Search

There is a "search" area in the upper right corner of the page.
You may enter any search term there and hit the button. Documents
which match your search will be listed, and you may click on one
to see it. This searches your entire catalog of files.

## Links To Other Documents

There are times when you may want to include an image or link to some other
document not contained in your main content directory. Such links, when
rendered in PKBase, may not work. The reason is complicated. Assuming you're
basing your Vimwiki content in `~/vimwiki`, you might have an images
directory in that hierarchy. The links in your markdown documents refer to,
say, images in your `~/vimwiki/images` directory. They're relative links,
relative to your vimwiki directory. When PKBase interprets your links, it
keeps them just like you wrote them-- relative to your vimwiki directory.
But PKBase runs in your web directory. So its home directory is not the
same as your vimwiki's. So those relative links won't work.

I don't know a way around this. You could make copies of all your images,
and put them in an `images/` subdirectory of PKBase, I suppose. But that's
about the best suggestion I can give you.

## License

You may use this software any way you like, and may modify it if
you prefer. All the code is included. If you make changes and
redistribute this software modified or unmodified to others, you
are required to include your source code. The terms of your use
are dictated by the GPLv2. If you do modify it, I'd like to be notified,
but you don't have to.

## Hacking/Technical Details

This application is written in PHP, and uses a model-view-controller
paradigm of my own design. It is built upon the Grotworx framework,
available where you got this software. The code is fairly well commented.
The directory hierarchy is relatively simple and should be
self-explanatory. Obviously, any changes you make to the code might be
overwritten if you attempt to upgrade to a newer version of PKBase
(assuming there is one).

PKBase uses the **Parsedown** library to parse content in "markdown"
format. It assumes markdown files have a `.md` extension. Markdown allows
you to specify bold and italic text, various levels of headline, tables,
and a variety of other types of formatting. It will also display Vim
"outline" files with an `.otl` extension. It will also display files which
have a `.txt` extension (plain text files). For the latter two, it will
maintain the formatting in the file. You may also serve up `.html` files,
which will be displayed as is. Styling is governed by the `style.css` file.
I've styled things the way I like them. You're free to change the styling.

