# Dynamic Admin Front Name
A Magento 2 module that changes the admin front name periodically via cron and sends emails to admins the new backend url.

# Installation
- `composer require alaa/magento2-dynamic-front-name`
- `php bin/magento module:enable Alaa_DynamicFrontName`
- `php bin/magento setup:upgrade`

# Documentation
* Configuration from the admin panel _Admin > Stores > Configuration > Dynamic Front Name_.
* _Enable_ set to yes by default.
* _Sender Email_ can be configured by adding the sender email, if left empty then the email from the first admin  will be used as the sender.
* _Front Name Length_, sets the length of the string of the front name, 8 characters are default.
* _Cron Expression_, how often the front name will be changed. The default is once a week every Sunday.
* The front name is generated using Magento core functions to generate random strings.

# Running the cron via magerun2
```
php n98-magerun2.phar sys:cron:run backend_front_name_generate
```
# Development
The sending emails is triggered via area code emulation.
If _Sample Data_ is installed, then the following plugin from _Magento_CustomerSampleData_ module needs to be disabled because it prevents sending emails via area code emulation.

This module is not responsible for disabling the plugin, so you need to add the following to a `di.xml` file in a module with that responsibility.
```
<type name="Magento\Framework\Mail\TransportInterface">
        <plugin name="customer-sample-data-disable-registration-email-send" disabled="true"/>
</type>
``` 

Also make sure your module depends on _Magento_CustomerSampleData_ and configure in the `module.xml` file.

```
<sequence>
    <module name="Magento_CustomerSampleData" />
</sequence>
```

__Note__: Please make sure you don't send emails to customers when disabling this plugin, this plugin was put for a reason which is to prevent customers from getting emails in a test environment.
You can do this by removing magento cron entry in the crontab and instead run specific crons using the _magerun2_ tool.

# Contribution
Feel free to raise issues and contribute.

# License
MIT