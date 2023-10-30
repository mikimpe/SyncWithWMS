# Mikimpe Sync With WMS

## Purpose of this module
This module has been developed to be paired with https://github.com/mikimpe/WMS  
Before installing this module, install https://github.com/mikimpe/WMS following the instructions in its README.md

## Dependencies
- https://github.com/mikimpe/WMS

## Installation
- Install https://github.com/mikimpe/WMS following the instructions in its README.md
- Inside the `app/code/Mikimpe` directory create the folder `SyncWithWMS`
- Download the latest release's source code from https://github.com/mikimpe/SyncWithWMS/releases
- Extract the content of the downloaded archive inside the newly created `app/code/Mikimpe/SyncWithWMS` folder
- Run `bin/magento module:enable Mikimpe_SyncWithWMS`
- Run `bin/magento setup:upgrade`

## Using the module
- You can perform **Sync With WMS** requests in product admin page by clicking the appropriate button.
- You can see the **Sync With WMS** requests history in the admin grid created by this module. You can find it by going in Mikimpe -> Sync With WMS Request History:
![mikimpe1](https://github.com/mikimpe/SyncWithWMS/assets/21277958/4fbc745b-c10a-4b0b-a5c9-5eeeddad532b)
![mikimpe2](https://github.com/mikimpe/SyncWithWMS/assets/21277958/8c88765f-24a1-4dfb-8d21-c1f104b6ef2d)
- This module is designed to operate in two different modes: by making the **Sync With WMS** request to the internal module Mikimpe_WMS or by making it to a remote endpoint. You can switch between these two modes by changing the configuration in Stores -> Configuration -> MIKIMPE -> WMS Sync -> Test Mode. There's no need to set the remote endpoint, as the default one is already functional.

## Testing errors
Keep clicking the **Sync With WMS** button until you get one.

## Configuration
This module doesn't require configuration after installation to function. However, you can modify its default configuration if needed.
You can find module's configuration in Stores -> Configuration -> MIKIMPE -> WMS Sync:

![configuration](https://github.com/mikimpe/SyncWithWMS/assets/21277958/68e4e369-19bc-495f-b178-b064dc5b3eec)

- **Enabled**: enable or disable the 'Sync With WMS' button in admin product page
- **Test Mode**: if enabled, 'Sync With WMS' requests will be directed to the internal module Mikimpe_WMS. If disabled, 'Sync With WMS' requests will be directed to the endpoint configured in the 'WMS Endpoint' setting.
- **WMS Endpoint**: this setting is visible only if **Test Mode** setting is disabled. Configure this setting to specify the endpoint where "Sync With WMS' requests should be executed if **Test Mode** is disabled. Its default value is `https://api.mockfly.dev/mocks/5df029e8-40cd-43d0-9c50-9cbf48467a8b/wms-product-qty/`. WARNING: changing this value will result in errors during the 'Sync With WMS' operation if the response from the new endpoint won't have the same structure of the default one.
- **Enable request logger**: if enabled, 'Sync With WMS' requests' details will be logged in `var/log/mikimpe_wms.log`

## Logs
Any logs are found in `var/log/mikimpe_wms.log`

## Development environment
- Magento 2.4.6-p3
- PHP 8.2
