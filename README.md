 

# Project Title
PostType - Create your own Post Type in Wordpress and add the meta fields you would like this post to include. 

This example shows how you can create your own Post Type and include meta boxes for each post you will create under this type. In addition, each post belongs to a specific category which can be filtered by the user when he is visiting the page. 

## Getting Started
In order to create your own Post Type, you need to modify the functions.php file and create three extra .php files. The archive-industrypartner.php file delivers the template of the page and the function where the filtering is happening. 

## Installing
First, you need to update the functions.php file, by including the code. This sample creates a Post Type named 
```Industry Partners```
with two meta boxes
```Intro - $intro_6_metabox```
and 
```Details - $details_7_metabox```

The template of the page is developed in archive-industrypartner.php. Based on the tab the user selects - the default ALL tab and 3 extra- the corresponding category is shown.

## Authors
Eva Triantafyllopoulou
