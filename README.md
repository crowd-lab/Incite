# A Quick Guide of Incite Installation

Since Incite is a plug-in to [Omeka](http://omeka.org), the this quick guide will assume you already have some basic knowlege about Omeka and already have Omeka installed. If you want to know more about Incite and what it is capable, please visit [Incite's website](http://incite.cs.vt.edu). This guide focuses on the installation part.


## Get the source

There are two ways you can get the source. 
* You can download the source in a zipped file from [Incite's website](http://incite.cs.vt.edu).
* You can also get it from the repository.


## Installation

### Where to place the source code
Once you have the source code, place the whole directory right under the plugins folder of Omeka. The structure would be like "omeka_root_directory/plugins/Incite/". Or you can do a **git clone** right under "omeka_root_directory/plugins/" and the directory structure would be right.

### How to plug Incite into Omeka
Once you have the code correctly placed in the Omeka plugins directory, you should be able to see it appear in the list of plugins. Like other plugins, click on the green "Install" button to install Incite.

### How to configurate or customize
If nothing goes wrong, you should now be at the configuration page. In this page, you can customize various aspects of Incite including logo, project title, project sponsors, social media, project description and so on. You should replace all the fillups with your own materials or turn some of them off if you don't need them.

If you just want to take a taste, the minimum you can do is to upload a logo and give some subjects/concepts that are required for the Connect crowdsourced task.

### Where the crowdsource platform is
After configuration, your collections on Omeka are ready to be crowdsourced! You should be able to visit the site at "http://your_server.domain/omeka_directory/incite". Give it a try and see how you might want to customize the site with your materials. You can always go back to the Omkea plugin list to configure and customize Incite like the previous step.



## Troubleshooting

### Customized logos not shown
This is usually caused by the server's lacking permission to upload your customized images into corresponding folders. Incite stores all the customized images (logos for your site and sponsors) in "/path/to/Omeka/plugins/Incite/views/shared/images/". Please make sure your server daemon has the permission to write files into those folders.



## Contact
If you have questions or feedback, you can reach the Incite development team at [incite-g@vt.edu](mailto:incite-g@vt.edu)
