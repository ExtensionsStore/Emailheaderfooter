Emailheaderfooter
=================
Automatically generates a consistent and customizable header and footer for standard 
Magento emails. As of Magento CE version 1.9.1 and EE 1.14.1, this extension is 
deprecated.

Description
-----------
Magento transactional emails are the standard emails that are sent out when the customer 
makes a purchase, registers for an account, etc. With this extension, you can apply a 
consistent header and footer to these emails. This extension will generate the header and 
footer markup for these emails; then you can customize the markup.

With Magento CE version 1.9.1 and EE 1.14.1, email header and footers are built into the 
RWD package. If you are using the latest RWD package, this extension will no longer generate 
the transactional emails for you as they are not needed; you can just customize the header 
and footer directly in the locale. See
http://www.magentocommerce.com/knowledge-base/entry/ee1141-ce191-responsive-email

This extension will also let you preview order details. 

How to use
----------
Upload the extension to your site and let the setup script run. Once the setup has 
completed, if you're not using the latest RWD package, transactional emails under 
System -> Transactional Emails will be created and the default store email templates in 
core_config_data table will be set to use the new transactional emails. 

If you're not using the latest RWD package, you can customize the header and footer by 
copying the header and footer templates in 
app/design/frontend/base/default/template/aydus/emailheaderfooter to your theme and 
customizing the templates there.
