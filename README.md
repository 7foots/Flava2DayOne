# Flava2DayOne

Import your entries from Flava iOS app to Day One.

## What it needs

At this point, this requires:

* [Day One Mac](http://bit.ly/DayOne-Mac)
* [The Day One CLI](http://dayoneapp.com/tools/)
* [iTools for Mac](http://mac.itools.cn/english), for export Flava files

## Export from Flava:

You need copy `Document` folder of Flava app from your iOS device. I do it with iTools, but you may use other app and way.

1. Connect your iOs device to Mac.
2. Open iTools.
3. Choose `Apps` tab.
4. Find Flava, click on the Document icon opposite app icon.
5. In Dialog that appear select `Document` folder and click `Export`.

## Import entries to Day One

1. Download the [.zip](https://github.com/7foots/Flava2DayOne/archive/master.zip) and unzip.
2. Copy script files into your exported Flava `Document` folder.
3. Quit Day One app.
4. Open Terminal app.
5. Go to your exported Flava `Document` folder: `$ cd path_to_folder`
6. Run import script: `$ php flava.php`
7. Wait until script done and say something like that: 

``` Complete!
Exported: 103 notes and 3 photos 
```
Congratulation! Use Day One.

## Dependencies

* [PlistParser class](https://github.com/jsjohnst/php_class_lib)