Emailheaderfooter
=======================

Description
-----------
This module generates standard store emails that can be customized using familiar 
Magento transactional emails, themes and templates.


Installation Instructions
-------------------------
Place module files in the following locations:

<pre>app
	code
		local
			Aydus
				Emailheaderfooter
					Block
						Adminhtml
							System
								Email
									Template
										Preview.php
						Template.php
					data
						emailheaderfooter_setup
							data-install-1.0.0.php
					etc
						config.xml
					Helper
						Data.php
						
	design
		frontend
			base
				default
					template
						aydus
							emailheaderfooter
								footer.phtml
								header.phtml
							
	etc
		modules
			Aydus_Emailheaderfooter.xml
</pre>	

How to use
-------------------------
Install the extension and let the data setup run. Once the data setup has completed, 
transactional emails under System -> Transactional Emails will be created and the 
default store email templates in core_config_data table will be set to use the new 
transactional emails. 

If needed, edit the _construct method in Template.php block to select the design for 
multi-store configurations. Currently, it selects the design for the default store.

Edit the footer and header templates to customize the email header and footer.
