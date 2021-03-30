
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
- A place to house and edit your knowldege base files.

## Installation

Create a directory under your webserver which will hold this
software, and copy it there. Edit your `config/config.ini`.
Change the following lines to suit your needs:

```
content_dir = /home/paulf/pkbase
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
editor of your choice. However, you can, instead, add, edit and
delete files in this software. Towards the top on the right will
be buttons which allow you to edit, delete and add files. Each
takes you to a screen designed to handle that task. The operation
is dead simple. After you've made changes, click the "confirm"
button.

Adding a file requires you to select a "topic" or directory in
which to put the file. It also asks you for an "extension" for
the file. This can be `.md`, `.otl`, `.txt` or `.html`.

There is also a "Topics" button on the upper right. This allows
you to create a new subdirectory. For this, you must pick the
directory this subdirectory belongs in.

## File And Directory Names

For files and directories, you may use any characters you like. I
typically use capitalized phrases which I'll be able to easily
recognize later. However, when the file or directory name is
stored, any spaces will be turned into hyphens, and any
non-alphanumeric characters will be removed. When the file or
directory name is later displayed, The hyphens are turned into
spaces again, and individual words are capitalized ("title
case"). You are advised to follow these conventions if you create
files in your editor. I haven't tested the results if you don't.

There is also a way to specify a title separate from the name of your
file. To do so, you must add something like the following to the first
line of your file:

```
[title]:- "My Title"
```

It must be done exactly this way. The following rules apply:

1. The metadata must appear on the first line of the file
2. It must start at the first position on the line.
3. The word "title" must appear in square brackets.
4. Immediately following the part above should be a colon, followed
by a dash/hyphen, followed by a single space.
5. The title you wish should be enclosed in double quotes.

## Search

There is a "search" area in the upper right corner of the page.
You may enter any search term there and hit the button. Documents
which match your search will be listed, and you may click on one
to see it. This searches your entire catalog of files.

## Links To Other Documents

There are times when you may want to include an image or PDF or somesuch
in a document. You don't want to put these in your main content
directory; they're likely to muck things up. Instead, you may include
such files in subdirectories where your software lives. In particular,
I've created `images` and `documents` directories for this. In your
document, you would include a link to a file in one of these
directories. In markdown, that would be something like:

```
[Some Random Image](images/random.jpg)
```

For HTML, it might look like this:

```
<a href="images/random.jpg">Some Random Image</a>
```

## License

You may use this software any way you like, and may modify it if
you prefer. All the code is included. If you make changes and
redistribute this software modified or unmodified to others, you
are required to include your source code. The terms of your use
are dictated by the GPLv2. If you do modify it, I'd like to be notified,
but you don't have to.

## Hacking/Technical Details

This application is written in PHP, and uses a model-view-controller
paradigm of my own design. It has no front controller. Instead, there
are page controllers for each landing page, in the root directory for
this project. The views are in the `views/` directory. The model is in
the `models/` directory. It uses the **Parsedown** library to parse
content in "markdown" format. It assumes markdown files have a `.md`
extension. Markdown allows you to specify bold and italic text, various
levels of headline, tables, and a variety of other types of formatting.
It will also display Vim "outline" files with an `.otl` extension. It
will also display files which have a `.txt` extension (plain text
files). For the latter two, it will maintain the formatting in the file.
You may also serve up `.html` files, which will be displayed as is.
Styling is governed by the `style.css` file. I've styled things the way
I like them. You're free to change the styling.

