Aydus_Emailheaderfooter
=======================
Author: David Tay <davidt@aydus.com>
Version: 1.1.4

CHANGE LOG
1.1.4	12/18/14	Added last order details to preview
1.1.3	6/3/2014	Stripped out template tags and put back in after DOMDocument has finished loading/parsing template html
1.1.2	4/22/2014	Removed extraneous stuff
1.1.1	2/26/2014	Moved updates/templates into aydus directory
1.1		2/14/2014	Moved block methods into helper, added body templates
1.0		2/6/2014	Initial


DESCRIPTION

This module generates standard store emails that can be customized using familiar 
Magento transactional emails, themes and templates.


INSTALLATION INSTRUCTIONS

Place module files in the following locations:

app
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
			

HOW TO USE

Install the extension and let the data setup run. Once the data setup has completed, 
transactional emails under System -> Transactional Emails will be created and the 
default store email templates in core_config_data table will be set to use the new 
transactional emails. 

If needed, edit the _construct method in Template.php block to select the design for 
multi-store configurations. Currently, it selects the design for the default store.

Edit the footer and header templates to customize the email header and footer.


TODO
- Currently only generates major sales and customer templates. Include more templates 
like wishlist, rma, etc.
