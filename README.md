# Paul's Knowledge Base

This package is designed to make available to non-programmers via a web
server the contents of your personal wiki. I use the word "wiki"
throughout, only because that is the tool you use as an author. From the
perspective of the user (not author), this looks like a knowledge base.
The difference is that this "wiki" has a visible index.

It assumes several things:

* You're doing a personal wiki on your local machine.
* Your wiki is being done with Vimwiki.
* Your wiki is using Markdown markup, rather than Vimwiki markup.
* Your local machine has a web server on it.
* You want to make the contents of your wiki available to others.

Basically, you administer your wiki/knowledge base with Vimwiki, and
others can see your content using a web interface.

## Installation

Copy this software to some directory served by your web server. Your
content will go in the `content` directory of the site. Create a
`toc.md` file in that directory which is an index of the files in your
wiki. You may add files and create new directories with files in them.
Your `toc.md` which looks like this:

```
* Foods
	* Fruits
		[[content/foods/fruits/apple.md|Apples]]
		[[content/foods/fruits/peaches.md|Peaches]]
		[[content/foods/fruits/grapes.md|Grapes]]
	* Meats
		[[content/foods/meats/lamb.md|Lamb]]
		[[content/foods/meats/beef.md|Beef]]
```

If you make this file the index for your wiki, you can add files on the
fly as you normally would in Vimwiki, by creating a Vimwiki link, and
hitting **Enter** on it to go to that file.

## Customization

The `config/config.ini` file allows you to change the name of your wiki,
the name of the content directory, etc. You are advised to edit this
before installation.

## Use Case

Here's my use case for this software. I dump a lot of random information
into my personal wiki, like details on appliances I've bought, cheat
sheets for software I use, construction project details, etc. My wife
can't access this information normally (it's on my personal machine),
and doesn't have the expertise to handle Vim or Vimwiki. Still, she
might need access to some of this information. So I run it on a web
server on my machine, and she can surf to my webserver from her machine.

## Technical Details

This package uses the Parsedown library to parse content from your wiki
pages, meaning you'll have to use markdown in your wiki. If you use
Vimwiki markup instead, you'll have to substitute some library which
understands Vimwiki markup to do your translation. Or, I suppose you
could write everything in HTHL. Good luck with that.

Come to think of it, you could use any markup you want. The advantage of
using Vimwiki is that I can hit **Enter** on a link and go to that file,
which will be created if it doesn't exist. Hit **Backspace** in that file
to go back to where I came from (the index file).

If you make changes to the contents of your content directory, there is
a button named "Rebuild Index" every page of the site. This will
generate a new `toc.md` (index) page for you.

