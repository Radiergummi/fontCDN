fontCDN
=======

Serve webfonts from your own server! 
fontCDN is a simple solution to deliver web fonts written in php. 


## Why would I need this?
As a web developer, you most probably use non-system fonts from Google Fonts or Typekit. Now, while those are awesome and blazingly fast and all, sometimes you need a font just not hosted on any major CDN, so you host it on the domain yourself.  

This drags your speed down quite a bit, depending on the amount of fonts and styles and file formats you need. Throw in images, svg and script files - websites can become pretty demanding.

## So, what does it do?
fontCDN behaves a bit like Google Fonts:  
To make a certain font available, you include a css file in your document head. The link is constructed from multiple parameters, like so:

```
https://static.example.com/fonts/Roboto
```

Without parameters, the returned css will default to the _normal_-font-style and font-weight _400_, including only the required file type based on the clients user agent. For a current build of Google Chrome, the css would look like this:

```css
@font-face {
  font-family: "Roboto";
  font-style: normal;
  font-weight: 400;
  src: local("Roboto"), local("Roboto"), url(https://static.example.com/files/fonts/Roboto/Roboto-normal.woff2) format("woff2");
}
```


To get more styles or even fonts at once, alter the URL like this:
```
https://static.example.com/fonts/Roboto|italic:600,normal:300&Open+Sans|normal:100
```
This will include three @font-face - blocks, each with their respective file name.



It even catches common errors and informs about it by inserting a css comment in the output:
```
https://static.example.com/fonts/RObOtO|normal:120000&Source+Code+Pro|fancy
```

This will resolve to font name Roboto, set its weight to 900 (maximum value by the specs), set the font-style for Source Code Pro to normal and add font-weight 400 to it:

```css
/* Requested weight 120000 is too high, falling back to 900 */
/* Requested style fancy not available, falling back to normal */
/* No weight specified, falling back to 400 */
@font-face {
  font-family: "Roboto";
  font-style: normal;
  font-weight: 900;
  src: local("Roboto"), local("Roboto"), url(http://static.9dev.de/files/fonts/Roboto/Roboto-normal.woff2) format("woff2");
}

@font-face {
  font-family: "Source Code Pro";
  font-style: normal;
  font-weight: 400;
  src: local("Source Code Pro"), local("SourceCodePro"), url(http://static.9dev.de/files/fonts/SourceCodePro/SourceCodePro-normal.woff2) format("woff2");
}
```



## Is it complete yet?
Not by any measure. The configuration for what font styles to use, browsers to support, URLs to build etc etc. is still buried in the script. There should be a global config file. While this _can_ be used to only serve fonts, a snippet for svg files is already included, more to come.

Participation would be gladly recieved! I am by no means a php professional (as you maybe are able to tell from looking at the code - it's pretty rough ATM).
