# Magento for Gimmie SaaS

- Embed Gimmie Widget scripts on page
- Trigger events on Gimmie SaaS
- Redirect user to Gimmie SaaS panel when click on link in Admin
- No local settings that user can change
- Have a config file generate from SaaS service

## Events

- Register
- Login
- View item
- Purchases item

## Flow

- Site owner visit Gimmie/Magento Connect plugin page
- Click on install Magento Plugin
- Redirect to Gimmie SaaS page to register and get Magento plugin link
- Copy/Download Magento plugin and Install with Admin page
- Go back to Magento Admin and see __Gimmie__ link in Admin navigation
- Click on navigation redirect to Gimmie SaaS settings page

## Notes

- All events will trigger to Gimmie, no settings for disable this in Magento side
- Key and Secret embed in file/code. (This will generate by script some where.)
- No settings to disable components. Don't want to use it, disable or uninstall.
