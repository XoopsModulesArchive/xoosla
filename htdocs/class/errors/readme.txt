So you found me did you? :-p

Well, if you want to create your own custom error pages, it is very easy to do. 

Just create a normal HTML file with all your contents and save it to the following place:

	Xoops_root_path/languages/{Your language}/custom_error_pages/

and save the file with the following format:
	custom_{number of error IE 404}.tpl
	
You can use smarty templates if you like, totally up to you and you can follow the default template to get
some info captured by the xoops404 Class and display it within your custom error page.

Note:

Custom pages will only work with the .htaccess directive. You will be required to rename htaccess.txt to .htaccess. If the file 
already exists then please copy the contents of htaccess.txt to .htaccess first.

Catz